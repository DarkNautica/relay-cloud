<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\ActivityService;
use App\Services\AppRegistryService;
use App\Services\PlanService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
            Log::error('Stripe webhook signature verification failed', ['error' => $e->getMessage()]);

            return response('Invalid signature', 400);
        }

        Log::info('Stripe webhook received', ['type' => $event->type, 'id' => $event->id]);

        match ($event->type) {
            'checkout.session.completed' => $this->handleCheckoutCompleted($event->data->object, $registry),
            'customer.subscription.updated' => $this->handleSubscriptionUpdated($event->data->object, $registry),
            'customer.subscription.deleted' => $this->handleSubscriptionDeleted($event->data->object, $registry),
            default => Log::info('Stripe webhook unhandled event type', ['type' => $event->type]),
        };

        return response('OK', 200);
    }

    private function handleCheckoutCompleted(object $session, AppRegistryService $registry): void
    {
        $email = $session->customer_details->email ?? $session->customer_email ?? null;
        $customerId = $session->customer;
        $subscriptionId = $session->subscription;

        Log::info('Stripe checkout.session.completed', [
            'email' => $email,
            'customer' => $customerId,
            'subscription' => $subscriptionId,
        ]);

        if (! $email) {
            Log::error('Stripe checkout: no customer email in session', ['session_id' => $session->id]);

            return;
        }

        $user = User::where('email', $email)->first();

        if (! $user) {
            Log::error('Stripe checkout: no user found for email', ['email' => $email]);

            return;
        }

        // Resolve plan from the price ID on the subscription line item
        $plan = $this->resolvePlanFromSession($session);

        if (! $plan) {
            Log::error('Stripe checkout: could not resolve plan from session', ['session_id' => $session->id]);

            return;
        }

        $maxConnections = (new PlanService)->getPlan($plan)['max_connections'];

        $user->update([
            'stripe_id' => $customerId,
            'stripe_customer_id' => $customerId,
            'stripe_subscription_id' => $subscriptionId,
            'plan' => $plan,
        ]);

        $user->projects()->update(['max_connections' => $maxConnections]);

        $registry->syncToServer();

        ActivityService::log($user, 'plan.upgraded', 'Upgraded to ' . ucfirst($plan) . ' plan', ['plan' => $plan]);

        Log::info('Stripe checkout: user plan updated', [
            'user' => $user->id,
            'email' => $email,
            'plan' => $plan,
            'max_connections' => $maxConnections,
        ]);
    }

    private function handleSubscriptionUpdated(object $subscription, AppRegistryService $registry): void
    {
        $customerId = $subscription->customer;

        Log::info('Stripe customer.subscription.updated', [
            'customer' => $customerId,
            'status' => $subscription->status,
        ]);

        $user = $this->findUserByStripeCustomer($customerId);

        if (! $user) {
            Log::error('Stripe subscription.updated: no user found', ['customer' => $customerId]);

            return;
        }

        if ($subscription->status !== 'active') {
            Log::info('Stripe subscription.updated: status not active, skipping', ['status' => $subscription->status]);

            return;
        }

        $priceId = $subscription->items->data[0]->price->id ?? null;
        $plan = $this->resolvePlanFromPriceId($priceId);

        if (! $plan) {
            Log::error('Stripe subscription.updated: unknown price', ['price_id' => $priceId]);

            return;
        }

        $maxConnections = (new PlanService)->getPlan($plan)['max_connections'];

        $user->update(['plan' => $plan]);
        $user->projects()->update(['max_connections' => $maxConnections]);

        $registry->syncToServer();

        Log::info('Stripe subscription.updated: plan changed', ['user' => $user->id, 'plan' => $plan]);
    }

    private function handleSubscriptionDeleted(object $subscription, AppRegistryService $registry): void
    {
        $customerId = $subscription->customer;

        Log::info('Stripe customer.subscription.deleted', ['customer' => $customerId]);

        $user = $this->findUserByStripeCustomer($customerId);

        if (! $user) {
            Log::error('Stripe subscription.deleted: no user found', ['customer' => $customerId]);

            return;
        }

        $maxConnections = (new PlanService)->getPlan('hobby')['max_connections'];

        $user->update([
            'plan' => 'hobby',
            'stripe_subscription_id' => null,
        ]);
        $user->projects()->update(['max_connections' => $maxConnections]);

        $registry->syncToServer();

        Log::info('Stripe subscription.deleted: downgraded to hobby', ['user' => $user->id]);
    }

    private function findUserByStripeCustomer(string $customerId): ?User
    {
        return User::where('stripe_id', $customerId)
            ->orWhere('stripe_customer_id', $customerId)
            ->first();
    }

    private function resolvePlanFromSession(object $session): ?string
    {
        // Line items contain the price ID directly in the session for subscriptions
        $priceId = $session->line_items->data[0]->price->id ?? null;

        // If line_items aren't expanded on the session, check subscription metadata
        if (! $priceId && isset($session->subscription)) {
            try {
                $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
                $sub = $stripe->subscriptions->retrieve($session->subscription, ['expand' => ['items.data.price']]);
                $priceId = $sub->items->data[0]->price->id ?? null;
            } catch (\Exception $e) {
                Log::error('Stripe: failed to retrieve subscription', ['error' => $e->getMessage()]);

                return null;
            }
        }

        return $this->resolvePlanFromPriceId($priceId);
    }

    private function resolvePlanFromPriceId(?string $priceId): ?string
    {
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
}
