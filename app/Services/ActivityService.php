<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\User;

class ActivityService
{
    public static function log(User $user, string $event, string $description, array $metadata = []): ActivityLog
    {
        return ActivityLog::create([
            'user_id' => $user->id,
            'event' => $event,
            'description' => $description,
            'metadata' => $metadata ?: null,
        ]);
    }
}
