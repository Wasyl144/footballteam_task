<?php

namespace App\Services\Game\Opponent;

use App\Models\Level;
use App\Models\User;
use App\Services\DeckCard\Draw\DeckCardDrawServiceInterface;

class GameOpponentService implements GameOpponentServiceInterface
{
    public function __construct(
        private readonly DeckCardDrawServiceInterface $deckCardDrawService
    ) {
    }

    public function prepareOpponent(User $user): User
    {
        $opponent = User::factory()->create();
        $level = Level::query()->whereLevelNumber($user->player->level)->first();

        $opponent->player->level = $user->player->level;
        $opponent->player->points = random_int($level->points_from, $level->points_to);
        $opponent->player->save();

        for ($i = 0; $i < $level->max_cards; $i++) {
            $this->deckCardDrawService->getDrawCard($opponent->id);
        }

        return $opponent;
    }
}
