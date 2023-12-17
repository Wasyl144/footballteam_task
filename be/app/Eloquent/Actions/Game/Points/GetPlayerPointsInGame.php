<?php

namespace App\Eloquent\Actions\Game\Points;

use App\Models\Game;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

final class GetPlayerPointsInGame
{
    public static function execute(User $user, Game $game): ?int
    {
        return $user->player->moves()->whereHas('round', function (Builder $builder) use ($game) {
            $builder->whereGameId($game->id);
        })->sum('points');
    }
}
