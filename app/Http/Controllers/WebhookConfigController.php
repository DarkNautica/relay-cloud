<?php

namespace App\Http\Controllers;

use App\Models\UserWebhook;
use App\Services\ActivityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WebhookConfigController extends Controller
{
    public function index(Request $request)
    {
        $webhooks = $request->user()->webhooks()->with('project')->latest()->get();
        $projects = $request->user()->projects()->get();

        return view('webhooks.index', compact('webhooks', 'projects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'url' => 'required|url|starts_with:https://',
            'project_id' => 'nullable|exists:projects,id',
            'events' => 'required|array|min:1',
            'events.*' => 'string',
        ]);

        $user = $request->user();

        if ($request->project_id) {
            $project = $user->projects()->findOrFail($request->project_id);
        }

        $webhook = $user->webhooks()->create([
            'url' => $request->url,
            'project_id' => $request->project_id,
            'events' => $request->events,
        ]);

        ActivityService::log($user, 'webhook.created', 'Created webhook for ' . $request->url, [
            'webhook_id' => $webhook->id,
        ]);

        return back()->with('success', 'Webhook created.')->with('new_secret', $webhook->secret);
    }

    public function destroy(Request $request, UserWebhook $webhook)
    {
        if ($webhook->user_id !== $request->user()->id) {
            abort(403);
        }

        ActivityService::log($request->user(), 'webhook.deleted', 'Deleted webhook for ' . $webhook->url, [
            'webhook_id' => $webhook->id,
        ]);

        $webhook->delete();

        return back()->with('success', 'Webhook deleted.');
    }

    public function test(Request $request, UserWebhook $webhook)
    {
        if ($webhook->user_id !== $request->user()->id) {
            abort(403);
        }

        try {
            $response = Http::timeout(5)
                ->withHeaders([
                    'X-Relay-Signature' => hash_hmac('sha256', 'webhook.test', $webhook->secret),
                    'Content-Type' => 'application/json',
                ])
                ->post($webhook->url, [
                    'event' => 'webhook.test',
                    'timestamp' => now()->timestamp,
                    'data' => ['message' => 'This is a test webhook from Relay Cloud'],
                ]);

            $webhook->update(['last_triggered_at' => now()]);

            if ($response->successful()) {
                return back()->with('success', 'Test webhook sent! Response: ' . $response->status());
            }

            return back()->with('error', 'Webhook returned status ' . $response->status());
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to reach webhook URL: ' . $e->getMessage());
        }
    }

    public function pause(Request $request, UserWebhook $webhook)
    {
        if ($webhook->user_id !== $request->user()->id) {
            abort(403);
        }

        $webhook->update(['is_paused' => true]);

        ActivityService::log($request->user(), 'webhook.paused', 'Manually paused webhook: ' . $webhook->url, [
            'webhook_id' => $webhook->id,
        ]);

        return back()->with('success', 'Webhook paused.');
    }

    public function resume(Request $request, UserWebhook $webhook)
    {
        if ($webhook->user_id !== $request->user()->id) {
            abort(403);
        }

        $webhook->update([
            'is_paused' => false,
            'failure_count' => 0,
        ]);

        ActivityService::log($request->user(), 'webhook.resumed', 'Resumed webhook: ' . $webhook->url, [
            'webhook_id' => $webhook->id,
        ]);

        return back()->with('success', 'Webhook resumed.');
    }

    public function deliveries(Request $request, UserWebhook $webhook)
    {
        if ($webhook->user_id !== $request->user()->id) {
            abort(403);
        }

        $deliveries = $webhook->deliveries()
            ->latest()
            ->take(5)
            ->get()
            ->map(fn ($d) => [
                'id' => $d->id,
                'event' => $d->event,
                'status' => $d->status,
                'response_status' => $d->response_status,
                'attempt' => $d->attempt,
                'payload' => $d->payload,
                'response_body' => $d->response_body,
                'created_at' => $d->created_at->toIso8601String(),
                'delivered_at' => $d->delivered_at?->toIso8601String(),
            ]);

        return response()->json($deliveries);
    }
}
