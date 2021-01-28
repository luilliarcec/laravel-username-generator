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
        //
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->app->singleton('UsernameGenerator', function () {
            return new UsernameGenerator;
        });
    }
}
