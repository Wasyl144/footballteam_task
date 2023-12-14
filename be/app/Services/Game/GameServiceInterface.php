<?php

namespace App\Services\Game;

interface GameServiceInterface
{
    public function createGame(int $userId): void;
}
