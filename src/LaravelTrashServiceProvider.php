<?php

namespace Ajaycalicut17\LaravelTrash;

use Ajaycalicut17\LaravelTrash\Providers\EventServiceProvider;
use Illuminate\Support\ServiceProvider;

class LaravelTrashServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/trash.php' => config_path('trash.php'),
            ], 'config');
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/trash.php', 'trash');

        // Register the services provider
        $this->app->register(EventServiceProvider::class);
    }
}
