<?php

namespace Luilliarcec\LaravelUsernameGenerator\Tests\Units;

use Luilliarcec\LaravelUsernameGenerator\Facades\Username;
use Luilliarcec\LaravelUsernameGenerator\Tests\Models\Customer;
use Luilliarcec\LaravelUsernameGenerator\Tests\TestCase;

class UsernameColumnTest extends TestCase
{
    /** @test */
    function make_username_with_different_column()
    {
        Customer::create([
            'name' => 'larcec',
            'username' => 'Luis Andrés',
        ]);

        $this->assertEquals(
            'larcec1',
            Username::setModel(Customer::class)
                ->setColum('name')
                ->make('Luis Andrés Arce Cárdenas')
        );
    }
}
