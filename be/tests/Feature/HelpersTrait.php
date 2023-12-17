<?php

namespace Tests\Feature;

use App\Models\Card;
use App\Models\User;
use Database\Seeders\CardSeeder;
use Database\Seeders\LevelSeeder;

trait HelpersTrait
{
    const MAX_ROUNDS = 5;

    const ROUND_VALID_MINUTES = 5;

    const MAX_PLAYERS = 2;

    const WIN_POINTS = 10;

    public function getUri(): string
    {
        return $this->url . $this->endpoint;
    }

    protected function prepareDeckCards(User $user, int $count = 5): void
    {
        $cards = Card::all()->shuffle();

        for ($i = 0; $i < $count; $i++) {
            $user->player->deck->deckCards()->create([
                'card_id' => $cards->random()->id,
            ]);
        }
    }

    protected function prepareSetUp()
    {
        // const value for number of rounds
        \Config::set('game.rounds.max_rounds', self::MAX_ROUNDS);
        \Config::set('game.rounds.win_points', self::WIN_POINTS);

        $this->seed(CardSeeder::class);
        $this->seed(LevelSeeder::class);
    }
}
