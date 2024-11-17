<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class RateLimitServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        RateLimiter::for(
            'api',
            fn () => Limit::perMinute(60)
        );

        RateLimiter::for(
            'auth',
            fn () => Limit::perMinute(5)
        );
    }
}
