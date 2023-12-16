<?php

namespace App\Exceptions\Game;

use App\Exceptions\GeneralException;

class GameException extends GeneralException
{
    public static function gameNotFoundForUser(): self
    {
        return new self(__('Game not found. Please start a new game.'), 404);
    }

    public static function userDoesNotHaveEnoughCardsInDeck(): self
    {
        return new self(__('User does not have enough cards in deck. Get the cards from home page.'), 422);
    }
}
