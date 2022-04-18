<?php

namespace Luilliarcec\LaravelUsernameGenerator\Tests\Units;

use Luilliarcec\LaravelUsernameGenerator\Exceptions\UsernameGeneratorException;
use Luilliarcec\LaravelUsernameGenerator\Facades\Username;
use Luilliarcec\LaravelUsernameGenerator\Tests\Models\User;
use Luilliarcec\LaravelUsernameGenerator\Tests\TestCase;

class UsernameWithDefaultValuesTest extends TestCase
{
    protected $withInitialConfigUsername = false;

    /** @test */
    public function an_exception_is_received_when_the_default_model_does_not_exist()
    {
        $this->expectException(UsernameGeneratorException::class);
        $this->expectExceptionMessage(
            "Unable to instantiate the model [App\Models\User]: Class 'App\Models\User' not found"
        );

        Username::make('Luis');
    }

    /** @test */
    public function make_username()
    {
        $this->assertEquals(
            'larcec',
            Username::setModel(User::class)->make('Luis Andrés Arce Cárdenas')
        );

        $this->assertEquals(
            'larcec',
            Username::setModel(User::class)->make('Luis Andrés', 'Arce Cárdenas')
        );
    }
}
