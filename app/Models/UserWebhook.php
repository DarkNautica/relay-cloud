<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class UserWebhook extends Model
{
    protected $fillable = ['user_id', 'project_id', 'url', 'events', 'secret', 'is_active', 'last_triggered_at', 'failure_count', 'last_failed_at', 'is_paused'];

    protected function casts(): array
    {
        return [
            'events' => 'array',
            'is_active' => 'boolean',
            'is_paused' => 'boolean',
            'last_triggered_at' => 'datetime',
            'last_failed_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (UserWebhook $webhook) {
            if (! $webhook->secret) {
                $webhook->secret = 'whsec_' . Str::random(40);
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function deliveries()
    {
        return $this->hasMany(\App\Models\WebhookDelivery::class, 'webhook_id');
    }
}
