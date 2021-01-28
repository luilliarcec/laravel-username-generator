<?php

namespace Luilliarcec\LaravelUsernameGenerator\Tests\Units;

use Luilliarcec\LaravelUsernameGenerator\Facades\Username;
use Luilliarcec\LaravelUsernameGenerator\Tests\Models\User;
use Luilliarcec\LaravelUsernameGenerator\Tests\TestCase;

class UsernameThreeNameTest extends TestCase
{
    /** @test */
    function make_username()
    {
        $this->assertEquals(
            'larce',
            Username::make('Luis Andrés', 'Arce')
        );

        $this->assertEquals(
            'larcec',
            Username::make('Luis', 'Arce Cárdenas')
        );

        $this->assertEquals(
            'larcec',
            Username::make('Luis Arce Cárdenas')
        );
    }

    /** @test */
    function make_username_with_similar_username_without_duplicate()
    {
        User::create(['username' => 'larcea']);

        User::create(['username' => 'larceca']);

        for ($i = 1; $i <= 2; $i++) {
            User::create([
                'username' => "larce{$i}"
            ]);

            User::create([
                'username' => "larcec{$i}"
            ]);
        }

        $this->assertEquals(
            'larce',
            Username::make('Luis Andrés', 'Arce')
        );

        $this->assertEquals(
            'larcec',
            Username::make('Luis', 'Arce Cárdenas')
        );
    }

    /** @test */
    function make_username_with_similars_username_and_duplicate()
    {
        User::create(['username' => 'larce']);

        User::create(['username' => 'larcec']);

        for ($i = 1; $i <= 2; $i++) {
            User::create([
                'username' => "larce{$i}"
            ]);

            User::create([
                'username' => "larcec{$i}"
            ]);
        }

        $this->assertEquals(
            'larce3',
            Username::make('Luis Andrés', 'Arce')
        );

        $this->assertEquals(
            'larcec3',
            Username::make('Luis', 'Arce Cárdenas')
        );
    }
}
