<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Stripe\StripeClient;

class StripeSeeder extends Seeder
{
    private StripeClient $stripe;

    public function run(): void
    {
        $this->stripe = new StripeClient(config('services.stripe.secret'));

        [$startupProduct, $startupPrice] = $this->findOrCreatePlan(
            'Relay Cloud Startup',
            1900,
            ['plan' => 'startup', 'max_connections' => 1000, 'max_projects' => 5],
        );

        $this->command->info("Startup Product: {$startupProduct->id}");
        $this->command->info("Startup Price:   {$startupPrice->id}");

        [$businessProduct, $businessPrice] = $this->findOrCreatePlan(
            'Relay Cloud Business',
            4900,
            ['plan' => 'business', 'max_connections' => 10000, 'max_projects' => 20],
        );

        $this->command->info("Business Product: {$businessProduct->id}");
        $this->command->info("Business Price:   {$businessPrice->id}");

        $this->command->newLine();
        $this->command->info('Add these to your .env:');
        $this->command->info("STRIPE_STARTUP_PRICE_ID={$startupPrice->id}");
        $this->command->info("STRIPE_BUSINESS_PRICE_ID={$businessPrice->id}");
    }

    private function findOrCreatePlan(string $name, int $unitAmount, array $metadata): array
    {
        $product = $this->findProductByName($name);

        if ($product) {
            $this->command->warn("Product '{$name}' already exists ({$product->id}), reusing.");

            $price = $this->findActivePrice($product->id, $unitAmount);

            if ($price) {
                $this->command->warn("Price already exists ({$price->id}), reusing.");

                return [$product, $price];
            }
        } else {
            $product = $this->stripe->products->create([
                'name' => $name,
                'metadata' => $metadata,
            ]);

            $this->command->info("Created product '{$name}' ({$product->id}).");
        }

        $price = $this->stripe->prices->create([
            'product' => $product->id,
            'unit_amount' => $unitAmount,
            'currency' => 'usd',
            'recurring' => ['interval' => 'month'],
            'metadata' => $metadata,
        ]);

        $this->command->info("Created price ({$price->id}).");

        return [$product, $price];
    }

    private function findProductByName(string $name): ?object
    {
        $products = $this->stripe->products->search([
            'query' => "name:'{$name}' AND active:'true'",
            'limit' => 1,
        ]);

        return $products->data[0] ?? null;
    }

    private function findActivePrice(string $productId, int $unitAmount): ?object
    {
        $prices = $this->stripe->prices->all([
            'product' => $productId,
            'active' => true,
            'type' => 'recurring',
            'limit' => 10,
        ]);

        foreach ($prices->data as $price) {
            if ($price->unit_amount === $unitAmount && $price->recurring->interval === 'month') {
                return $price;
            }
        }

        return null;
    }
}
