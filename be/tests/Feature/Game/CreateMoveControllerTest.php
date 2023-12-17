<?php

namespace Tests\Feature\Game;

use App\Eloquent\Actions\Cards\GetAvailableCardsInGameByUser;
use App\Jobs\FinishGameJob;
use App\Models\Game;
use App\Models\Score;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\Feature\HelpersTrait;
use Tests\TestCase;

class CreateMoveControllerTest extends TestCase
{
    use HelpersTrait;
    use RefreshDatabase;

    public string $endpoint = '/api/duels/action';

    public string $url = 'http://localhost';

    protected function setUp(): void
    {
        parent::setUp();

        $this->prepareSetUp();
    }

    public function test_should_create_move_if_last_round_and_send_event(): void
    {
        \Queue::fake();

        $user = User::factory()->create();
        $this->prepareDeckCards($user, self::MAX_ROUNDS);
        $game = $this->generateDummyGameNotFinishedWithCountRounds($user, self::MAX_ROUNDS);

        $this->assertDatabaseCount(User::class, 2);
        $this->assertDatabaseCount(Game::class, 1);
        $this->assertDatabaseCount(Score::class, 0);

        Sanctum::actingAs($user);

        $cards = GetAvailableCardsInGameByUser::execute($user, $game);

        $this->postJson($this->getUri(), [
            'id' => $cards->random()->id,
        ])->assertOk();

        \Queue::assertPushed(FinishGameJob::class);
        $this->assertDatabaseCount(User::class, 2);
        $this->assertDatabaseCount(Game::class, 1);
        $game->refresh();
        $this->assertTrue($game->finished_at !== null);
    }

    public function test_should_create_move_if_round_is_active(): void
    {
        \Queue::fake();

        $user = User::factory()->create();
        $this->prepareDeckCards($user, self::MAX_ROUNDS);
        $game = $this->generateDummyGameNotFinishedWithCountRounds($user, self::MAX_ROUNDS - 1);

        $this->assertDatabaseCount(User::class, 2);
        $this->assertDatabaseCount(Game::class, 1);

        Sanctum::actingAs($user);

        $cards = GetAvailableCardsInGameByUser::execute($user, $game);

        $this->postJson($this->getUri(), [
            'id' => $cards->random()->id,
        ])->assertOk();
        \Queue::assertNotPushed(FinishGameJob::class);
        $this->assertDatabaseCount(User::class, 2);
        $this->assertDatabaseCount(Game::class, 1);
        $game->refresh();
        $this->assertTrue($game->finished_at === null);
    }

    public function test_should_not_create_move_if_game_is_not_valid_doesnt_exists(): void
    {
        \Queue::fake();

        $user = User::factory()->create();
        $this->prepareDeckCards($user, self::MAX_ROUNDS);
        $game = $this->generateDummyGameNotFinishedWithCountRounds($user, self::MAX_ROUNDS - 1);

        $this->assertDatabaseCount(User::class, 2);
        $this->assertDatabaseCount(Game::class, 1);

        Sanctum::actingAs($user);

        $cards = GetAvailableCardsInGameByUser::execute($user, $game);

        $this->travel(10)->minutes();

        $this->postJson($this->getUri(), [
            'id' => $cards->random()->id,
        ])->assertForbidden();
        \Queue::assertNotPushed(FinishGameJob::class);
        $this->assertDatabaseCount(User::class, 2);
        $this->assertDatabaseCount(Game::class, 1);
        $game->refresh();
        $this->assertTrue($game->finished_at === null);
    }

    public function test_should_not_create_move_if_game_doesnt_exists(): void
    {
        \Queue::fake();

        $user = User::factory()->create();
        $this->prepareDeckCards($user, self::MAX_ROUNDS);

        $this->assertDatabaseCount(User::class, 1);
        $this->assertDatabaseCount(Game::class, 0);
        $this->assertDatabaseCount(Score::class, 0);

        Sanctum::actingAs($user);

        $this->postJson($this->getUri(), [
            'id' => $user->player->deck->deckCards->random()->id,
        ])->assertForbidden();
        \Queue::assertNotPushed(FinishGameJob::class);
        $this->assertDatabaseCount(User::class, 1);
        $this->assertDatabaseCount(Game::class, 0);
    }
}
