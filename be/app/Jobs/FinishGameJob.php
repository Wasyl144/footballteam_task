<?php

namespace App\Jobs;

use App\Eloquent\Actions\Game\Points\GetPlayerPointsInGame;
use App\Enums\Score\ScoreStatusEnum;
use App\Events\PlayerWonGameEvent;
use App\Models\Game;
use App\Models\Player;
use App\Models\Score;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FinishGameJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private readonly Game $game
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $scores = $this->game
            ->players
            ->map(function (Player $player): array {
                return [
                    'player' => $player,
                    'points' => GetPlayerPointsInGame::execute($player->user, $this->game),
                ];
            })->sortBy('points', descending: true);

        $wonPlayer = $scores->shift();

        Score::create([
            'player_id' => $wonPlayer['player']->id,
            'status' => ScoreStatusEnum::WON,
            'game_id' => $this->game->id,
            'points' => $wonPlayer['points'],
        ]);

        event(new PlayerWonGameEvent($wonPlayer['player']));

        $scores->map(function (array $score): Score {
            return Score::create([
                'player_id' => $score['player']->id,
                'status' => ScoreStatusEnum::DEFEAT,
                'game_id' => $this->game->id,
                'points' => $score['points'],
            ]);
        });
    }
}
