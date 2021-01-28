<?php

namespace Luilliarcec\LaravelUsernameGenerator\Tests\Units\Exception;

use Luilliarcec\LaravelUsernameGenerator\Exceptions\UsernameGeneratorException;
use Luilliarcec\LaravelUsernameGenerator\Facades\Username;
use Luilliarcec\LaravelUsernameGenerator\Tests\TestCase;

class UsernameModelExceptionTest extends TestCase
{
    /** @test */
    function an_exception_is_received_when_the_model_does_not_exist()
    {
        $this->expectException(UsernameGeneratorException::class);
        $this->expectExceptionMessage(
            "Unable to instantiate the model [App\Foo]: Class 'App\Foo' not found"
        );

        Username::setModel('App\Foo')->make('Luis');
    }

    /** @test */
    function an_exception_is_received_when_the_model_does_not_instance_of_eloquent_model()
    {
        $this->expectException(UsernameGeneratorException::class);
        $this->expectExceptionMessage(
            "[Luilliarcec\LaravelUsernameGenerator\Tests\Units\Exception\FooBar] is not an instance of Illuminate\Database\Eloquent\Model"
        );

        Username::setModel(FooBar::class)
            ->make('Luis');
    }
}

class FooBar
{

}
