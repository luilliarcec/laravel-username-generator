<?php

namespace Luilliarcec\LaravelUsernameGenerator\Tests\Units;

use Luilliarcec\LaravelUsernameGenerator\Facades\Username;
use Luilliarcec\LaravelUsernameGenerator\Tests\Models\Customer;
use Luilliarcec\LaravelUsernameGenerator\Tests\Models\User;
use Luilliarcec\LaravelUsernameGenerator\Tests\TestCase;

class UsernameModelTest extends TestCase
{
    /** @test */
    function make_username_in_different_model()
    {
        User::create([
            'username' => 'larcec'
        ]);

        $this->assertEquals(
            'larcec1',
            Username::setModel(User::class)
                ->make('Luis Andrés Arce Cárdenas')
        );

        $this->assertEquals(
            'larcec',
            Username::setModel(Customer::class)
                ->make('Luis Andrés Arce Cárdenas')
        );
    }
}
