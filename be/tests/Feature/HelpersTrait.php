<?php

namespace Tests\Feature;

use App\Enums\Game\GameStatusEnum;
use App\Enums\Score\ScoreStatusEnum;
use App\Models\Card;
use App\Models\Game;
use App\Models\Round;
use App\Models\Score;
use App\Models\User;
use Carbon\Carbon;
use Database\Seeders\CardSeeder;
use Database\Seeders\LevelSeeder;

trait HelpersTrait
{
    const MAX_ROUNDS = 5;

    const ROUND_VALID_MINUTES = 5;

    const MAX_PLAYERS = 2;

    const WIN_POINTS = 10;

    public function getUri(): string
    {
        return $this->url.$this->endpoint;
    }

    protected function prepareDeckCards(User $user, int $count = 5): void
    {
        $cards = Card::all()->shuffle();

        for ($i = 0; $i < $count; $i++) {
            $user->player->deck->deckCards()->create([
                'card_id' => $cards->random()->id,
            ]);
        }
    }

    protected function prepareSetUp(): void
    {
        // const value for number of rounds
        \Config::set('game.rounds.max_rounds', self::MAX_ROUNDS);
        \Config::set('game.rounds.win_points', self::WIN_POINTS);

        $this->seed(CardSeeder::class);
        $this->seed(LevelSeeder::class);
    }

    protected function generateDummyGameFinishedUserAlwaysWins(User $user): Game
    {
        $opponent = User::factory()->create();
        $this->prepareDeckCards($opponent);

        $game = Game::factory()->create([
            'valid_until' => Carbon::now()->addMinutes(5),
            'finished_at' => Carbon::now()->addMinutes(),
            'status' => GameStatusEnum::FINISHED,
        ]);

        $game->addPlayer($user->player);
        $game->addPlayer($opponent->player);

        $game->save();

        $opponentPoints = 0;
        $userPoints = 0;

        for ($i = 0; $i < self::MAX_ROUNDS; $i++) {

            /** @var Round $round */
            $round = $game->rounds()->create([
                'round_number' => $i + 1,
            ]);

            $cardFromUser = $user->player->deck->deckCards->random();
            $cardFromOpponent = $opponent->player->deck->deckCards->random();

            $round->moves()->create([
                'player_id' => $opponent->player->id,
                'deck_card_id' => $cardFromOpponent->id,
                'points' => $cardFromOpponent->card->power,
            ]);

            $opponentPoints += $cardFromOpponent->card->power;

            $round->moves()->create([
                'player_id' => $user->player->id,
                'deck_card_id' => $cardFromUser->id,
                'points' => $cardFromUser->card->power * 1000,
            ]);

            $userPoints += $cardFromUser->card->power;
        }

        if ($userPoints > $opponentPoints) {
            Score::create([
                'player_id' => $user->player->id,
                'status' => ScoreStatusEnum::WON,
                'game_id' => $game->id,
                'points' => $userPoints,
            ]);

            Score::create([
                'player_id' => $opponent->player->id,
                'status' => ScoreStatusEnum::DEFEAT,
                'game_id' => $game->id,
                'points' => $opponentPoints,
            ]);
        } else {
            Score::create([
                'player_id' => $user->player->id,
                'status' => ScoreStatusEnum::DEFEAT,
                'game_id' => $game->id,
                'points' => $userPoints,
            ]);

            Score::create([
                'player_id' => $opponent->player->id,
                'status' => ScoreStatusEnum::WON,
                'game_id' => $game->id,
                'points' => $opponentPoints,
            ]);
        }

        return $game;
    }

    protected function generateDummyGameNotFinishedWithCountRounds(User $user, int $conut = 5): Game
    {
        $opponent = User::factory()->create();
        $this->prepareDeckCards($opponent);

        $game = Game::factory()->create([
            'valid_until' => Carbon::now()->addMinutes(5),
            'finished_at' => null,
            'status' => GameStatusEnum::ACTIVE,
        ]);

        $game->addPlayer($user->player);
        $game->addPlayer($opponent->player);

        $game->save();

        for ($i = 0; $i < $conut; $i++) {

            /** @var Round $round */
            $round = $game->rounds()->create([
                'round_number' => $i + 1,
            ]);

            $cardFromUser = $user->player->deck->deckCards->random();
            $cardFromOpponent = $opponent->player->deck->deckCards->random();

            $round->moves()->create([
                'player_id' => $opponent->player->id,
                'deck_card_id' => $cardFromOpponent->id,
                'points' => $cardFromOpponent->card->power,
            ]);

            $round->moves()->create([
                'player_id' => $user->player->id,
                'deck_card_id' => $cardFromUser->id,
                'points' => $cardFromUser->card->power * 1000,
            ]);

        }

        return $game;
    }
}
