<?php

namespace Database\Seeders;

use App\Models\Card;
use Illuminate\Database\Seeder;

class CardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (config('game.cards') ?? [] as $card) {
            Card::create([
                'name' => $card['name'],
                'power' => $card['power'],
                'image' => $card['image'],
            ]);
        }
    }
}
