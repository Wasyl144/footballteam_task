<?php

namespace App\Dtos\Duels;

readonly class DuelsHistoryDto
{
    public function __construct(
        public int $id,
        public string $playerName,
        public string $opponentName,
        public int $won,
    ) {
    }
}
