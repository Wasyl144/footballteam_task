<?php

namespace App\Exceptions\DeckCard\Draw;

use App\Exceptions\GeneralException;

class DrawException extends GeneralException
{
    public static function userHaveTooMuchCards(): self
    {
        return new self(__('User cannot get a card because You dont have a level'), 422);
    }

    public static function cannotPassMoreCardsThenExpectedByLevel(): self
    {
        return new self(__('User cannot get cards more then max level of user.'), 422);
    }
}
