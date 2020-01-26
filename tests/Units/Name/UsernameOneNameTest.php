<?php

namespace Luilliarcec\LaravelUsernameGenerator\Tests\Units\Name;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Luilliarcec\LaravelUsernameGenerator\Exceptions\UsernameGeneratorException;
use Luilliarcec\LaravelUsernameGenerator\Facades\UsernameGeneratorFacade;
use Luilliarcec\LaravelUsernameGenerator\Models\User;
use Luilliarcec\LaravelUsernameGenerator\Support\UsernameGenerator;
use Luilliarcec\LaravelUsernameGenerator\Tests\TestCase;

class UsernameOneNameTest extends TestCase
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
     * @test
     * @throws \Luilliarcec\LaravelUsernameGenerator\Exceptions\UsernameGeneratorException
     */
    public function make_lower_username()
    {
        $username = $this->usernameGenerator->make('Luis');
        $this->assertEquals('luis', $username);

        $username = UsernameGeneratorFacade::make('Luis');
        $this->assertEquals('luis', $username);
    }

    /**
     * @test
     * @throws \Luilliarcec\LaravelUsernameGenerator\Exceptions\UsernameGeneratorException
     */
    public function make_lower_username_with_similar_username_without_duplicate()
    {
        for ($i = 1; $i <= 5; $i++) {
            factory(User::class)->create([
                'username' => "luis{$i}"
            ]);
        }

        factory(User::class)->create([
            'username' => "luisamaria"
        ]);

        $username = $this->usernameGenerator->make('Luis');
        $this->assertEquals('luis', $username);

        $username = UsernameGeneratorFacade::make('Luis');
        $this->assertEquals('luis', $username);
    }

    /**
     * @test
     * @throws \Luilliarcec\LaravelUsernameGenerator\Exceptions\UsernameGeneratorException
     */
    public function make_lower_username_with_similars_username_and_duplicate()
    {
        for ($i = 1; $i <= 5; $i++) {
            factory(User::class)->create([
                'username' => "luis{$i}"
            ]);
        }

        factory(User::class)->create([
            'username' => "luis"
        ]);

        $username = $this->usernameGenerator->make('Luis');
        $this->assertEquals('luis6', $username);

        $username = UsernameGeneratorFacade::make('Luis');
        $this->assertEquals('luis6', $username);
    }
}
