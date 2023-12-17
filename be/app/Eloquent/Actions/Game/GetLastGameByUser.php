<?php

namespace App\Eloquent\Actions\Game;

use App\Models\Game;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

final class GetLastGameByUser
{
    public static function execute(User $user): ?Game
    {
        return Game::query()
            ->valid()
            ->whereHas('players', function (Builder $builder) use ($user): void {
                $builder->whereId($user->player->id);
            })
            ->latest()
            ->first();
    }
}
