<?php

namespace Tests\Unit\Services\Duels;

use App\Dtos\Duels\DuelsHistoryDto;
use App\Dtos\User\Data\UserDataDto;
use App\Enums\Game\GameStatusEnum;
use App\Enums\Score\ScoreStatusEnum;
use App\Models\Card;
use App\Models\Game;
use App\Models\Level;
use App\Models\Round;
use App\Models\Score;
use App\Models\User;
use App\Services\Duels\DuelsServiceInterface;
use App\Services\User\Data\UserDataServiceInterface;
use Carbon\Carbon;
use Database\Seeders\CardSeeder;
use Database\Seeders\LevelSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DuelsServiceTest extends TestCase
{
    use RefreshDatabase;

    const MAX_ROUNDS = 5;

    const ROUND_VALID_MINUTES = 5;

    const MAX_PLAYERS = 2;

    private readonly DuelsServiceInterface $duelsService;

    protected function setUp(): void
    {
        parent::setUp();

        // const value for number of rounds
        \Config::set('game.rounds.max_rounds', self::MAX_ROUNDS);
        $this->duelsService = app()->make(DuelsServiceInterface::class);

        $this->seed(CardSeeder::class);
        $this->seed(LevelSeeder::class);
    }

    public function test_should_return_user_data(): void
    {
        $user = User::factory()->create();

        $this->prepareDeckCards($user);
        $game = $this->generateDummyGameFinished($user);

        $this->assertDatabaseHas(Score::class, [
            'player_id' => $user->player->id,
            'game_id' => $game->id
        ]);

        $result = $this->duelsService->getDuelsHistoryByUser($user->id);

        $this->assertCount(1, $result);

        $dto = $result[0];

        $score = $user->player->scores->first();


        $testDto = new DuelsHistoryDto(
            id: $score->id,
            playerName: $user->name,
            opponentName: $game->players()->whereNotIn('id', [$user->player->id])->get()->first()->user->name,
            won: $score->status->value
        );

        $this->assertEquals($testDto, $dto);
    }

    private function generateDummyGameFinished(User $user): Game
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

        $opponentPoints = 0;
        $userPoints = 0;

        for ($i = 0; $i < self::MAX_ROUNDS; $i++) {

            /** @var Round $round */
            $round = $game->rounds()->create([
                'round_number' => $i + 1
            ]);

            $cardFromUser = $user->player->deck->deckCards->random();
            $cardFromOpponent = $opponent->player->deck->deckCards->random();

            $round->moves()->create([
                'player_id' => $opponent->player->id,
                'deck_card_id' => $cardFromOpponent->id,
                'points' => $cardFromOpponent->card->power,
            ]);

            $opponentPoints += $cardFromOpponent->card->power;

            $round->moves()->create([
                'player_id' => $user->player->id,
                'deck_card_id' => $cardFromUser->id,
                'points' => $cardFromUser->card->power,
            ]);

            $userPoints += $cardFromUser->card->power;
        }

        if ($userPoints > $opponentPoints) {
            Score::create([
                'player_id' => $user->player->id,
                'status' => ScoreStatusEnum::WON,
                'game_id' => $game->id,
                'points' => $userPoints
            ]);

            Score::create([
                'player_id' => $opponent->player->id,
                'status' => ScoreStatusEnum::DEFEAT,
                'game_id' => $game->id,
                'points' => $opponentPoints
            ]);
        } else {
            Score::create([
                'player_id' => $user->player->id,
                'status' => ScoreStatusEnum::DEFEAT,
                'game_id' => $game->id,
                'points' => $userPoints
            ]);

            Score::create([
                'player_id' => $opponent->player->id,
                'status' => ScoreStatusEnum::WON,
                'game_id' => $game->id,
                'points' => $opponentPoints
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
