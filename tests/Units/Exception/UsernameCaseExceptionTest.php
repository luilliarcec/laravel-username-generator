<?php

namespace Luilliarcec\LaravelUsernameGenerator\Tests\Units\Exception;

use Luilliarcec\LaravelUsernameGenerator\Exceptions\UsernameGeneratorException;
use Luilliarcec\LaravelUsernameGenerator\Facades\Username;
use Luilliarcec\LaravelUsernameGenerator\Tests\TestCase;

class UsernameCaseExceptionTest extends TestCase
{
    /** @test */
    public function an_exception_is_received_when_the_case_does_not_exist()
    {
        $this->expectException(UsernameGeneratorException::class);
        $this->expectExceptionMessage('Case type not supported [foo]: Method Illuminate\Support\Str::foo does not exist.');

        Username::setCase('foo')->make('Luis');
    }
}
