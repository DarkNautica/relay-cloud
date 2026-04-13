<?php

namespace App\Http\Controllers;

use App\Services\PlanService;
use App\Services\RelayServerService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request, PlanService $planService, RelayServerService $relay)
    {
        $user = auth()->user()->fresh();
        $currentPlan = $user->plan ?? 'hobby';
        $projects = $user->projects()->latest()->get();
        $totalConnections = $projects->sum('max_connections');
        $planName = PlanService::getPlan($currentPlan)['name'];

        $serverOnline = $relay->isServerOnline();
        $serverStats = $relay->getServerStats();

        $projectStats = [];
        foreach ($projects as $project) {
            $projectStats[$project->app_id] = $relay->getProjectStats($project->app_id, $project->app_secret);
        }

        return view('dashboard', compact(
            'projects', 'totalConnections', 'planName', 'currentPlan',
            'serverOnline', 'serverStats', 'projectStats'
        ));
    }

    public function dismiss2faNudge()
    {
        session(['hide_2fa_nudge' => true]);

        return redirect()->route('dashboard');
    }
}
