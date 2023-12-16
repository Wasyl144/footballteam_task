<?php

namespace App\Eloquent\Actions\Cards;

use App\Models\DeckCard;
use App\Models\Game;
use App\Models\User;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;

final class GetAvailableCardsInGameByUser
{
    /**
     * @return Collection<DeckCard>
     */
    public static function execute(User $user, Game $game): Collection
    {
        $models = $user
            ->player
            ->deck
            ->deckCards()
            ->whereNotIn('id', function (Builder $builder) use ($game) {
                $builder
                    ->select('deck_cards.id')
                    ->from('deck_cards')
                    ->join('moves', 'moves.deck_card_id', '=', 'deck_cards.id')
                    ->join('rounds', 'rounds.id', '=', 'moves.round_id')
                    ->join('games', 'games.id', '=', 'rounds.game_id')
                    ->where('games.id', '=', $game->id);
            })
            ->get();

        return $models;
    }
}
