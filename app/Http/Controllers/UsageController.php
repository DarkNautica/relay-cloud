<?php

namespace App\Http\Controllers;

use App\Models\UsageStat;
use App\Services\RelayServerService;
use Illuminate\Http\Request;

class UsageController extends Controller
{
    public function index(Request $request, RelayServerService $relay)
    {
        $user = $request->user();
        $projects = $user->projects()->get();
        $serverStats = $relay->getServerStats();

        $todayPeak = UsageStat::whereIn('project_id', $projects->pluck('id'))
            ->whereDate('recorded_at', today())
            ->max('connections_peak') ?? 0;

        $todayMessages = UsageStat::whereIn('project_id', $projects->pluck('id'))
            ->whereDate('recorded_at', today())
            ->sum('messages_count');

        $projectStats = [];
        foreach ($projects as $project) {
            $stats = $relay->getProjectStats($project->app_id, $project->app_secret);
            $projectStats[$project->id] = $stats;
        }

        return view('usage.index', compact('projects', 'serverStats', 'todayPeak', 'todayMessages', 'projectStats'));
    }

    public function stats(Request $request, RelayServerService $relay)
    {
        $user = $request->user();
        $projects = $user->projects()->get();

        $history = UsageStat::whereIn('project_id', $projects->pluck('id'))
            ->where('recorded_at', '>=', now()->subHours(24))
            ->orderBy('recorded_at')
            ->get()
            ->groupBy(fn ($s) => $s->recorded_at->format('H:00'))
            ->map(fn ($group) => $group->max('connections_peak'))
            ->toArray();

        $labels = [];
        $data = [];
        for ($i = 23; $i >= 0; $i--) {
            $hour = now()->subHours($i)->format('H:00');
            $labels[] = $hour;
            $data[] = $history[$hour] ?? 0;
        }

        return response()->json(compact('labels', 'data'));
    }
}
