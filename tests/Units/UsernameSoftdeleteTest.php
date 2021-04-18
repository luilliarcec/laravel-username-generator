<?php

namespace Luilliarcec\LaravelUsernameGenerator\Tests\Units;

use Luilliarcec\LaravelUsernameGenerator\Facades\Username;
use Luilliarcec\LaravelUsernameGenerator\Tests\Models\Customer;
use Luilliarcec\LaravelUsernameGenerator\Tests\Models\User;
use Luilliarcec\LaravelUsernameGenerator\Tests\TestCase;

class UsernameSoftdeleteTest extends TestCase
{
    /** @test */
    function make_username_with_trashed()
    {
        $user = User::create([
            'username' => 'larcec'
        ]);

        $user->delete();

        $this->assertEquals(
            'larcec1',
            Username::withTrashed()
                ->make('Luis Andrés', 'Arce Cárdenas')
        );

        $this->assertEquals(
            'larcec1',
            Username::withTrashed()
                ->make('Luis Andrés Arce Cárdenas')
        );
    }

    /** @test */
    function make_username_without_trashed()
    {
        $user = User::create([
            'username' => 'larcec'
        ]);

        $user->delete();

        $this->assertEquals(
            'larcec',
            Username::withoutTrashed()
                ->make('Luis Andrés', 'Arce Cárdenas')
        );

        $this->assertEquals(
            'larcec',
            Username::withoutTrashed()
                ->make('Luis Andrés Arce Cárdenas')
        );
    }
}
