<?php

namespace Luilliarcec\LaravelUsernameGenerator\Tests\Units\Exception;

use Luilliarcec\LaravelUsernameGenerator\Exceptions\UsernameGeneratorException;
use Luilliarcec\LaravelUsernameGenerator\Facades\Username;
use Luilliarcec\LaravelUsernameGenerator\Tests\TestCase;

class UsernameDriverExceptionTest extends TestCase
{
    /** @test */
    function an_exception_is_received_when_the_drive_does_not_exist()
    {
        $this->expectException(UsernameGeneratorException::class);
        $this->expectExceptionMessage('Class [random] not found');

        Username::setDriver('random')->make('Luis');
    }

    /** @test */
    function an_exception_is_received_when_wrong_driver_is_used()
    {
        $this->expectException(UsernameGeneratorException::class);
        $this->expectExceptionMessage('Use the email driver, to generate a username from the email.');

        Username::setDriver('name')->make('larcec@test.com');
    }
}
