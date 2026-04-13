<?php

namespace App\Jobs;

use App\Models\WebhookDelivery;
use App\Services\ActivityService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class DeliverWebhook implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(public WebhookDelivery $delivery) {}

    public function backoff(): array
    {
        return [60, 300, 1800];
    }

    public function handle(): void
    {
        $delivery = $this->delivery;
        $webhook = $delivery->webhook;

        if (!$webhook || $webhook->is_paused) {
            return;
        }

        $payloadJson = json_encode($delivery->payload);
        $signature = hash_hmac('sha256', $payloadJson, $webhook->secret);

        try {
            $response = Http::timeout(10)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'X-Relay-Signature' => $signature,
                    'X-Relay-Event' => $delivery->event,
                    'User-Agent' => 'RelayCloud-Webhooks/1.0',
                ])
                ->withBody($payloadJson, 'application/json')
                ->post($webhook->url);

            $delivery->update([
                'response_status' => $response->status(),
                'response_body' => mb_substr($response->body(), 0, 2000),
                'attempt' => $this->attempts(),
            ]);

            if ($response->successful()) {
                $delivery->update([
                    'status' => 'delivered',
                    'delivered_at' => now(),
                ]);
                $webhook->update([
                    'last_triggered_at' => now(),
                    'failure_count' => 0,
                ]);
            } else {
                $this->markFailed($delivery, $webhook);
            }
        } catch (\Exception $e) {
            $delivery->update([
                'response_body' => mb_substr($e->getMessage(), 0, 2000),
                'attempt' => $this->attempts(),
            ]);
            $this->markFailed($delivery, $webhook);
        }
    }

    private function markFailed(WebhookDelivery $delivery, $webhook): void
    {
        $isLastAttempt = $this->attempts() >= $this->tries;

        $delivery->update([
            'status' => $isLastAttempt ? 'failed' : 'retrying',
        ]);

        if ($isLastAttempt) {
            $newFailureCount = $webhook->failure_count + 1;
            $webhook->update([
                'failure_count' => $newFailureCount,
                'last_failed_at' => now(),
            ]);

            if ($newFailureCount >= 3) {
                $webhook->update(['is_paused' => true]);
                ActivityService::log(
                    $webhook->user,
                    'webhook.auto_paused',
                    'Webhook paused after 3 consecutive failures: ' . $webhook->url,
                    ['webhook_id' => $webhook->id]
                );
            }
        }

        if (!$isLastAttempt) {
            $this->release($this->backoff()[$this->attempts() - 1] ?? 60);
        }
    }
}
