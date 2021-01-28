<?php

namespace Luilliarcec\LaravelUsernameGenerator\Tests\Units;

use Luilliarcec\LaravelUsernameGenerator\Facades\Username;
use Luilliarcec\LaravelUsernameGenerator\Tests\Models\Customer;
use Luilliarcec\LaravelUsernameGenerator\Tests\Models\User;
use Luilliarcec\LaravelUsernameGenerator\Tests\TestCase;

class UsernameFullNameTest extends TestCase
{
    /** @test */
    function make_username_with_inline_configuration()
    {
        $this->assertEquals(
            'larcec',
            Username::setModel(Customer::class, 'username')
                ->setDriver('name')
                ->setCase('lower')
                ->make('Luis Andrés Arce Cárdenas')
        );

        $this->assertEquals(
            'larcec',
            Username::setModel(Customer::class, 'username')
                ->setDriver('name')
                ->setCase('lower')
                ->make('Luis Andrés', 'Arce Cárdenas')
        );
    }

    /** @test */
    function make_username_with_default_configuration()
    {
        $this->assertEquals(
            'larcec',
            Username::make('Luis Andrés Arce Cárdenas')
        );

        $this->assertEquals(
            'larcec',
            Username::make('Luis Andrés', 'Arce Cárdenas')
        );
    }

    /** @test */
    function make_username_with_similar_username_without_duplicate()
    {
        for ($i = 1; $i <= 2; $i++) {
            User::create([
                'username' => "larcec{$i}"
            ]);
        }

        User::create([
            'username' => 'larceca'
        ]);

        $this->assertEquals(
            'larcec',
            Username::make('Luis Andrés', 'Arce Cárdenas')
        );

        $this->assertEquals(
            'larcec',
            Username::make('Luis Andrés Arce Cárdenas')
        );
    }

    /** @test */
    function make_username_with_similars_username_and_duplicate()
    {
        for ($i = 1; $i <= 3; $i++) {
            User::create([
                'username' => "larcec{$i}"
            ]);
        }

        User::create([
            'username' => 'larcec'
        ]);

        $this->assertEquals(
            'larcec4',
            Username::make('Luis Andrés', 'Arce Cárdenas')
        );

        $this->assertEquals(
            'larcec4',
            Username::make('Luis Andrés Arce Cárdenas')
        );
    }
}
