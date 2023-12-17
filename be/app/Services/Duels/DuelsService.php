<?php

namespace App\Services\Duels;

use App\Dtos\Duels\DuelsHistoryDto;
use App\Models\Player;
use App\Models\Score;
use App\Models\User;

class DuelsService implements DuelsServiceInterface
{
    /**
     * @return DuelsHistoryDto[]
     */
    public function getDuelsHistoryByUser(int $userId): array
    {
        $user = User::findOrFail($userId);

        return $user->player->scores->map(function (Score $score) use ($user) {
            /** @var Player $opponent */
            $opponent = $score->game->players()->whereNotIn('id', [$user->player->id])->get()->first();

            return new DuelsHistoryDto(
                id: $score->id,
                playerName: $user->name,
                opponentName: $opponent->user->name,
                won: $score->status->value
            );
        })->toArray();
    }
}
