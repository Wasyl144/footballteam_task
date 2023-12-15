<?php

namespace App\Services\Game;

use App\Dtos\Game\ActualInfo\ActualGameInfoDto;
use App\Dtos\Game\Move\CardMoveRequestDto;
use App\Eloquent\Actions\Cards\GetAvailableCardsInGameByUser;
use App\Eloquent\Actions\Cards\IsCardUsedInGame;
use App\Eloquent\Actions\Game\GetActiveGameByUser;
use App\Eloquent\Actions\Game\GetLastGameByUser;
use App\Eloquent\Actions\Game\Opponent\GetOpponentUserInGameWithoutExistsingUser;
use App\Eloquent\Actions\Game\Points\GetPlayerPointsInGame;
use App\Enums\Game\GameStatusEnum;
use App\Enums\Score\ScoreStatusEnum;
use App\Exceptions\Game\Card\CardException;
use App\Exceptions\Game\GameException;
use App\Models\DeckCard;
use App\Models\Game;
use App\Models\Move;
use App\Models\Player;
use App\Models\Round;
use App\Models\Score;
use App\Models\User;
use App\Services\Game\Opponent\GameOpponentServiceInterface;
use Carbon\Carbon;

class GameService implements GameServiceInterface
{
    public function __construct(
        private readonly int $maxRounds,
        private readonly GameOpponentServiceInterface $gameOpponentService
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
        $game = GetLastGameByUser::execute($user);

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

    public function createMove(int $userId, CardMoveRequestDto $dto): void
    {
        $user = User::query()->with('player')->find($userId);
        $game = GetActiveGameByUser::execute(user: $user);
        $opponent = GetOpponentUserInGameWithoutExistsingUser::execute(user: $user, game: $game);

        /** @var DeckCard|null $card */
        $card = DeckCard::query()
            ->whereId($dto->deckCardId)
            ->whereDeckId($user->player->deck->id)
            ->get()
            ->first();

        if (! $card) {
            throw CardException::gameCardNotFound();
        }

        if (IsCardUsedInGame::execute(deckCard: $card, game: $game)) {
            throw CardException::gameCardHasBeenUsedInGame();
        }

        /** @var Round|null $actualRound */
        $actualRound = $game->rounds()->orderBy('round_number', 'desc')->first();

        Move::create([
            'player_id' => $user->player->id,
            'round_id' => $actualRound->id,
            'deck_card_id' => $card->id,
            'points' => $card->card->power,
        ]);

        $this->gameOpponentService->createMove($opponent, $actualRound);

        if (! $this->checkForWin($game)) {
            $game->rounds()->create([
                'round_number' => $actualRound->round_number + 1,
            ]);
        }

    }

    private function checkForWin(?Game $game): bool
    {
        if ($game->rounds->count() < $this->maxRounds) {
            return false;
        }

        $game->status = GameStatusEnum::FINISHED;
        $game->finished_at = Carbon::now();
        $game->save();

        $scores = $game
            ->players
            ->map(function (Player $player) use ($game): array {
                return [
                    'player' => $player,
                    'points' => GetPlayerPointsInGame::execute($player->user, $game),
                ];
            })->sortBy('points', descending: true);

        $wonPlayer = $scores->shift();

        Score::create([
            'player_id' => $wonPlayer['player']->id,
            'status' => ScoreStatusEnum::WON,
            'game_id' => $game->id,
            'points' => $wonPlayer['points'],
        ]);

        $wonPlayer['player']->points += 20;
        $wonPlayer['player']->save();

        $scores->map(function (array $score) use ($game): Score {
            return Score::create([
                'player_id' => $score['player']->id,
                'status' => ScoreStatusEnum::DEFEAT,
                'game_id' => $game->id,
                'points' => $score['points'],
            ]);
        });

        return true;
    }
}
