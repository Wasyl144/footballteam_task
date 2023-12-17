<?php

namespace Tests\Feature\User\Data;

use App\Models\Level;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\Feature\HelpersTrait;
use Tests\TestCase;

class UserDataControllerTest extends TestCase
{
    use HelpersTrait;
    use RefreshDatabase;

    public string $endpoint = '/api/user-data';

    public string $url = 'http://localhost';

    protected function setUp(): void
    {
        parent::setUp();

        $this->prepareSetUp();
    }

    public function test_should_receive_actual_user_data(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->getJson($this->getUri());

        /** @var Level $level */
        $level = Level::whereLevelNumber($user->player->level)->get()->first();

        /** @var Level $nextLevel */
        $nextLevel = Level::whereLevelNumber($user->player->level + 1)->get()->first();

        $response
            ->assertJson([
                'id' => $user->id,
                'username' => $user->name,
                'level' => $user->player->level,
                'level_points' => sprintf('%s/%s', $user->player->points, $nextLevel->points_from),
                'cards' => [],
                'new_card_allowed' => $level->max_cards > $user->player->deck->getTotalCardsInDeck,
            ])
            ->assertOk();
    }
}
