<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Force HTTPS in production behind Railway proxy
        if ($this->app->environment('production')) {
            URL::forceScheme('https');

            // Force HTTPS for Vite assets
            $assetUrl = config('app.asset_url');
            if ($assetUrl && ! str_starts_with($assetUrl, 'https://')) {
                config(['app.asset_url' => 'https://'.ltrim($assetUrl, 'https://')]);
            }
        }

        Vite::prefetch(concurrency: 3);
    }
}
