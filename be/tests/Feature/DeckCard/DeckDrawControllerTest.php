<?php

namespace Tests\Feature\DeckCard;

use App\Models\DeckCard;
use App\Models\User;
use Database\Seeders\CardSeeder;
use Database\Seeders\LevelSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\Feature\HelpersTrait;
use Tests\TestCase;

class DeckDrawControllerTest extends TestCase
{
    use HelpersTrait;
    use RefreshDatabase;

    public string $endpoint = '/api/cards';
    public string $url = 'http://localhost';

    protected function setUp(): void
    {
        parent::setUp();

        $this->prepareSetUp();
    }

    public function test_should_draw_a_card_for_user(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $this->assertDatabaseCount(DeckCard::class, 0);
        $this->postJson($this->getUri())->assertOk();
        $this->assertDatabaseCount(DeckCard::class, 1);
    }

    public function test_should_not_draw_a_card_for_user_if_count_exceeded(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $this->prepareDeckCards($user);

        $this->assertDatabaseCount(DeckCard::class, 5);
        $this->postJson($this->getUri())->assertUnprocessable();
        $this->assertDatabaseCount(DeckCard::class, 5);
    }
}
