<?php

namespace Tests\Unit\Jobs;

use App\Jobs\AddPlayerScoreJob;
use App\Models\Level;
use App\Models\User;
use Database\Seeders\CardSeeder;
use Database\Seeders\LevelSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AddPlayerScoreJobTest extends TestCase
{
    use RefreshDatabase;

    const MAX_ROUNDS = 5;

    const ROUND_VALID_MINUTES = 5;

    const MAX_PLAYERS = 2;

    const WIN_POINTS = 10;

    protected function setUp(): void
    {
        parent::setUp();

        // const value for number of rounds
        \Config::set('game.rounds.max_rounds', self::MAX_ROUNDS);
        \Config::set('game.rounds.win_points', self::WIN_POINTS);

        $this->seed(CardSeeder::class);
        $this->seed(LevelSeeder::class);
    }

    public function test_should_job_add_points_for_user(): void
    {
        $user = User::factory()->create();
        $userExpectedPoints = $user->player->points + self::WIN_POINTS;

        $job = new AddPlayerScoreJob($user->player);
        $job->handle();

        $this->assertEquals($userExpectedPoints, $user->player->points);
    }

    public function test_should_job_add_points_and_add_level_for_user(): void
    {
        $user = User::factory()->create();
        $userLevel = $user->player->level;
        /** @var Level $expectedLevel */
        $expectedLevel = Level::query()->whereLevelNumber($user->player->level + 1)->get()->first();
        $user->player->points = $expectedLevel->points_from - self::WIN_POINTS;
        $user->player->save();
        $userExpectedPoints = $user->player->points + self::WIN_POINTS;

        $job = new AddPlayerScoreJob($user->player);
        $job->handle();

        $this->assertEquals($userExpectedPoints, $user->player->points);
        $this->assertNotEquals($userLevel, $user->player->level);

    }
}
