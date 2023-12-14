<?php

namespace App\Services\DeckCard\Draw;

use App\Exceptions\DeckCard\Draw\DrawException;
use App\Models\Card;
use App\Models\DeckCard;
use App\Models\Level;
use App\Models\User;
use App\Services\Draw\DrawServiceInterface;

final class DeckCardDrawService implements DeckCardDrawServiceInterface
{
    public function __construct(
        private readonly DrawServiceInterface $drawService
    ) {
    }

    public function getDrawCard(int $userId): void
    {
        $user = User::query()->with('player')->findOrFail($userId);
        $level = Level::query()->whereLevelNumber($user->player->level)->first();

        if ($level->max_cards <= $user->player->deck->getTotalCardsInDeck) {
            throw DrawException::userHaveTooMuchCards();
        }

        $cards = Card::lazy()->pluck('id')->toArray();
        $randomCard = $this->drawService->drawArrayOfNumbers($cards);
        $card = Card::query()->find($randomCard);

        $deckCard = DeckCard::create([
            'deck_id' => $user->player->deck->id,
            'card_id' => $card->id,
        ]);
    }
}
