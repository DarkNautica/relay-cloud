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

    public function getActivePlan(User $user): string
    {
        if ($user->plan !== 'hobby' && $user->subscribed('default')) {
            return $user->plan;
        }

        if ($user->plan !== 'hobby' && ! $user->subscribed('default')) {
            return 'hobby';
        }

        return $user->plan;
    }

    public function getPlan(string $plan): array
    {
        return self::PLANS[$plan] ?? self::PLANS['hobby'];
    }

    public function getLimit(User $user, string $limit): int
    {
        $activePlan = $this->getActivePlan($user);
        $plan = $this->getPlan($activePlan);

        return $plan[$limit] ?? 0;
    }

    public function canCreateProject(User $user): bool
    {
        $maxProjects = $this->getLimit($user, 'max_projects');

        return $user->projects()->count() < $maxProjects;
    }

    public function getUserPlanName(User $user): string
    {
        return $this->getPlan($this->getActivePlan($user))['name'];
    }
}
