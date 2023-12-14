<?php

namespace App\Services\Draw;

use Random\Randomizer;

final class DrawService implements DrawServiceInterface
{
    public function __construct(
        private readonly Randomizer $randomizer
    ) {
    }

    public function drawArrayOfNumbers(array $items): int
    {
        $items = $this->randomizer->shuffleArray($items);

        return $items[0];
    }
}
