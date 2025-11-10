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
        // Create a default admin user
        User::factory()->create([
            'name' => 'Administrator',
            'email' => 'admin@votingosis.local',
            'password' => 'admin12345',
        ]);
    }
}