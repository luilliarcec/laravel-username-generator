<?php

namespace Luilliarcec\LaravelUsernameGenerator\Tests\Units\Exception;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Luilliarcec\LaravelUsernameGenerator\Exceptions\UsernameGeneratorException;
use Luilliarcec\LaravelUsernameGenerator\Facades\Username;
use Luilliarcec\LaravelUsernameGenerator\Support\UsernameGenerator;
use Luilliarcec\LaravelUsernameGenerator\Tests\TestCase;

class UsernameModelExceptionTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * @var UsernameGenerator
     */
    protected $usernameGenerator;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->usernameGenerator = new UsernameGenerator();
    }

    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $app['config']->set('laravel-username-generator.model', '\Luilliarcec\LaravelUsernameGenerator\User');
    }

    /**
     * @test
     * @throws UsernameGeneratorException
     */
    public function make_lower_username()
    {
        $this->expectException(UsernameGeneratorException::class);
        $this->expectExceptionMessage(
            "Unable to instantiate the model [\Luilliarcec\LaravelUsernameGenerator\User]: Class '\Luilliarcec\LaravelUsernameGenerator\User' not found"
        );

        $username = $this->usernameGenerator->make('Luis');
        $this->assertEquals('luis', $username);

        $username = Username::make('Luis');
        $this->assertEquals('luis', $username);
    }
}
