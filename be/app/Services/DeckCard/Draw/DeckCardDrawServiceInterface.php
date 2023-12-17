<?php

namespace App\Services\DeckCard\Draw;

interface DeckCardDrawServiceInterface
{
    public function drawCards(int $userId, int $count = 1): void;
}
