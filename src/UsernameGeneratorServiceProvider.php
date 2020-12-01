<?php

namespace Luilliarcec\LaravelUsernameGenerator;

use Illuminate\Support\ServiceProvider;
use Luilliarcec\LaravelUsernameGenerator\Support\UsernameGenerator;

class UsernameGeneratorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('username-generator.php'),
            ], 'config');
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'username-generator');

        // Register the main class to use with the facade
        $this->app->singleton('UsernameGenerator', function () {
            return new UsernameGenerator;
        });
    }
}
