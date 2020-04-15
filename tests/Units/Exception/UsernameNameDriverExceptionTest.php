<?php

namespace Luilliarcec\LaravelUsernameGenerator\Tests\Units\Exception;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Luilliarcec\LaravelUsernameGenerator\Exceptions\UsernameGeneratorException;
use Luilliarcec\LaravelUsernameGenerator\Facades\Username;
use Luilliarcec\LaravelUsernameGenerator\Support\UsernameGenerator;
use Luilliarcec\LaravelUsernameGenerator\Tests\TestCase;

class UsernameNameDriverExceptionTest extends TestCase
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
        $app['config']->set('laravel-username-generator.driver', 'name');
    }

    /**
     * @test
     * @throws \Luilliarcec\LaravelUsernameGenerator\Exceptions\UsernameGeneratorException
     */
    public function check_that_the_name_is_not_null()
    {
        $this->expectException(UsernameGeneratorException::class);
        $this->expectExceptionMessage('The name cannot be null');

        $this->usernameGenerator->make('');
    }

    /**
     * @test
     * @throws \Luilliarcec\LaravelUsernameGenerator\Exceptions\UsernameGeneratorException
     */
    public function get_error_with_invalid_email()
    {
        $this->expectException(UsernameGeneratorException::class);
        $this->expectExceptionMessage('Use the email driver, to generate a username from the email.');

        $this->usernameGenerator->make('larcec@test.com');
        Username::make('larcec@test.com');
    }
}
