<?php

namespace App\Exceptions\Game\Card;

use App\Exceptions\GeneralException;

class CardException extends GeneralException
{
    public static function gameCardNotFound(): self
    {
        return new self(__('Card not found.'), 404);
    }

    public static function gameCardHasBeenUsedInGame(): self
    {
        return new self(__('Chosen card has been used.'), 422);
    }
}
