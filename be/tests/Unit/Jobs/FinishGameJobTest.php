<?php

namespace Tests\Unit\Jobs;

use App\Eloquent\Actions\Game\Opponent\GetOpponentUserInGameWithoutExistsingUser;
use App\Eloquent\Actions\Game\Points\GetPlayerPointsInGame;
use App\Enums\Game\GameStatusEnum;
use App\Enums\Score\ScoreStatusEnum;
use App\Events\PlayerWonGameEvent;
use App\Jobs\FinishGameJob;
use App\Models\Card;
use App\Models\Game;
use App\Models\Round;
use App\Models\Score;
use App\Models\User;
use Carbon\Carbon;
use Database\Seeders\CardSeeder;
use Database\Seeders\LevelSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FinishGameJobTest extends TestCase
{
    use RefreshDatabase;

    const MAX_ROUNDS = 5;

    const ROUND_VALID_MINUTES = 5;

    const MAX_PLAYERS = 2;

    protected function setUp(): void
    {
        parent::setUp();

        // const value for number of rounds
        \Config::set('game.rounds.max_rounds', self::MAX_ROUNDS);

        $this->seed(CardSeeder::class);
        $this->seed(LevelSeeder::class);
    }

    public function test_should_job_calculate_points_and_add_score(): void
    {
        $user = User::factory()->create();
        $this->prepareDeckCards($user);
        $game = $this->generateDummyGameFinishedAlwaysWins($user);

        \Event::fake();

        $job = new FinishGameJob($game);
        $job->handle();

        \Event::assertDispatched(PlayerWonGameEvent::class);

        $this->assertDatabaseCount(Score::class, 2);

        $opponent = GetOpponentUserInGameWithoutExistsingUser::execute($user, $game);
        $userPoints = GetPlayerPointsInGame::execute($user, $game);
        $opponentPoints = GetPlayerPointsInGame::execute($opponent, $game);

        $this->assertDatabaseHas(Score::class, [
            'player_id' => $user->player->id,
            'points' => $userPoints,
            'status' => $userPoints > $opponentPoints ? ScoreStatusEnum::WON->value : ScoreStatusEnum::DEFEAT->value,
            'game_id' => $game->id,
        ]);

        $this->assertDatabaseHas(Score::class, [
            'player_id' => $opponent->player->id,
            'points' => $opponentPoints,
            'status' => $opponentPoints > $userPoints ? ScoreStatusEnum::WON->value : ScoreStatusEnum::DEFEAT->value,
            'game_id' => $game->id,
        ]);
    }

    public function test_should_job_calculate_points_and_add_score_draw(): void
    {
        $user = User::factory()->create();
        $this->prepareDeckCards($user);
        $game = $this->generateDummyGameFinishedByDraw($user);

        \Event::fake();

        $job = new FinishGameJob($game);
        $job->handle();

        \Event::assertNotDispatched(PlayerWonGameEvent::class);

        $this->assertDatabaseCount(Score::class, 2);

        $opponent = GetOpponentUserInGameWithoutExistsingUser::execute($user, $game);
        $userPoints = GetPlayerPointsInGame::execute($user, $game);
        $opponentPoints = GetPlayerPointsInGame::execute($opponent, $game);

        $this->assertDatabaseHas(Score::class, [
            'player_id' => $user->player->id,
            'points' => $userPoints,
            'status' => $userPoints > $opponentPoints ? ScoreStatusEnum::WON->value : ScoreStatusEnum::DEFEAT->value,
            'game_id' => $game->id,
        ]);

        $this->assertDatabaseHas(Score::class, [
            'player_id' => $opponent->player->id,
            'points' => $opponentPoints,
            'status' => $opponentPoints > $userPoints ? ScoreStatusEnum::WON->value : ScoreStatusEnum::DEFEAT->value,
            'game_id' => $game->id,
        ]);
    }

    private function generateDummyGameFinishedAlwaysWins(User $user): Game
    {
        $opponent = User::factory()->create();
        $this->prepareDeckCards($opponent);

        $game = Game::factory()->create([
            'valid_until' => Carbon::now(),
            'finished_at' => Carbon::now()->addMinutes(),
            'status' => GameStatusEnum::FINISHED,
        ]);

        $game->addPlayer($user->player);
        $game->addPlayer($opponent->player);

        $game->save();

        for ($i = 0; $i < self::MAX_ROUNDS; $i++) {

            /** @var Round $round */
            $round = $game->rounds()->create([
                'round_number' => $i + 1,
            ]);

            $cardFromUser = $user->player->deck->deckCards->random();
            $cardFromOpponent = $opponent->player->deck->deckCards->random();

            $round->moves()->create([
                'player_id' => $opponent->player->id,
                'deck_card_id' => $cardFromOpponent->id,
                'points' => $cardFromOpponent->card->power,
            ]);

            $round->moves()->create([
                'player_id' => $user->player->id,
                'deck_card_id' => $cardFromUser->id,
                'points' => $cardFromUser->card->power * 1000,
            ]);
        }

        return $game;
    }

    private function generateDummyGameFinishedByDraw(User $user): Game
    {
        $opponent = User::factory()->create();
        $this->prepareDeckCards($opponent);

        $game = Game::factory()->create([
            'valid_until' => Carbon::now(),
            'finished_at' => Carbon::now()->addMinutes(),
            'status' => GameStatusEnum::FINISHED,
        ]);

        $game->addPlayer($user->player);
        $game->addPlayer($opponent->player);

        $game->save();

        for ($i = 0; $i < self::MAX_ROUNDS; $i++) {

            /** @var Round $round */
            $round = $game->rounds()->create([
                'round_number' => $i + 1,
            ]);

            $cardFromUser = $user->player->deck->deckCards->random();
            $cardFromOpponent = $opponent->player->deck->deckCards->random();

            $round->moves()->create([
                'player_id' => $opponent->player->id,
                'deck_card_id' => $cardFromOpponent->id,
                'points' => 1,
            ]);

            $round->moves()->create([
                'player_id' => $user->player->id,
                'deck_card_id' => $cardFromUser->id,
                'points' => 1,
            ]);
        }

        return $game;
    }

    private function prepareDeckCards(User $user, int $count = 5): void
    {
        $cards = Card::all()->shuffle();

        for ($i = 0; $i < $count; $i++) {
            $user->player->deck->deckCards()->create([
                'card_id' => $cards->random()->id,
            ]);
        }
    }
}
