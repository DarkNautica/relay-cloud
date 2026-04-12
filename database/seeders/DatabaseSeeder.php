<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::create([
            'name' => 'Demo User',
            'email' => 'demo@relay.cloud',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'plan' => 'startup',
        ]);

        $user->projects()->create([
            'name' => 'Production API',
            'max_connections' => 1000,
        ]);

        $user->projects()->create([
            'name' => 'Staging Environment',
            'max_connections' => 1000,
        ]);
    }
}
