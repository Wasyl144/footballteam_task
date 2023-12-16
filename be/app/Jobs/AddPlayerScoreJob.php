<?php

namespace App\Jobs;

use App\Models\Level;
use App\Models\Player;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AddPlayerScoreJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private readonly Player $player
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $level = Level::query()->whereLevelNumber($this->player->level)->first();
        $points = $this->player->points + config('game.rounds.win_points');

        if ($points > $level->points_to) {
            $nextLevel = Level::whereLevelNumber($level->level_number + 1)->first();

            if (! $nextLevel) {
                $this->player->points = $level->points_to;
            } else {
                $this->player->points = $points;
                $this->player->level = $nextLevel->level_number;
            }
        } else {
            $this->player->points = $points;
        }

        $this->player->save();
    }
}
