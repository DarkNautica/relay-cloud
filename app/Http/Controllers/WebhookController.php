<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\AppRegistryService;
use App\Services\PlanService;
use Illuminate\Http\Request;
use Stripe\StripeClient;
use Stripe\Webhook;

class WebhookController extends Controller
{
    public function handle(Request $request, AppRegistryService $registry)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $secret = config('services.stripe.webhook.secret');

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $secret);
        } catch (\Exception $e) {
            return response('Invalid signature', 400);
        }

        match ($event->type) {
            'checkout.session.completed' => $this->handleCheckoutCompleted($event->data->object, $registry),
            'customer.subscription.updated' => $this->handleSubscriptionUpdated($event->data->object, $registry),
            'customer.subscription.deleted' => $this->handleSubscriptionDeleted($event->data->object, $registry),
            default => null,
        };

        return response('OK', 200);
    }

    private function handleCheckoutCompleted(object $session, AppRegistryService $registry): void
    {
        $user = User::where('stripe_id', $session->customer)->first();

        if (! $user) {
            return;
        }

        $plan = $this->getPlanFromSubscription($session->subscription);

        if ($plan) {
            $user->update([
                'stripe_customer_id' => $session->customer,
                'stripe_subscription_id' => $session->subscription,
                'plan' => $plan,
            ]);
            $this->updateProjectLimits($user, $plan);
            $registry->syncToServer();
        }
    }

    private function handleSubscriptionUpdated(object $subscription, AppRegistryService $registry): void
    {
        $user = User::where('stripe_id', $subscription->customer)->first();

        if (! $user) {
            return;
        }

        $plan = $this->getPlanFromSubscriptionObject($subscription);

        if ($plan && $subscription->status === 'active') {
            $user->update(['plan' => $plan]);
            $this->updateProjectLimits($user, $plan);
            $registry->syncToServer();
        }
    }

    private function handleSubscriptionDeleted(object $subscription, AppRegistryService $registry): void
    {
        $user = User::where('stripe_id', $subscription->customer)->first();

        if (! $user) {
            return;
        }

        $user->update([
            'plan' => 'hobby',
            'stripe_subscription_id' => null,
        ]);
        $this->updateProjectLimits($user, 'hobby');
        $registry->syncToServer();
    }

    private function getPlanFromSubscription(string $subscriptionId): ?string
    {
        $stripe = new StripeClient(config('services.stripe.secret'));
        $subscription = $stripe->subscriptions->retrieve($subscriptionId, ['expand' => ['items.data.price']]);

        return $this->getPlanFromSubscriptionObject($subscription);
    }

    private function getPlanFromSubscriptionObject(object $subscription): ?string
    {
        $priceId = $subscription->items->data[0]->price->id ?? null;

        if (! $priceId) {
            return null;
        }

        $prices = config('services.stripe.prices');

        foreach ($prices as $plan => $id) {
            if ($id === $priceId) {
                return $plan;
            }
        }

        return null;
    }

    private function updateProjectLimits(User $user, string $plan): void
    {
        $planService = app(PlanService::class);
        $maxConnections = $planService->getPlan($plan)['max_connections'];

        $user->projects()->update(['max_connections' => $maxConnections]);
    }
}
