<?php

namespace App\Eloquent\Actions\Cards;

use App\Models\DeckCard;
use App\Models\Game;
use Illuminate\Database\Eloquent\Builder as Eloquent;

final class IsCardUsedInGame
{
    public static function execute(DeckCard $deckCard, Game $game): bool
    {
        $model = $game
            ->rounds()
            ->whereHas('moves', function (Eloquent $builder) use ($deckCard) {
                $builder->join('deck_cards', 'moves.deck_card_id', '=', 'deck_cards.id');
                $builder->where('deck_cards.id', $deckCard->id);
            })
            ->get()
            ->first;

        return $model != false;
    }
}
