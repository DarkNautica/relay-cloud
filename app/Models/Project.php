<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

#[Fillable(['name', 'max_connections', 'is_active'])]
class Project extends Model
{
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Project $project) {
            $project->app_id = 'app_' . Str::lower(Str::random(8));
            $project->app_key = Str::lower(Str::random(32));
            $project->app_secret = Str::random(64);
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function usageStats()
    {
        return $this->hasMany(UsageStat::class);
    }
}
