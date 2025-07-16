<?php

namespace App\Providers;

use App\Services\ConnectionService;
use App\Services\IConnectionService;
use App\Services\IMasterService;
use App\Services\MasterService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(IMasterService::class, MasterService::class);
        $this->app->bind(IConnectionService::class, ConnectionService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
