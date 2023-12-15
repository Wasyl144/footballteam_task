<?php

namespace App\Services\Game\Opponent;

use App\Eloquent\Actions\Cards\GetAvailableCardsInGameByUser;
use App\Models\Level;
use App\Models\Move;
use App\Models\Round;
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

    public function createMove(User $opponent, Round $round): void
    {
        $cards = GetAvailableCardsInGameByUser::execute($opponent, $round->game);
        $cards->shuffle();
        $card = $cards->first();

        Move::create([
            'player_id' => $opponent->player->id,
            'round_id' => $round->id,
            'deck_card_id' => $card->id,
            'points' => $card->card->power,
        ]);
    }
}
