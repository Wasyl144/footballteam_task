<?php

namespace Database\Seeders;

use App\Models\Level;
use Illuminate\Database\Seeder;

class LevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (config()->get('game.level_ranges') as $datum) {
            Level::factory()->create($datum);
        }
    }
}
