<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Services\ActivityService;
use App\Services\AppRegistryService;
use App\Services\PlanService;
use App\Services\RelayServerService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $projects = $request->user()->projects()->latest()->get();

        return view('projects.index', compact('projects'));
    }

    public function create(Request $request, PlanService $planService)
    {
        $user = $request->user();
        $canCreate = $planService->canCreateProject($user);
        $maxProjects = $planService->getLimit($user, 'max_projects');
        $currentCount = $user->projects()->count();

        return view('projects.create', compact('canCreate', 'maxProjects', 'currentCount'));
    }

    public function store(Request $request, PlanService $planService, AppRegistryService $registry)
    {
        $user = auth()->user()->fresh();

        if (! $planService->canCreateProject($user)) {
            return back()->with('error', 'You have reached your plan\'s project limit. Please upgrade to create more projects.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $project = $user->projects()->create([
            'name' => $request->name,
            'max_connections' => $planService->getLimit($user, 'max_connections'),
        ]);

        $registry->syncToServer();

        ActivityService::log($user, 'project.created', 'Created project "' . $project->name . '"', ['project_id' => $project->id]);

        return redirect()->route('projects.show', $project)->with('success', 'Project created successfully.');
    }

    public function show(Request $request, Project $project, RelayServerService $relay)
    {
        if ($project->user_id !== $request->user()->id) {
            abort(403);
        }

        $liveStats = $relay->getProjectStats($project->app_id, $project->app_secret);
        $eventLog = array_slice($relay->getProjectEventLog($project->app_id, $project->app_secret), 0, 20);

        return view('projects.show', compact('project', 'liveStats', 'eventLog'));
    }

    public function pause(Request $request, Project $project, AppRegistryService $registry)
    {
        if ($project->user_id !== $request->user()->id) {
            abort(403);
        }

        $project->update(['is_active' => false]);
        $registry->syncToServer();

        ActivityService::log($request->user(), 'project.paused', 'Paused project "' . $project->name . '"', ['project_id' => $project->id]);

        return response()->json(['status' => 'paused']);
    }

    public function resume(Request $request, Project $project, AppRegistryService $registry)
    {
        if ($project->user_id !== $request->user()->id) {
            abort(403);
        }

        $project->update(['is_active' => true]);
        $registry->syncToServer();

        ActivityService::log($request->user(), 'project.resumed', 'Resumed project "' . $project->name . '"', ['project_id' => $project->id]);

        return response()->json(['status' => 'active']);
    }

    public function rotateKey(Request $request, Project $project, AppRegistryService $registry)
    {
        if ($project->user_id !== $request->user()->id) {
            abort(403);
        }

        $newKey = Str::lower(Str::random(32));
        $project->update(['app_key' => $newKey]);
        $registry->syncToServer();

        ActivityService::log($request->user(), 'project.key_rotated', 'Rotated app key for "' . $project->name . '"', ['project_id' => $project->id]);

        return redirect()->route('projects.show', $project)->with('rotated_key', $newKey);
    }

    public function rotateSecret(Request $request, Project $project, AppRegistryService $registry)
    {
        if ($project->user_id !== $request->user()->id) {
            abort(403);
        }

        $newSecret = Str::random(64);
        $project->update(['app_secret' => $newSecret]);
        $registry->syncToServer();

        ActivityService::log($request->user(), 'project.secret_rotated', 'Rotated app secret for "' . $project->name . '"', ['project_id' => $project->id]);

        return redirect()->route('projects.show', $project)->with('rotated_secret', $newSecret);
    }

    public function destroy(Request $request, Project $project, AppRegistryService $registry)
    {
        if ($project->user_id !== $request->user()->id) {
            abort(403);
        }

        $name = $project->name;
        $project->delete();

        $registry->syncToServer();

        ActivityService::log($request->user(), 'project.deleted', 'Deleted project "' . $name . '"');

        return redirect()->route('projects.index')->with('success', 'Project deleted successfully.');
    }
}
