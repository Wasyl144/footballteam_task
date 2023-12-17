<?php

namespace Tests\Feature\Game;

use App\Models\Game;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\Feature\HelpersTrait;
use Tests\TestCase;

class StartGameControllerTest extends TestCase
{
    use HelpersTrait;
    use RefreshDatabase;

    public string $endpoint = '/api/duels';

    public string $url = 'http://localhost';

    protected function setUp(): void
    {
        parent::setUp();

        $this->prepareSetUp();
    }

    public function test_should_create_game_if_not_exists(): void
    {
        $user = User::factory()->create();
        $this->prepareDeckCards($user, self::MAX_ROUNDS);

        $this->assertDatabaseCount(User::class, 1);
        $this->assertDatabaseCount(Game::class, 0);

        Sanctum::actingAs($user);

        $this->postJson($this->getUri())->assertOk();
        $this->assertDatabaseCount(Game::class, 1);
        $this->assertDatabaseCount(User::class, 2);
    }

    public function test_should_not_create_game_if_game_started_and_valid(): void
    {
        $user = User::factory()->create();
        $this->prepareDeckCards($user, self::MAX_ROUNDS);

        $this->assertDatabaseCount(Game::class, 0);
        $this->assertDatabaseCount(User::class, 1);
        $game = $this->generateDummyGameNotFinishedWithCountRounds($user, 4);
        $this->assertDatabaseCount(Game::class, 1);
        $this->assertDatabaseCount(User::class, 2);

        Sanctum::actingAs($user);

        $this->postJson($this->getUri())->assertOk();
        $this->assertDatabaseCount(Game::class, 1);
        $this->assertDatabaseCount(User::class, 2);
    }

    public function test_should_create_game_if_game_is_finished(): void
    {
        $user = User::factory()->create();
        $this->prepareDeckCards($user, self::MAX_ROUNDS);

        $this->assertDatabaseCount(Game::class, 0);
        $this->assertDatabaseCount(User::class, 1);
        $game = $this->generateDummyGameFinishedUserAlwaysWins($user);
        $this->assertDatabaseCount(Game::class, 1);
        $this->assertDatabaseCount(User::class, 2);

        Sanctum::actingAs($user);

        $this->postJson($this->getUri())->assertOk();
        $this->assertDatabaseCount(Game::class, 2);
        $this->assertDatabaseCount(User::class, 3);
    }

    public function test_should_create_game_if_game_is_not_valid(): void
    {
        $user = User::factory()->create();
        $this->prepareDeckCards($user, self::MAX_ROUNDS);

        $this->assertDatabaseCount(Game::class, 0);
        $this->assertDatabaseCount(User::class, 1);
        $game = $this->generateDummyGameNotFinishedWithCountRounds($user, 4);
        $this->travel(10)->minutes();
        $this->assertDatabaseCount(Game::class, 1);
        $this->assertDatabaseCount(User::class, 2);

        Sanctum::actingAs($user);

        $this->postJson($this->getUri())->assertOk();
        $this->assertDatabaseCount(Game::class, 2);
        $this->assertDatabaseCount(User::class, 3);
    }
}
