<?php

namespace App\Services;

use App\Models\User;

class PlanService
{
    const PLANS = [
        'hobby' => [
            'name' => 'Hobby',
            'price' => 0,
            'max_connections' => 100,
            'max_messages_day' => 500000,
            'max_projects' => 1,
        ],
        'startup' => [
            'name' => 'Startup',
            'price' => 19,
            'max_connections' => 1000,
            'max_messages_day' => 5000000,
            'max_projects' => 5,
        ],
        'business' => [
            'name' => 'Business',
            'price' => 49,
            'max_connections' => 10000,
            'max_messages_day' => -1,
            'max_projects' => 20,
        ],
    ];

    public static function getPlan(string $plan): array
    {
        return self::PLANS[$plan] ?? self::PLANS['hobby'];
    }

    public function getLimit(User $user, string $limit): int
    {
        $plan = self::getPlan($user->fresh()->plan ?? 'hobby');

        return $plan[$limit] ?? 0;
    }

    public function canCreateProject(User $user): bool
    {
        $freshUser = $user->fresh();
        $maxProjects = self::getPlan($freshUser->plan ?? 'hobby')['max_projects'];

        return $freshUser->projects()->count() < $maxProjects;
    }

    public function getUserPlanName(User $user): string
    {
        return self::getPlan($user->fresh()->plan ?? 'hobby')['name'];
    }
}
