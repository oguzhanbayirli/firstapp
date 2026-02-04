<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\CacheService;
use App\Services\FollowService;
use App\Services\PostService;
use App\Services\UserService;

class ServiceLayerProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Register service classes as singletons
        $this->app->singleton(CacheService::class);
        $this->app->singleton(FollowService::class);
        $this->app->singleton(PostService::class);
        $this->app->singleton(UserService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
