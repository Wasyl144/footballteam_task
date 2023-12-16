<?php

namespace App\Dtos\Game\Move;

readonly class CardMoveRequestDto
{
    public function __construct(
        public int $deckCardId,
    ) {
    }
}
