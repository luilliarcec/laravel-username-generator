<?php

namespace Luilliarcec\LaravelUsernameGenerator\Tests\Units;

use Luilliarcec\LaravelUsernameGenerator\Facades\Username;
use Luilliarcec\LaravelUsernameGenerator\Support\Drivers\Email;
use Luilliarcec\LaravelUsernameGenerator\Support\Drivers\Name;
use Luilliarcec\LaravelUsernameGenerator\Tests\TestCase;

class UsernameDriverTest extends TestCase
{
    /** @test */
    public function make_username_by_email()
    {
        $this->assertEquals(
            'larcec',
            Username::setDriver('email')
                ->make('larcec@test.com')
        );
    }

    /** @test */
    public function make_username_by_name()
    {
        $this->assertEquals(
            'larcec',
            Username::setDriver('name')
                ->make('Luis Andrés Arce Cárdenas')
        );
    }

    /** @test */
    public function make_username_by_email_class_instance()
    {
        $this->assertEquals(
            'larcec',
            Username::setDriver(new Email())
                ->make('larcec@test.com')
        );
    }

    /** @test */
    public function make_username_by_email_class_name()
    {
        $this->assertEquals(
            'larcec',
            Username::setDriver(Name::class)
                ->make('Luis Andrés Arce Cárdenas')
        );
    }
}
