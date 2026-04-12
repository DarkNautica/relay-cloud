<?php

namespace App\Http\Controllers;

use App\Services\PlanService;
use App\Services\RelayServerService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request, PlanService $planService, RelayServerService $relay)
    {
        $user = $request->user();
        $projects = $user->projects()->latest()->get();
        $totalConnections = $projects->sum('max_connections');
        $planName = $planService->getUserPlanName($user);

        $serverOnline = $relay->isServerOnline();
        $serverStats = $relay->getServerStats();

        return view('dashboard', compact(
            'projects', 'totalConnections', 'planName',
            'serverOnline', 'serverStats'
        ));
    }
}
