<?php

namespace App\Providers;

use App\Service\Spotify\SpotifyRequest;
use App\Service\Spotify\SpotifyService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind('spotify', function () {
            return new SpotifyRequest();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        
    }
}
