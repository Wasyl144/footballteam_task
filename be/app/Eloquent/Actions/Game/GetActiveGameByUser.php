<?php

namespace App\Eloquent\Actions\Game;

use App\Models\Game;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

final class GetActiveGameByUser
{
    public static function execute(User $user): ?Game
    {
        return Game::active()
            ->valid()
            ->notFinished()
            ->whereHas('players', function (Builder $builder) use ($user): void {
                $builder->whereId($user->player->id);
            })
            ->first();
    }
}
