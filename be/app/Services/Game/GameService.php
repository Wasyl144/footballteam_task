<?php

namespace App\Services\Game;

use App\Enums\Game\GameStatusEnum;
use App\Models\Game;
use App\Models\User;
use App\Services\Game\Opponent\GameOpponentService;
use Carbon\Carbon;

class GameService implements GameServiceInterface
{
    public function __construct(
        private readonly int $maxRounds,
        private readonly GameOpponentService $gameOpponentService
    ) {
    }

    public function createGame(int $userId): void
    {
        $user = User::query()->with('player')->find($userId);
        $opponent = $this->gameOpponentService->prepareOpponent($user);

        $game = Game::create([
            'valid_until' => Carbon::now()->addMinutes(5),
            'finished_at' => null,
            'status' => GameStatusEnum::ACTIVE,
        ]);

        $game->addPlayer($user->player);
        $game->addPlayer($opponent->player);
    }
}
