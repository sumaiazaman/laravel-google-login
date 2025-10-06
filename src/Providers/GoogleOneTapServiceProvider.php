<?php

namespace Sumaia\GoogleOneTapLogin\Providers;

use Illuminate\Support\ServiceProvider;

class GoogleOneTapServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Merge package configuration with app configuration
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/google-onetap.php',
            'google-onetap'
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Load package routes
        $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');

        // Load package views
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'google-onetap');

        // Load package migrations
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        // Publish configuration file
        $this->publishes([
            __DIR__ . '/../../config/google-onetap.php' => config_path('google-onetap.php'),
        ], 'google-onetap-config');

        // Publish views
        $this->publishes([
            __DIR__ . '/../../resources/views' => resource_path('views/vendor/google-onetap'),
        ], 'google-onetap-views');

        // Publish migrations
        $this->publishes([
            __DIR__ . '/../../database/migrations' => database_path('migrations'),
        ], 'google-onetap-migrations');

        // Publish assets (if any)
        $this->publishes([
            __DIR__ . '/../../resources/assets' => public_path('vendor/google-onetap'),
        ], 'google-onetap-assets');

        // Register commands if running in console
        if ($this->app->runningInConsole()) {
            $this->commands([
                // Add any package commands here
            ]);
        }
    }
}
