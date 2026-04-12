<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\RelayServerService;
use Illuminate\Http\Request;

class DashboardStatsController extends Controller
{
    public function index(Request $request, RelayServerService $relay)
    {
        $user = $request->user();
        $projects = $user->projects()->where('is_active', true)->get();

        $serverOnline = $relay->isServerOnline();
        $serverStats = $relay->getServerStats();

        $projectStats = [];
        foreach ($projects as $project) {
            $projectStats[$project->id] = $relay->getProjectStats($project->app_id, $project->app_secret);
        }

        return response()->json([
            'server_online' => $serverOnline,
            'server' => $serverStats,
            'projects' => $projectStats,
        ]);
    }
}
