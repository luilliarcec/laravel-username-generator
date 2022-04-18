<?php

namespace Luilliarcec\LaravelUsernameGenerator\Tests\Units;

use Luilliarcec\LaravelUsernameGenerator\Facades\Username;
use Luilliarcec\LaravelUsernameGenerator\Tests\Models\User;
use Luilliarcec\LaravelUsernameGenerator\Tests\TestCase;

class UsernameTwoNameTest extends TestCase
{
    /** @test */
    public function make_username()
    {
        $this->assertEquals(
            'larce',
            Username::make('Luis Arce')
        );

        $this->assertEquals(
            'larce',
            Username::make('Luis', 'Arce')
        );
    }

    /** @test */
    public function make_username_with_similar_username_without_duplicate()
    {
        User::create([
            'username' => 'larcec',
        ]);

        for ($i = 1; $i <= 5; $i++) {
            User::create([
                'username' => "larce{$i}",
            ]);
        }

        $this->assertEquals(
            'larce',
            Username::make('Luis Arce')
        );

        $this->assertEquals(
            'larce',
            Username::make('Luis', 'Arce')
        );
    }

    /** @test */
    public function make_username_with_similars_username_and_duplicate()
    {
        User::create([
            'username' => 'larce',
        ]);

        for ($i = 1; $i <= 6; $i++) {
            User::create([
                'username' => "larce{$i}",
            ]);
        }

        $this->assertEquals(
            'larce7',
            Username::make('Luis Arce')
        );

        $this->assertEquals(
            'larce7',
            Username::make('Luis', 'Arce')
        );
    }
}
