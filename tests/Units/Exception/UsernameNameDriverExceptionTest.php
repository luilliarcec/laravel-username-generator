<?php

namespace Luilliarcec\LaravelUsernameGenerator\Tests\Units\Exception;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Luilliarcec\LaravelUsernameGenerator\Exceptions\UsernameGeneratorException;
use Luilliarcec\LaravelUsernameGenerator\Facades\UsernameGeneratorFacade;
use Luilliarcec\LaravelUsernameGenerator\Models\User;
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
    public function get_error_with_invalid_email()
    {
        $this->expectException(UsernameGeneratorException::class);
        $this->expectExceptionMessage('Use the email driver, to generate a username from the email.');

        $this->usernameGenerator->make('larcec@test.com');
        UsernameGeneratorFacade::make('larcec@test.com');
    }

    /**
     * @test
     * @throws \Luilliarcec\LaravelUsernameGenerator\Exceptions\UsernameGeneratorException
     */
    public function get_error_with_the_value_provided_exceeds_four_words()
    {
        $this->expectException(UsernameGeneratorException::class);
        $this->expectExceptionMessage('Too many values have been provided to the username generator,
                    the candidate words provided are: 5, the number of words supported is: 4.');

        $this->usernameGenerator->make('Luis Andrés Arce Cárdenas Maple');
        UsernameGeneratorFacade::make('Luis Andrés Arce Cárdenas Maple');
    }
}
