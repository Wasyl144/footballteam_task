<?php

namespace Tests\Unit\Services\User\Data;

use App\Dtos\User\Data\UserDataDto;
use App\Models\Level;
use App\Models\User;
use App\Services\User\Data\UserDataServiceInterface;
use Database\Seeders\CardSeeder;
use Database\Seeders\LevelSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserDataServiceTest extends TestCase
{
    use RefreshDatabase;

    const MAX_ROUNDS = 5;

    const ROUND_VALID_MINUTES = 5;

    const MAX_PLAYERS = 2;

    private readonly UserDataServiceInterface $userDataService;

    protected function setUp(): void
    {
        parent::setUp();

        // const value for number of rounds
        \Config::set('game.rounds.max_rounds', self::MAX_ROUNDS);
        $this->userDataService = app()->make(UserDataServiceInterface::class);

        $this->seed(CardSeeder::class);
        $this->seed(LevelSeeder::class);
    }

    public function test_should_return_user_data()
    {
        $user = User::factory()->create();

        $result = $this->userDataService->getUserData($user->id);
        $level = Level::whereLevelNumber($user->player->level)->get()->first();
        $nextLevel = Level::whereLevelNumber($user->player->level + 1)->get()->first();

        $testDto = new UserDataDto(
            id: $user->id,
            name: $user->name,
            level: $user->player->level,
            levelPoints: sprintf(
                '%s/%s',
                $user->player->points, $nextLevel?->points_from ?: $level->points_to
            ),
            cards: collect([]),
            isNewCardAllowed: true
        );

        $this->assertEquals($testDto->level, $result->level);
        $this->assertEquals($testDto->id, $result->id);
        $this->assertEquals($testDto->levelPoints, $result->levelPoints);
        $this->assertEquals($testDto->name, $result->name);
        $this->assertEquals($testDto->cards->count(), $result->cards->count());
        $this->assertEquals($testDto->isNewCardAllowed, $result->isNewCardAllowed);
    }
}
