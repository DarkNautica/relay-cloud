<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Services\ActivityService;
use App\Services\RelayServerService;
use Illuminate\Http\Request;

class InspectorController extends Controller
{
    public function show(Request $request, Project $project)
    {
        if ($project->user_id !== $request->user()->id) {
            abort(403);
        }

        ActivityService::log($request->user(), 'channel_inspector.viewed', 'Viewed channel inspector for "' . $project->name . '"', ['project_id' => $project->id]);

        return view('inspector.show', compact('project'));
    }

    public function channels(Request $request, Project $project, RelayServerService $relay)
    {
        if ($project->user_id !== $request->user()->id) {
            abort(403);
        }

        return response()->json($relay->getProjectChannels($project));
    }

    public function events(Request $request, Project $project, string $channel, RelayServerService $relay)
    {
        if ($project->user_id !== $request->user()->id) {
            abort(403);
        }

        return response()->json($relay->getChannelEvents(
            $project,
            $channel,
            (int) $request->input('limit', 25),
            $request->input('cursor')
        ));
    }
}
