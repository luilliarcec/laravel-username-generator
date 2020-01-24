<?php

namespace Luilliarcec\LaravelUsernameGenerator\Tests\Units;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Luilliarcec\LaravelUsernameGenerator\Models\User;
use Luilliarcec\LaravelUsernameGenerator\Tests\TestCase;

class UsernameTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function make_username_with_complete_name()
    {
        $user = factory(User::class)->create();
        dd($user);
    }


    /** @test */
    public function true_is_true()
    {
//        $this->app->get('config')['laravel-username-generator.model'];

        $user = factory(User::class)->create();

        User::query()->where('usermane', 'larcec')->get();

        $this->assertEquals('username', $this->app->get('config')['laravel-username-generator.column']);
    }
}
