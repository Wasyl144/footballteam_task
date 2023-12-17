<?php

namespace Tests\Unit\Services\Game\Opponent;

use App\Eloquent\Actions\Game\Opponent\GetOpponentUserInGameWithoutExistsingUser;
use App\Models\Card;
use App\Models\Level;
use App\Models\Move;
use App\Models\User;
use App\Services\Game\GameServiceInterface;
use App\Services\Game\Opponent\GameOpponentServiceInterface;
use Database\Seeders\CardSeeder;
use Database\Seeders\LevelSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GameOpponentServiceTest extends TestCase
{
    use RefreshDatabase;

    const MAX_ROUNDS = 5;

    const ROUND_VALID_MINUTES = 5;

    const MAX_PLAYERS = 2;

    private readonly GameServiceInterface $gameService;

    private readonly GameOpponentServiceInterface $gameOpponentService;

    protected function setUp(): void
    {
        parent::setUp();

        // const value for number of rounds
        \Config::set('game.rounds.max_rounds', self::MAX_ROUNDS);

        $this->gameService = app()->make(GameServiceInterface::class);
        $this->gameOpponentService = app()->make(GameOpponentServiceInterface::class);
        $this->seed(CardSeeder::class);
        $this->seed(LevelSeeder::class);
    }

    public function test_should_create_opponent_with_same_level_like_a_user_and_with_max_cards_in_level(): void
    {
        $user = User::factory()->create();
        $opponent = $this->gameOpponentService->prepareOpponent($user);

        /** @var Level $level */
        $level = Level::query()->whereLevelNumber($user->player->level)->get()->first();

        $this->assertDatabaseCount(User::class, 2);
        $this->assertTrue($opponent->player->level === $user->player->level);
        $this->assertTrue($opponent->id !== $user->id);
        $this->assertTrue($opponent->player->deck->deckCards->count() === $level->max_cards);
    }

    public function test_opponent_should_create_move(): void
    {
        $user = User::factory()->create();
        $this->prepareDeckCards($user, self::MAX_ROUNDS);
        $game = $this->gameService->createGame($user->id);
        $this->assertDatabaseCount(User::class, 2);

        $opponent = GetOpponentUserInGameWithoutExistsingUser::execute($user, $game);

        $move = $this->gameOpponentService->createMove($opponent, $game->getActualRound());

        $this->assertDatabaseHas(Move::class, [
            'id' => $move->id,
            'player_id' => $opponent->player->id,
        ]);

        $this->assertDatabaseCount(Move::class, 1);
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
