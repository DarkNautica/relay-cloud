<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebhookDelivery extends Model
{
    protected $fillable = [
        'webhook_id', 'event', 'channel', 'payload',
        'response_status', 'response_body', 'attempt',
        'status', 'delivered_at',
    ];

    protected function casts(): array
    {
        return [
            'payload' => 'array',
            'delivered_at' => 'datetime',
        ];
    }

    public function webhook()
    {
        return $this->belongsTo(UserWebhook::class, 'webhook_id');
    }
}
