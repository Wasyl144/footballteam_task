<?php

namespace App\Services\Game\Opponent;

use App\Models\User;

interface GameOpponentServiceInterface
{
    public function prepareOpponent(User $user): User;
}
