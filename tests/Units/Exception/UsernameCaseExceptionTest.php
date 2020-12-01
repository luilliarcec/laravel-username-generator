<?php

namespace Luilliarcec\LaravelUsernameGenerator\Tests\Units\Exception;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Luilliarcec\LaravelUsernameGenerator\Exceptions\UsernameGeneratorException;
use Luilliarcec\LaravelUsernameGenerator\Facades\Username;
use Luilliarcec\LaravelUsernameGenerator\Support\UsernameGenerator;
use Luilliarcec\LaravelUsernameGenerator\Tests\TestCase;

class UsernameCaseExceptionTest extends TestCase
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

        $app['config']->set('username-generator.case', 'foo');
    }

    /**
     * @test
     * @throws UsernameGeneratorException
     */
    public function make_lower_username()
    {
        $this->expectException(UsernameGeneratorException::class);
        $this->expectExceptionMessage('Case type not supported [foo]: Method Illuminate\Support\Str::foo does not exist.');

        $username = $this->usernameGenerator->make('Luis');
        $this->assertEquals('luis', $username);

        $username = Username::make('Luis');
        $this->assertEquals('luis', $username);
    }
}
