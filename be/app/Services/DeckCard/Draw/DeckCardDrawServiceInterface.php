<?php

namespace App\Services\DeckCard\Draw;

interface DeckCardDrawServiceInterface
{
    public function getDrawCard(int $userId): void;
}
