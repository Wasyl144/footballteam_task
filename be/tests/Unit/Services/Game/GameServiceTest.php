<?php

namespace Tests\Unit\Services\Game;

use App\Dtos\Game\ActualInfo\ActualGameInfoDto;
use App\Dtos\Game\Move\CardMoveRequestDto;
use App\Eloquent\Actions\Cards\GetAvailableCardsInGameByUser;
use App\Eloquent\Actions\Game\GetActiveGameByUser;
use App\Exceptions\Game\Card\CardException;
use App\Exceptions\Game\GameException;
use App\Jobs\FinishGameJob;
use App\Models\Card;
use App\Models\DeckCard;
use App\Models\Game;
use App\Models\Move;
use App\Models\Round;
use App\Models\User;
use App\Services\Game\GameServiceInterface;
use Database\Seeders\CardSeeder;
use Database\Seeders\LevelSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class GameServiceTest extends TestCase
{
    use RefreshDatabase;

    const MAX_ROUNDS = 5;

    const ROUND_VALID_MINUTES = 5;

    const MAX_PLAYERS = 2;

    private readonly GameServiceInterface $gameService;

    protected function setUp(): void
    {
        parent::setUp();

        // const value for number of rounds
        \Config::set('game.rounds.max_rounds', self::MAX_ROUNDS);

        $this->gameService = app()->make(GameServiceInterface::class);
        $this->seed(CardSeeder::class);
        $this->seed(LevelSeeder::class);
    }

    public function test_should_create_game_if_user_have_enough_cards_for_rounds(): void
    {
        $this->assertDatabaseCount(Game::class, 0);

        $user = User::factory()->create();
        $this->prepareDeckCards(user: $user, count: self::MAX_ROUNDS);
        $this->gameService->createGame($user->id);

        $this->assertDatabaseCount(Game::class, 1);
        $game = Game::first();
        $this->assertDatabaseHas('game_player', [
            'game_id' => $game->id,
            'player_id' => $user->player->id,
        ]);

        $this->assertTrue($game->players->count() == self::MAX_PLAYERS);
    }

    public function test_should_not_create_game_if_user_doesnt_have_enough_cards_to_play(): void
    {
        $this->assertDatabaseCount(Game::class, 0);

        $this->expectException(GameException::class);
        $this->expectExceptionCode(422);

        $user = User::factory()->create();
        $this->prepareDeckCards(user: $user, count: self::MAX_ROUNDS - 1);
        $this->gameService->createGame($user->id);

        $this->assertDatabaseCount(Game::class, 0);
        $this->assertDatabaseMissing('game_player', [
            'player_id' => $user->player->id,
        ]);
    }

    public function test_should_not_create_game_if_game_is_valid(): void
    {
        $this->assertDatabaseCount(Game::class, 0);

        $user = User::factory()->create();
        $this->prepareDeckCards(user: $user, count: self::MAX_ROUNDS);
        $this->gameService->createGame($user->id);

        $this->assertDatabaseCount(Game::class, 1);
        $game = Game::first();
        $this->assertDatabaseHas('game_player', [
            'game_id' => $game->id,
            'player_id' => $user->player->id,
        ]);

        $this->assertTrue($game->players->count() == self::MAX_PLAYERS);

        $this->gameService->createGame($user->id);

        $this->assertDatabaseCount(Game::class, 1);
        $game = Game::first();
        $this->assertDatabaseHas('game_player', [
            'game_id' => $game->id,
            'player_id' => $user->player->id,
        ]);
        $this->assertTrue($game->players->count() == self::MAX_PLAYERS);
    }

    public function test_should_create_game_if_game_is_not_valid(): void
    {
        $this->assertDatabaseCount(Game::class, 0);

        $user = User::factory()->create();
        $this->prepareDeckCards(user: $user, count: self::MAX_ROUNDS);
        $this->gameService->createGame($user->id);

        $this->assertDatabaseCount(Game::class, 1);
        $game = Game::first();
        $this->assertDatabaseHas('game_player', [
            'game_id' => $game->id,
            'player_id' => $user->player->id,
        ]);

        $this->assertTrue($game->players->count() == self::MAX_PLAYERS);

        $this->travel(self::ROUND_VALID_MINUTES + 1)->minutes();

        $this->gameService->createGame($user->id);

        $this->assertDatabaseCount(Game::class, 2);
        $game = Game::latest()->first();
        $this->assertDatabaseHas('game_player', [
            'game_id' => $game->id,
            'player_id' => $user->player->id,
        ]);

        $this->assertTrue($game->players->count() == self::MAX_PLAYERS);
    }

    public function test_should_not_return_actual_game_info_if_game_not_exists(): void
    {
        $this->expectException(GameException::class);
        $this->expectExceptionCode(404);
        $this->assertDatabaseCount(Game::class, 0);

        $user = User::factory()->create();
        $this->gameService->actualGameInfo($user->id);
        $this->assertDatabaseCount(Game::class, 0);
    }

    public function test_should_return_actual_game_info_if_game_exists(): void
    {
        $this->assertDatabaseCount(Game::class, 0);
        $user = User::factory()->create();
        $this->prepareDeckCards(user: $user, count: self::MAX_ROUNDS);

        $this->gameService->createGame($user->id);
        $result = $this->gameService->actualGameInfo($user->id);

        $game = Game::first();

        $this->assertDatabaseCount(Game::class, 1);

        $testDto = new ActualGameInfoDto(
            round: 1,
            yourPoints: 0,
            opponentPoints: 0,
            status: $game->getStatusText(),
            cards: $user->player->deck->deckCards
        );

        $this->assertEquals($testDto, $result);
    }

    public function test_should_create_move_if_game_exists(): void
    {
        $this->assertDatabaseCount(Game::class, 0);

        $user = User::factory()->create();
        $this->prepareDeckCards(user: $user, count: self::MAX_ROUNDS);

        $this->assertDatabaseCount(Round::class, 0);
        $this->gameService->createGame($user->id);

        $this->assertDatabaseCount(Game::class, 1);
        $game = Game::first();
        $this->assertDatabaseHas('game_player', [
            'game_id' => $game->id,
            'player_id' => $user->player->id,
        ]);

        /** @var DeckCard $randomCard */
        $randomCard = $user->player->deck->deckCards->random();

        $this->assertDatabaseCount(Round::class, 1);

        $dto = new CardMoveRequestDto(
            deckCardId: $randomCard->id
        );

        $actualRound = $game->getActualRound();

        $this->gameService->createMove(
            userId: $user->id,
            dto: $dto
        );

        $move = $actualRound->moves()->wherePlayerId($user->player->id)->get()->first();

        $this->assertTrue($move !== false);

        $this->assertDatabaseHas(Move::class, [
            'player_id' => $user->player->id,
            'round_id' => $actualRound->id,
            'deck_card_id' => $randomCard->id,
            'points' => $randomCard->card->power,
        ]);

        $this->assertDatabaseCount(Round::class, 2);
    }

    public function test_should_not_create_move_if_game_not_exists(): void
    {
        $this->expectException(GameException::class);
        $this->expectExceptionCode(404);

        $this->assertDatabaseCount(Game::class, 0);

        $user = User::factory()->create();
        $this->prepareDeckCards(user: $user, count: self::MAX_ROUNDS);

        $this->assertDatabaseCount(Round::class, 0);

        /** @var DeckCard $randomCard */
        $randomCard = $user->player->deck->deckCards->random();

        $dto = new CardMoveRequestDto(
            deckCardId: $randomCard->id
        );

        $this->gameService->createMove(
            userId: $user->id,
            dto: $dto
        );

        $this->assertDatabaseCount(Game::class, 0);
        $this->assertDatabaseCount(Round::class, 0);
        $this->assertDatabaseCount(Move::class, 0);
    }

    public function test_should_throw_exception_if_card_not_exists(): void
    {
        $this->expectException(CardException::class);
        $this->expectExceptionCode(404);

        $this->assertDatabaseCount(Game::class, 0);

        $user = User::factory()->create();
        $this->prepareDeckCards(user: $user, count: self::MAX_ROUNDS);

        $this->assertDatabaseCount(Round::class, 0);

        $this->gameService->createGame($user->id);

        $this->assertDatabaseCount(Round::class, 1);
        $this->assertDatabaseCount(Game::class, 1);

        $game = Game::first();
        $this->assertDatabaseHas('game_player', [
            'game_id' => $game->id,
            'player_id' => $user->player->id,
        ]);

        $dto = new CardMoveRequestDto(
            deckCardId: 888888
        );

        $actualRound = $game->getActualRound();

        $this->gameService->createMove(
            userId: $user->id,
            dto: $dto
        );

        $this->assertDatabaseCount(Round::class, 1);
        $this->assertDatabaseCount(Game::class, 1);
        $this->assertDatabaseCount(Move::class, 2);
    }

    public function test_should_throw_exception_if_card_has_been_used(): void
    {
        $this->expectException(CardException::class);
        $this->expectExceptionCode(422);

        $this->assertDatabaseCount(Game::class, 0);

        $user = User::factory()->create();
        $this->prepareDeckCards(user: $user, count: self::MAX_ROUNDS);

        $this->assertDatabaseCount(Round::class, 0);
        $this->gameService->createGame($user->id);

        $this->assertDatabaseCount(Game::class, 1);
        $game = Game::first();
        $this->assertDatabaseHas('game_player', [
            'game_id' => $game->id,
            'player_id' => $user->player->id,
        ]);

        /** @var DeckCard $randomCard */
        $randomCard = $user->player->deck->deckCards->random();

        $this->assertDatabaseCount(Round::class, 1);

        $dto = new CardMoveRequestDto(
            deckCardId: $randomCard->id
        );

        $actualRound = $game->getActualRound();

        $this->gameService->createMove(
            userId: $user->id,
            dto: $dto
        );

        $move = $actualRound->moves()->wherePlayerId($user->player->id)->get()->first();

        $this->assertTrue($move !== false);

        $this->assertDatabaseHas(Move::class, [
            'player_id' => $user->player->id,
            'round_id' => $actualRound->id,
            'deck_card_id' => $randomCard->id,
            'points' => $randomCard->card->power,
        ]);

        $this->assertDatabaseCount(Round::class, 2);

        $actualRound = $game->getActualRound();

        $this->gameService->createMove(
            userId: $user->id,
            dto: $dto
        );

        $this->assertDatabaseMissing(Move::class, [
            'player_id' => $user->player->id,
            'round_id' => $actualRound->id,
            'deck_card_id' => $randomCard->id,
            'points' => $randomCard->card->power,
        ]);

        $this->assertDatabaseCount(Round::class, 2);
    }

    public function test_should_finish_a_game_if_is_last_round(): void
    {
        \Queue::fake();

        $user = User::factory()->create();
        $this->prepareDeckCards(user: $user, count: self::MAX_ROUNDS);

        $this->gameService->createGame($user->id);
        $game = GetActiveGameByUser::execute($user);

        for ($i = 0; $i < self::MAX_ROUNDS; $i++) {
            $cards = GetAvailableCardsInGameByUser::execute($user, $game);
            $dto = new CardMoveRequestDto(
                deckCardId: $cards->random()->id
            );
            $this->gameService->createMove($user->id, $dto);
        }

        \Queue::assertPushed(FinishGameJob::class);
        $game->refresh();

        $this->assertTrue($game->finished_at instanceof Carbon);
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
