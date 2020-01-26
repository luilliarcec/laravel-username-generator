<?php


namespace Luilliarcec\LaravelUsernameGenerator\Tests;

use Luilliarcec\LaravelUsernameGenerator\UsernameGeneratorServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->withFactories(__DIR__ . '/../database/factories');
    }

    /**
     * Get package providers.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [UsernameGeneratorServiceProvider::class];
    }

    /**
     * Define environment setup.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        /** Config */
        $app['config']->set('laravel-username-generator.column', 'username');
        $app['config']->set('laravel-username-generator.case', 'lower');
        $app['config']->set('laravel-username-generator.driver', 'name');
        $app['config']->set('laravel-username-generator.model', '\Luilliarcec\LaravelUsernameGenerator\Models\User');

        /** Database */
        $app['config']->set('database.default', 'testdb');
        $app['config']->set('database.connections.testdb', [
            'driver' => 'sqlite',
            'database' => ':memory:'
        ]);
    }
}
