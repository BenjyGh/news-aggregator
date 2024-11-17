<?php

namespace App\Providers;

use App\Service\APIs\GuardianSource;
use App\Service\APIs\NewsAPISource;
use App\Service\APIs\NYTimesSource;
use App\Service\NewsAggregatorService;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Initialize the NewsAggregatorService with our sources.
        $this->app->singleton(NewsAggregatorService::class, fn() => new NewsAggregatorService([
            new NYTimesSource(),
            new GuardianSource(),
            new NewsAPISource(),
        ]));
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        JsonResource::withoutWrapping();
    }
}
