<?php

namespace App\Services\DeckCard\Draw;

use App\Exceptions\DeckCard\Draw\DrawException;
use App\Models\Card;
use App\Models\DeckCard;
use App\Models\Level;
use App\Models\User;

final class DeckCardDrawService implements DeckCardDrawServiceInterface
{
    public function __construct()
    {
    }

    public function drawCards(int $userId, int $count = 1): void
    {
        $user = User::query()->with('player')->findOrFail($userId);
        $level = Level::query()->whereLevelNumber($user->player->level)->first();

        if ($level->max_cards <= $user->player->deck->getTotalCardsInDeck) {
            throw DrawException::userHaveTooMuchCards();
        }

        $cards = Card::query()->inRandomOrder()->get()->shuffle();

        for ($i = 0; $i < $count; $i++) {
            $card = $cards->random();

            DeckCard::create([
                'deck_id' => $user->player->deck->id,
                'card_id' => $card->id,
            ]);
        }
    }
}
