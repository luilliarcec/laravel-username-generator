<?php

namespace Luilliarcec\LaravelUsernameGenerator\Tests\Units\Email;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Luilliarcec\LaravelUsernameGenerator\Exceptions\UsernameGeneratorException;
use Luilliarcec\LaravelUsernameGenerator\Facades\Username;
use Luilliarcec\LaravelUsernameGenerator\Support\UsernameGenerator;
use Luilliarcec\LaravelUsernameGenerator\Tests\Models\User;
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

        $app['config']->set('username-generator.driver', 'email');
    }

    /**
     * @test
     * @throws UsernameGeneratorException
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
     * @throws UsernameGeneratorException
     */
    public function make_lower_username_with_similar_username_without_duplicate()
    {
        for ($i = 1; $i <= 5; $i++) {
            User::create([
                'username' => "larcec{$i}"
            ]);
        }

        User::create([
            'username' => "larceca"
        ]);

        $username = $this->usernameGenerator->make('larcec@test.com');
        $this->assertEquals('larcec', $username);

        $username = Username::make('larcec@test.com');
        $this->assertEquals('larcec', $username);
    }

    /**
     * @test
     * @throws UsernameGeneratorException
     */
    public function make_lower_username_with_similars_username_and_duplicate()
    {
        for ($i = 1; $i <= 5; $i++) {
            User::create([
                'username' => "larcec{$i}"
            ]);
        }

        User::create([
            'username' => "larcec"
        ]);

        $username = $this->usernameGenerator->make('larcec@test.com');
        $this->assertEquals('larcec6', $username);

        $username = Username::make('larcec@test.com');
        $this->assertEquals('larcec6', $username);
    }
}
