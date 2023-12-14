<?php

namespace App\Providers;

use App\Models\User;
use App\Observers\UserObserver;
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
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        User::observe(UserObserver::class);
    }
}
