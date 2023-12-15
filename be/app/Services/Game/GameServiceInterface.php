<?php

namespace App\Services\Game;

use App\Dtos\Game\ActualInfo\ActualGameInfoDto;

interface GameServiceInterface
{
    public function createGame(int $userId): void;

    public function actualGameInfo(int $userId): ActualGameInfoDto;
}
