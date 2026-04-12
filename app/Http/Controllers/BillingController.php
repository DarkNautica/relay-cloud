<?php

namespace App\Http\Controllers;

use App\Services\PlanService;
use Illuminate\Http\Request;

class BillingController extends Controller
{
    public function index(Request $request, PlanService $planService)
    {
        $user = $request->user();
        $currentPlan = $user->plan;
        $plans = PlanService::PLANS;
        $projectCount = $user->projects()->count();
        $totalConnections = $user->projects()->sum('max_connections');

        return view('billing.index', compact('currentPlan', 'plans', 'projectCount', 'totalConnections'));
    }

    public function upgrade(Request $request, string $plan)
    {
        if (! array_key_exists($plan, PlanService::PLANS)) {
            abort(404);
        }

        $planDetails = PlanService::PLANS[$plan];

        return back()->with('info', "Stripe checkout for {$planDetails['name']} plan (\${$planDetails['price']}/mo) coming soon.");
    }
}
