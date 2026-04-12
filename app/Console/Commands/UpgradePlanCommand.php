<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\AppRegistryService;
use App\Services\PlanService;
use Illuminate\Console\Command;

class UpgradePlanCommand extends Command
{
    protected $signature = 'relay:upgrade-plan {email} {plan}';

    protected $description = 'Manually set a user\'s plan (hobby, startup, business)';

    public function handle(AppRegistryService $registry): int
    {
        $email = $this->argument('email');
        $plan = $this->argument('plan');

        if (! array_key_exists($plan, PlanService::PLANS)) {
            $this->error("Invalid plan '{$plan}'. Must be one of: hobby, startup, business");

            return self::FAILURE;
        }

        $user = User::where('email', $email)->first();

        if (! $user) {
            $this->error("No user found with email: {$email}");

            return self::FAILURE;
        }

        $planDetails = (new PlanService)->getPlan($plan);

        $user->update(['plan' => $plan]);
        $user->projects()->update(['max_connections' => $planDetails['max_connections']]);

        $registry->syncToServer();

        $this->info("Updated {$email} to {$planDetails['name']} plan");
        $this->info("  Max connections: {$planDetails['max_connections']}");
        $this->info("  Max projects: {$planDetails['max_projects']}");
        $this->info("  Projects updated: {$user->projects()->count()}");

        return self::SUCCESS;
    }
}
