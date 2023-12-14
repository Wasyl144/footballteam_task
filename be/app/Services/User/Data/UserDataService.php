<?php

namespace App\Services\User\Data;

use App\Dtos\User\Data\UserDataDto;
use App\Models\Level;
use App\Models\User;

class UserDataService implements UserDataServiceInterface
{
    public function getUserData(int $id): UserDataDto
    {
        $user = User::query()->findOrFail($id);
        $level = Level::query()->whereLevelNumber($user->player->level)->first();

        return new UserDataDto(
            id: $user->id,
            name: $user->name,
            level: $user->player->level,
            levelPoints: sprintf('%s/%s', $user->player->points, $level->points_to),
            cards: $user->player->deck->deckCards,
            isNewCardAllowed: $level->max_cards > $user->player->deck->deck_cards_count
        );
    }
}
