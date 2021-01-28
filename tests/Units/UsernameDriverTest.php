<?php

namespace Luilliarcec\LaravelUsernameGenerator\Tests\Units;

use Luilliarcec\LaravelUsernameGenerator\Facades\Username;
use Luilliarcec\LaravelUsernameGenerator\Tests\TestCase;

class UsernameDriverTest extends TestCase
{
    /** @test */
    function make_username_by_email()
    {
        $this->assertEquals(
            'larcec',
            Username::setDriver('email')
                ->make('larcec@test.com')
        );
    }

    /** @test */
    function make_username_by_name()
    {
        $this->assertEquals(
            'larcec',
            Username::setDriver('name')
                ->make('Luis Andrés Arce Cárdenas')
        );
    }
}
