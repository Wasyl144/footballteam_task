<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Level>
 */
class LevelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $from = fake()->randomNumber(1);
        $to = $from + 20;

        return [
            'level_number' => fake()->randomDigit(),
            'points_from' => $from,
            'points_to' => $to,
            'max_cards' => fake()->randomNumber(2),
        ];
    }
}
