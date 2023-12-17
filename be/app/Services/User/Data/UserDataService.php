<?php

namespace App\Services\User\Data;

use App\Dtos\User\Data\UserDataDto;
use App\Models\Level;
use App\Models\User;

class UserDataService implements UserDataServiceInterface
{
    public function getUserData(int $id): UserDataDto
    {
        $user = User::query()->with('player')->findOrFail($id);
        $level = Level::query()->whereLevelNumber($user->player->level)->first();
        $nextLevel = Level::whereLevelNumber($level->level_number + 1)->first();

        return new UserDataDto(
            id: $user->id,
            name: $user->name,
            level: $user->player->level,
            levelPoints: sprintf('%s/%s', $user->player->points, $nextLevel?->points_from ?: $level->points_to),
            cards: $user->player->deck->deckCards,
            isNewCardAllowed: $level->max_cards > $user->player->deck->getTotalCardsInDeck
        );
    }
}
