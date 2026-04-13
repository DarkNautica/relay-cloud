<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Services\ActivityService;
use App\Services\RelayServerService;
use Illuminate\Http\Request;

class ObservabilityController extends Controller
{
    public function show(Request $request, Project $project, RelayServerService $relay)
    {
        if ($project->user_id !== $request->user()->id) {
            abort(403);
        }

        $metrics = $relay->getProjectMetrics($project);
        $initial = $relay->getProjectEvents($project, 25);

        ActivityService::log($request->user(), 'observability.viewed', 'Viewed observability for "' . $project->name . '"', ['project_id' => $project->id]);

        return view('observability.show', compact('project', 'metrics', 'initial'));
    }

    public function events(Request $request, Project $project, RelayServerService $relay)
    {
        if ($project->user_id !== $request->user()->id) {
            abort(403);
        }

        return response()->json($relay->getProjectEvents(
            $project,
            (int) $request->input('limit', 25),
            $request->input('cursor'),
            $request->input('channel')
        ));
    }

    public function event(Request $request, Project $project, string $eventId, RelayServerService $relay)
    {
        if ($project->user_id !== $request->user()->id) {
            abort(403);
        }

        return response()->json($relay->getEventDetail($project, $eventId));
    }

    public function replay(Request $request, Project $project, string $eventId, RelayServerService $relay)
    {
        if ($project->user_id !== $request->user()->id) {
            abort(403);
        }

        $ok = $relay->replayEvent($project, $eventId);

        if ($ok) {
            ActivityService::log($request->user(), 'event.replayed', 'Replayed event ' . $eventId . ' on "' . $project->name . '"', [
                'project_id' => $project->id,
                'event_id' => $eventId,
            ]);
        }

        return response()->json(['ok' => $ok]);
    }
}
