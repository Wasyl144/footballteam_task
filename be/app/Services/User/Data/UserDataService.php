<?php

namespace App\Services\User\Data;

use App\Dtos\User\Data\UserDataDto;
use App\Models\User;

class UserDataService implements UserDataServiceInterface
{
    public function getUserData(int $id): UserDataDto
    {
        $user = User::findOrFail($id);

        return new UserDataDto(
            id: $user->id,
            name: $user->name,
            level: $user->player->level,
            levelPoints: 'essa',
            cards: $user->player->deck->deckCards,
            isNewCardAllowed: false
        );
    }
}
