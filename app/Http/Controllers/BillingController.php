<?php

namespace App\Http\Controllers;

use App\Services\PlanService;
use Illuminate\Http\Request;

class BillingController extends Controller
{
    public function index(Request $request, PlanService $planService)
    {
        $user = $request->user();
        $currentPlan = $planService->getActivePlan($user);
        $plans = PlanService::PLANS;
        $projectCount = $user->projects()->count();
        $totalConnections = $user->projects()->sum('max_connections');
        $isSubscribed = $user->subscribed('default');

        return view('billing.index', compact('currentPlan', 'plans', 'projectCount', 'totalConnections', 'isSubscribed'));
    }

    public function checkout(Request $request, string $plan)
    {
        $priceId = config("services.stripe.prices.{$plan}");

        if (! $priceId) {
            abort(404);
        }

        $user = $request->user();

        $checkout = $user->newSubscription('default', $priceId)
            ->checkout([
                'success_url' => route('billing.index') . '?success=1',
                'cancel_url' => route('billing.index'),
            ]);

        return redirect($checkout->url);
    }

    public function portal(Request $request)
    {
        return $request->user()->redirectToBillingPortal(route('billing.index'));
    }
}
