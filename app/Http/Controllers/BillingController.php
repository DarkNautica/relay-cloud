<?php

namespace App\Http\Controllers;

use App\Services\PlanService;
use App\Services\RelayServerService;
use Illuminate\Http\Request;

class BillingController extends Controller
{
    public function index(Request $request, RelayServerService $relay)
    {
        $user = auth()->user()->fresh();
        $currentPlan = $user->plan ?? 'hobby';
        $plans = PlanService::PLANS;
        $projectCount = $user->projects()->count();
        $isSubscribed = $user->subscribed('default');
        $maxConnections = PlanService::getPlan($currentPlan)['max_connections'];
        $maxProjects = PlanService::getPlan($currentPlan)['max_projects'];

        $relayOnline = $relay->isServerOnline();
        $activeConnections = 0;

        if ($relayOnline) {
            $projects = $user->projects()->where('is_active', true)->get();
            foreach ($projects as $project) {
                $stats = $relay->getProjectStats($project->app_id, $project->app_secret);
                $activeConnections += $stats['connections'];
            }
        }

        return view('billing.index', compact(
            'currentPlan', 'plans', 'projectCount', 'activeConnections',
            'isSubscribed', 'maxConnections', 'maxProjects', 'relayOnline'
        ));
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
