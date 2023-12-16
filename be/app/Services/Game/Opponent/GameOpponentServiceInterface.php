<?php

namespace App\Services\Game\Opponent;

use App\Models\Round;
use App\Models\User;

interface GameOpponentServiceInterface
{
    public function prepareOpponent(User $user): User;

    public function createMove(User $opponent, Round $round): void;
}
