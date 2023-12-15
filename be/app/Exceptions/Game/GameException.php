<?php

namespace App\Exceptions\Game;

use App\Exceptions\GeneralException;

class GameException extends GeneralException
{
    public static function gameNotFoundForUser(): self
    {
        return new self(__('Game not found. Please start a new game.'), 404);
    }
}
