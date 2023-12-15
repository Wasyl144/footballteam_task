<?php

namespace App\Services\Game;

use App\Dtos\Game\ActualInfo\ActualGameInfoDto;
use App\Eloquent\Actions\Cards\GetAvailableCardsInGameByUser;
use App\Eloquent\Actions\Game\GetActiveGameByUser;
use App\Eloquent\Actions\Game\Opponent\GetOpponentUserInGameWithoutExistsingUser;
use App\Eloquent\Actions\Game\Points\GetPlayerPointsInGame;
use App\Enums\Game\GameStatusEnum;
use App\Exceptions\Game\GameException;
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

        if (GetActiveGameByUser::execute($user)) {
            return;
        }

        $opponent = $this->gameOpponentService->prepareOpponent($user);

        $game = Game::create([
            'valid_until' => Carbon::now()->addMinutes(5),
            'finished_at' => null,
            'status' => GameStatusEnum::ACTIVE,
        ]);

        $game->addPlayer($user->player);
        $game->addPlayer($opponent->player);

        $game->rounds()->create([
            'round_number' => 1,
        ]);
    }

    public function actualGameInfo(int $userId): ActualGameInfoDto
    {
        $user = User::query()->with('player')->find($userId);
        $game = GetActiveGameByUser::execute($user);

        if (! $game) {
            throw GameException::gameNotFoundForUser();
        }

        $actualRound = $game->rounds()->orderBy('round_number', 'desc')->first();
        $cards = GetAvailableCardsInGameByUser::execute($user, $game);
        $opponent = GetOpponentUserInGameWithoutExistsingUser::execute($user, $game);

        return new ActualGameInfoDto(
            round: $actualRound->round_number,
            yourPoints: GetPlayerPointsInGame::execute($user, $game),
            opponentPoints: GetPlayerPointsInGame::execute($opponent, $game),
            status: $game->getStatusText(),
            cards: $cards
        );
    }
}
