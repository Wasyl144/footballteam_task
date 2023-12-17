<?php

namespace App\Services\Game;

use App\Dtos\Game\ActualInfo\ActualGameInfoDto;
use App\Dtos\Game\Move\CardMoveRequestDto;
use App\Models\Game;
use App\Models\Move;

interface GameServiceInterface
{
    public function createGame(int $userId): ?Game;

    public function actualGameInfo(int $userId): ActualGameInfoDto;

    public function createMove(int $userId, CardMoveRequestDto $dto): ?Move;

    public function checkForWin(Game $game): bool;
}
