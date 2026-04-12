<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class UserWebhook extends Model
{
    protected $fillable = ['user_id', 'project_id', 'url', 'events', 'secret', 'is_active', 'last_triggered_at'];

    protected function casts(): array
    {
        return [
            'events' => 'array',
            'is_active' => 'boolean',
            'last_triggered_at' => 'datetime',
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
}
