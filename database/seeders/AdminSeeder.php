<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin 1
        User::query()->updateOrCreate(
            ['email' => 'admin1@votingosis.local'],
            [
                'name' => 'admin 1',
                'password' => 'password',
                'role' => 'admin',
                'email_verified_at' => now(),
            ],
        );

        // Admin 2
        User::query()->updateOrCreate(
            ['email' => 'admin2@votingosis.local'],
            [
                'name' => 'admin 2',
                'password' => 'password',
                'role' => 'admin',
                'email_verified_at' => now(),
            ],
        );
    }
}