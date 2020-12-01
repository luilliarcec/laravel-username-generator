<?php

namespace Luilliarcec\LaravelUsernameGenerator\Tests\Units\Exception;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Luilliarcec\LaravelUsernameGenerator\Exceptions\UsernameGeneratorException;
use Luilliarcec\LaravelUsernameGenerator\Facades\Username;
use Luilliarcec\LaravelUsernameGenerator\Support\UsernameGenerator;
use Luilliarcec\LaravelUsernameGenerator\Tests\TestCase;

class UsernameEmailDriverExceptionTest extends TestCase
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

        $app['config']->set('laravel-username-generator.driver', 'email');
    }

    /**
     * @test
     * @throws UsernameGeneratorException
     */
    public function get_error_with_invalid_email()
    {
        $this->expectException(UsernameGeneratorException::class);
        $this->expectExceptionMessage('The value provided does not have a valid email format.');

        $this->usernameGenerator->make('Luis Arce');
        Username::make('Luis Arce');
    }
}
