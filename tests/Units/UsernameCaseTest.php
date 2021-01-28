<?php

namespace Luilliarcec\LaravelUsernameGenerator\Tests\Units;

use Luilliarcec\LaravelUsernameGenerator\Facades\Username;
use Luilliarcec\LaravelUsernameGenerator\Tests\TestCase;

class UsernameCaseTest extends TestCase
{
    /** @test */
    function make_username_lowercase()
    {
        $this->assertEquals(
            'larcec',
            Username::setCase('lower')
                ->make('Luis Andrés Arce Cárdenas')
        );
    }

    /** @test */
    function make_username_uppercase()
    {
        $this->assertEquals(
            'LARCEC',
            Username::setCase('upper')
                ->make('Luis Andrés Arce Cárdenas')
        );
    }
}
