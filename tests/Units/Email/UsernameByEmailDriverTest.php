<?php

namespace Luilliarcec\LaravelUsernameGenerator\Tests\Units\Email;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Luilliarcec\LaravelUsernameGenerator\Exceptions\UsernameGeneratorException;
use Luilliarcec\LaravelUsernameGenerator\Facades\Username;
use Luilliarcec\LaravelUsernameGenerator\Models\User;
use Luilliarcec\LaravelUsernameGenerator\Support\UsernameGenerator;
use Luilliarcec\LaravelUsernameGenerator\Tests\TestCase;

class UsernameByEmailDriverTest extends TestCase
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

    /**
     * Define environment setup.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $app['config']->set('laravel-username-generator.driver', 'email');
    }

    /**
     * @test
     * @throws \Luilliarcec\LaravelUsernameGenerator\Exceptions\UsernameGeneratorException
     */
    public function make_lower_username()
    {
        $username = $this->usernameGenerator->make('larcec@test.com');
        $this->assertEquals('larcec', $username);

        $username = Username::make('larcec@test.com');
        $this->assertEquals('larcec', $username);
    }

    /**
     * @test
     * @throws \Luilliarcec\LaravelUsernameGenerator\Exceptions\UsernameGeneratorException
     */
    public function make_lower_username_with_similar_username_without_duplicate()
    {
        for ($i = 1; $i <= 5; $i++) {
            factory(User::class)->create([
                'username' => "larcec{$i}"
            ]);
        }

        factory(User::class)->create([
            'username' => "larceca"
        ]);

        $username = $this->usernameGenerator->make('larcec@test.com');
        $this->assertEquals('larcec', $username);

        $username = Username::make('larcec@test.com');
        $this->assertEquals('larcec', $username);
    }

    /**
     * @test
     * @throws \Luilliarcec\LaravelUsernameGenerator\Exceptions\UsernameGeneratorException
     */
    public function make_lower_username_with_similars_username_and_duplicate()
    {
        for ($i = 1; $i <= 5; $i++) {
            factory(User::class)->create([
                'username' => "larcec{$i}"
            ]);
        }

        factory(User::class)->create([
            'username' => "larcec"
        ]);

        $username = $this->usernameGenerator->make('larcec@test.com');
        $this->assertEquals('larcec6', $username);

        $username = Username::make('larcec@test.com');
        $this->assertEquals('larcec6', $username);
    }
}
