<?php

namespace App\Services\Draw;

use App\Exceptions\DeckCard\Draw\DrawException;

interface DrawServiceInterface
{
    /**
     * @throws DrawException
     */
    public function drawArrayOfNumbers(array $items): int;
}
