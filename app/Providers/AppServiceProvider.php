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
        // Force HTTPS in production (not local) for asset URLs behind Railway proxy
        if (! $this->app->isLocal()) {
            URL::forceScheme('https');

            // Configure ASSET_URL for Vite if not set
            $assetUrl = config('app.asset_url');
            if (empty($assetUrl)) {
                $appUrl = config('app.url');
                if ($appUrl) {
                    // Use app URL with https scheme as asset URL
                    $httpsUrl = str_replace('http://', 'https://', $appUrl);
                    config(['app.asset_url' => $httpsUrl]);
                }
            } elseif (! str_starts_with($assetUrl, 'https://')) {
                // Ensure existing ASSET_URL uses https
                config(['app.asset_url' => 'https://'.ltrim($assetUrl, 'https://')]);
            }
        }

        Vite::prefetch(concurrency: 3);
    }
}
