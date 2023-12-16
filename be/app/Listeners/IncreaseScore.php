<?php

namespace App\Listeners;

use App\Events\PlayerWonGameEvent;
use App\Jobs\AddPlayerScoreJob;

class IncreaseScore
{
    /**
     * Handle the event.
     */
    public function handle(PlayerWonGameEvent $event): void
    {
        AddPlayerScoreJob::dispatch($event->player);
    }
}
