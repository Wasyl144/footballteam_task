<?php

namespace App\Services\Game;

use App\Dtos\Game\ActualInfo\ActualGameInfoDto;
use App\Dtos\Game\Move\CardMoveRequestDto;

interface GameServiceInterface
{
    public function createGame(int $userId): void;

    public function actualGameInfo(int $userId): ActualGameInfoDto;

    public function createMove(int $userId, CardMoveRequestDto $dto): void;
}
