<?php

namespace App\Services;

use App\Jobs\DeliverWebhook;
use App\Models\UserWebhook;
use App\Models\WebhookDelivery;

class WebhookService
{
    public function deliver(UserWebhook $webhook, string $event, array $payload): WebhookDelivery
    {
        $delivery = WebhookDelivery::create([
            'webhook_id' => $webhook->id,
            'event' => $event,
            'channel' => $payload['channel'] ?? null,
            'payload' => $payload,
            'status' => 'pending',
            'attempt' => 1,
        ]);

        DeliverWebhook::dispatch($delivery);

        return $delivery;
    }
}
