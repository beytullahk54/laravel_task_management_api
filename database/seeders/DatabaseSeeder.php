<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Team;
use App\Models\Task;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Team::create([
            'name' => 'Team 1',
            'owner_id' => 1,
        ]);
        Team::create([
            'name' => 'Team 2',
            'owner_id' => 2,
        ]);
        Team::create([
            'name' => 'Team 3',
            'owner_id' => 3,
        ]);

        Task::factory()->count(10)->create();
    }
}
