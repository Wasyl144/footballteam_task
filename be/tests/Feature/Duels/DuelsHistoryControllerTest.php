<?php

namespace Tests\Feature\Duels;

use App\Models\Level;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\Feature\HelpersTrait;
use Tests\TestCase;

class DuelsHistoryControllerTest extends TestCase
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

    public function test_should_receive_duels_history(): void
    {
        $user = User::factory()->create();
        $this->prepareDeckCards($user);

        Sanctum::actingAs($user);

        $game = $this->generateDummyGameFinishedUserAlwaysWins($user);

        $this->getJson($this->getUri())->assertJsonStructure([
            '*' => [
                'id',
                'player_name',
                'opponent_name',
                'won'
            ]
        ])->assertOk();
    }
}
