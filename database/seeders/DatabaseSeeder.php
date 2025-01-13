<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // create 120 online player records each with a time differing by one day from the previous record
        for ($i = 0; $i < 120; $i++) {
            \App\Models\Misc\OnlinePlayers::factory()->create([
                'created_at' => now()->subDays(120)->addDays($i)->format('Y-m-d')
            ]);
        }
    }
}
