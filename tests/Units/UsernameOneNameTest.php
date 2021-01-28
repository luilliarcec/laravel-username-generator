<?php

namespace Luilliarcec\LaravelUsernameGenerator\Tests\Units;

use Luilliarcec\LaravelUsernameGenerator\Facades\Username;
use Luilliarcec\LaravelUsernameGenerator\Tests\Models\User;
use Luilliarcec\LaravelUsernameGenerator\Tests\TestCase;

class UsernameOneNameTest extends TestCase
{
    /** @test */
    function make_username()
    {
        $this->assertEquals(
            'luis',
            Username::make('Luis')
        );
    }

    /** @test */
    function make_username_with_similar_username_without_duplicate()
    {
        for ($i = 1; $i <= 5; $i++) {
            User::create([
                'username' => "luis{$i}"
            ]);
        }

        User::create([
            'username' => "luisamaria"
        ]);

        $this->assertEquals(
            'luis',
            Username::make('Luis')
        );
    }

    /** @test */
    function make_username_with_similars_username_and_duplicate()
    {
        for ($i = 1; $i <= 6; $i++) {
            User::create([
                'username' => "luis{$i}"
            ]);
        }

        User::create([
            'username' => "luis"
        ]);

        $this->assertEquals(
            'luis7',
            Username::make('Luis')
        );
    }
}
