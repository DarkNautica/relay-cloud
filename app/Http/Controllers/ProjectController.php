<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Services\AppRegistryService;
use App\Services\PlanService;
use App\Services\RelayServerService;
use Illuminate\Http\Request;

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
        $user = $request->user();

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

    public function destroy(Request $request, Project $project, AppRegistryService $registry)
    {
        if ($project->user_id !== $request->user()->id) {
            abort(403);
        }

        $project->delete();

        $registry->syncToServer();

        return redirect()->route('projects.index')->with('success', 'Project deleted successfully.');
    }
}
