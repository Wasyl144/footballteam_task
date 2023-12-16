<?php

namespace App\Providers;

use App\Models\User;
use App\Observers\UserObserver;
use App\Services\DeckCard\Draw\DeckCardDrawService;
use App\Services\DeckCard\Draw\DeckCardDrawServiceInterface;
use App\Services\Duels\DuelsService;
use App\Services\Duels\DuelsServiceInterface;
use App\Services\Game\GameService;
use App\Services\Game\GameServiceInterface;
use App\Services\Game\Opponent\GameOpponentService;
use App\Services\Game\Opponent\GameOpponentServiceInterface;
use App\Services\User\Data\UserDataService;
use App\Services\User\Data\UserDataServiceInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserDataServiceInterface::class, UserDataService::class);
        $this->app->bind(DeckCardDrawServiceInterface::class, DeckCardDrawService::class);
        $this->app->bind(GameServiceInterface::class, GameService::class);
        $this->app->bind(GameOpponentServiceInterface::class, GameOpponentService::class);
        $this->app->bind(DuelsServiceInterface::class, DuelsService::class);
        $this->app->when(GameService::class)->needs('$maxRounds')->giveConfig('game.rounds.max_rounds');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        User::observe(UserObserver::class);
    }
}
