<?php

namespace Tests\Unit\Services\DeckCard\Draw;

use App\Dtos\User\Data\UserDataDto;
use App\Exceptions\DeckCard\Draw\DrawException;
use App\Models\DeckCard;
use App\Models\Level;
use App\Models\User;
use App\Services\DeckCard\Draw\DeckCardDrawServiceInterface;
use App\Services\User\Data\UserDataServiceInterface;
use Database\Seeders\CardSeeder;
use Database\Seeders\LevelSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeckCardDrawServiceTest extends TestCase
{
    use RefreshDatabase;

    const MAX_ROUNDS = 5;

    const ROUND_VALID_MINUTES = 5;

    const MAX_PLAYERS = 2;

    private readonly DeckCardDrawServiceInterface $deckCardDrawService;

    protected function setUp(): void
    {
        parent::setUp();

        // const value for number of rounds
        \Config::set('game.rounds.max_rounds', self::MAX_ROUNDS);
        $this->deckCardDrawService = app()->make(DeckCardDrawServiceInterface::class);

        $this->seed(CardSeeder::class);
        $this->seed(LevelSeeder::class);
    }

    public function test_should_draw_cards_to_round_max_cards(): void
    {
        $user = User::factory()->create();

        $this->deckCardDrawService->drawCards(
            userId: $user->id,
            count: self::MAX_ROUNDS
        );

        $this->assertDatabaseCount(DeckCard::class, 5);
    }

    public function test_should_not_draw_card_if_pass_too_much_cards_depends_on_level(): void
    {
        $this->expectException(DrawException::class);
        $this->expectExceptionCode(422);

        $user = User::factory()->create();

        /** @var Level $level */
        $level = Level::whereLevelNumber($user->player->level)->get()->first();

        $this->deckCardDrawService->drawCards(
            userId: $user->id,
            count: $level->max_cards + 1
        );

        $this->assertDatabaseCount(DeckCard::class, 0);
    }

    public function test_should_not_draw_cards_if_level_max_cards_exceeded(): void
    {
        $this->expectException(DrawException::class);
        $this->expectExceptionCode(422);

        $user = User::factory()->create();

        /** @var Level $level */
        $level = Level::whereLevelNumber($user->player->level)->get()->first();

        $this->deckCardDrawService->drawCards(
            userId: $user->id,
            count: $level->max_cards
        );

        $this->deckCardDrawService->drawCards(
            userId: $user->id,
            count: 1
        );

        $this->assertDatabaseCount(DeckCard::class, 5);
    }
}
