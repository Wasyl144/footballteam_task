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

        $this->deckCardDrawService->drawCards($opponent->id, $level->max_cards);

        return $opponent;
    }

    public function createMove(User $opponent, Round $round): ?Move
    {
        $cards = GetAvailableCardsInGameByUser::execute($opponent, $round->game)->shuffle();
        $card = $cards->first();

        return Move::create([
            'player_id' => $opponent->player->id,
            'round_id' => $round->id,
            'deck_card_id' => $card->id,
            'points' => $card->card->power,
        ]);
    }
}
