<?php

namespace App\Eloquent\Actions\Game\Opponent;

use App\Models\Game;
use App\Models\User;

final class GetOpponentUserInGameWithoutExistsingUser
{
    public static function execute(User $user, Game $game): ?User
    {
        $model = $game->players()->whereNotIn('player_id', [$user->player->id])->get()->first();

        return $model->user;
    }
}
