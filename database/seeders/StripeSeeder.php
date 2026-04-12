<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Stripe\StripeClient;

class StripeSeeder extends Seeder
{
    public function run(): void
    {
        $stripe = new StripeClient(config('services.stripe.secret'));

        // Startup Plan — $19/mo
        $startupProduct = $stripe->products->create([
            'name' => 'Relay Cloud Startup',
            'metadata' => [
                'plan' => 'startup',
                'max_connections' => 1000,
                'max_projects' => 5,
            ],
        ]);

        $startupPrice = $stripe->prices->create([
            'product' => $startupProduct->id,
            'unit_amount' => 1900,
            'currency' => 'usd',
            'recurring' => ['interval' => 'month'],
            'metadata' => [
                'plan' => 'startup',
                'max_connections' => 1000,
                'max_projects' => 5,
            ],
        ]);

        $this->command->info("Startup Product: {$startupProduct->id}");
        $this->command->info("Startup Price:   {$startupPrice->id}");

        // Business Plan — $49/mo
        $businessProduct = $stripe->products->create([
            'name' => 'Relay Cloud Business',
            'metadata' => [
                'plan' => 'business',
                'max_connections' => 10000,
                'max_projects' => 20,
            ],
        ]);

        $businessPrice = $stripe->prices->create([
            'product' => $businessProduct->id,
            'unit_amount' => 4900,
            'currency' => 'usd',
            'recurring' => ['interval' => 'month'],
            'metadata' => [
                'plan' => 'business',
                'max_connections' => 10000,
                'max_projects' => 20,
            ],
        ]);

        $this->command->info("Business Product: {$businessProduct->id}");
        $this->command->info("Business Price:   {$businessPrice->id}");

        $this->command->newLine();
        $this->command->info('Add these to your .env:');
        $this->command->info("STRIPE_STARTUP_PRICE_ID={$startupPrice->id}");
        $this->command->info("STRIPE_BUSINESS_PRICE_ID={$businessPrice->id}");
    }
}
