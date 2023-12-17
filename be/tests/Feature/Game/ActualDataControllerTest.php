<?php

namespace Tests\Feature\Game;

use App\Models\Game;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\Feature\HelpersTrait;
use Tests\TestCase;

class ActualDataControllerTest extends TestCase
{
    use HelpersTrait;
    use RefreshDatabase;

    public string $endpoint = '/api/duels/active';

    public string $url = 'http://localhost';

    protected function setUp(): void
    {
        parent::setUp();

        $this->prepareSetUp();
    }

    public function test_should_not_receive_info_if_game_not_exists(): void
    {
        $user = User::factory()->create();
        $this->prepareDeckCards($user, self::MAX_ROUNDS);

        $this->assertDatabaseCount(User::class, 1);
        $this->assertDatabaseCount(Game::class, 0);

        Sanctum::actingAs($user);

        $this->getJson($this->getUri())->assertNotFound();
        $this->assertDatabaseCount(User::class, 1);
        $this->assertDatabaseCount(Game::class, 0);
    }

    public function test_should_receive_info_if_game_exists_and_valid(): void
    {
        $user = User::factory()->create();
        $this->prepareDeckCards($user, self::MAX_ROUNDS);

        $this->assertDatabaseCount(Game::class, 0);
        $this->assertDatabaseCount(User::class, 1);
        $game = $this->generateDummyGameNotFinishedWithCountRounds($user, self::MAX_ROUNDS - 2);
        $this->assertDatabaseCount(Game::class, 1);
        $this->assertDatabaseCount(User::class, 2);

        Sanctum::actingAs($user);

        $response = $this->getJson($this->getUri())->assertOk();
        $this->assertDatabaseCount(Game::class, 1);
        $this->assertDatabaseCount(User::class, 2);

        $response->assertJsonStructure([
            'round',
            'your_points',
            'opponent_points',
            'status',
            'cards' => [
                '*' => [
                    'id',
                    'name',
                    'image',
                    'power',
                ],
            ],
        ]);
    }

    public function test_should_not_receive_info_if_game_in_not_valid(): void
    {
        $user = User::factory()->create();
        $this->prepareDeckCards($user, self::MAX_ROUNDS);

        $this->assertDatabaseCount(Game::class, 0);
        $this->assertDatabaseCount(User::class, 1);
        $game = $this->generateDummyGameNotFinishedWithCountRounds($user, self::MAX_ROUNDS - 2);
        $this->assertDatabaseCount(Game::class, 1);
        $this->assertDatabaseCount(User::class, 2);

        Sanctum::actingAs($user);

        $this->travel(10)->minutes();

        $response = $this->getJson($this->getUri())->assertNotFound();
        $this->assertDatabaseCount(Game::class, 1);
        $this->assertDatabaseCount(User::class, 2);
    }
}
