<?php

namespace Luilliarcec\LaravelUsernameGenerator\Tests\Units\Exception;

use Luilliarcec\LaravelUsernameGenerator\Exceptions\UsernameGeneratorException;
use Luilliarcec\LaravelUsernameGenerator\Facades\Username;
use Luilliarcec\LaravelUsernameGenerator\Tests\TestCase;

class UsernameExceptionTest extends TestCase
{
    /** @test */
    function an_exception_is_received_when_the_name_is_empty_or_null()
    {
        $this->expectException(UsernameGeneratorException::class);
        $this->expectExceptionMessage('The name cannot be null');

        Username::make('');
        Username::make(null);
    }

    /** @test */
    function an_exception_is_received_when_the_email_is_invalid()
    {
        $this->expectException(UsernameGeneratorException::class);
        $this->expectExceptionMessage('The value provided does not have a valid email format.');

        Username::setDriver('email')->make('Luis Arce');
    }
}
