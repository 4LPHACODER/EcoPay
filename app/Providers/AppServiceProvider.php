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
        // Force HTTPS in production OR when behind Railway proxy (APP_ENV not local)
        // Check for Railway's environment indicator or any non-local environment
        $isProductionLike = $this->app->environment('production') ||
                            $this->app->environment() !== 'local';

        if ($isProductionLike) {
            URL::forceScheme('https');

            // Force HTTPS for Vite assets via ASSET_URL config
            $assetUrl = config('app.asset_url');
            if ($assetUrl) {
                // Ensure asset URL uses HTTPS
                if (! str_starts_with($assetUrl, 'https://')) {
                    $assetUrl = 'https://'.ltrim($assetUrl, 'https://');
                    config(['app.asset_url' => $assetUrl]);
                }
            } else {
                // If no ASSET_URL is set, default to using the app URL with https
                $appUrl = config('app.url');
                if ($appUrl && str_starts_with($appUrl, 'http://')) {
                    config(['app.asset_url' => str_replace('http://', 'https://', $appUrl)]);
                }
            }
        }

        Vite::prefetch(concurrency: 3);
    }
}
