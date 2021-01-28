<?php

namespace Luilliarcec\LaravelUsernameGenerator\Tests\Units;

use Luilliarcec\LaravelUsernameGenerator\Facades\Username;
use Luilliarcec\LaravelUsernameGenerator\Tests\TestCase;

class UsernameManyNameTest extends TestCase
{
    /** @test */
    function the_username_is_generated_with_the_full_name_and_lastname_ignoring_the_surplus()
    {
        $this->assertEquals(
            'larcec',
            Username::make('Luis Andrés Arce Cárdenas', 'Other Lastname')
        );
    }

    /** @test */
    function the_username_is_generated_with_many_arguments()
    {
        $this->assertEquals(
            'larcec',
            Username::make('Luis Andrés', 'Arce Cárdenas Maple')
        );

        $this->assertEquals(
            'larcec',
            Username::make('Luis', 'Arce Cárdenas Maple')
        );

        $this->assertEquals(
            'larcec',
            Username::make('Luis Andrés Arce Cárdenas Maple')
        );
    }

    /** @test */
    function the_username_is_generated_with_the_three_words_in_name_and_lastname_ignoring_the_surplus()
    {
        $this->assertEquals(
            'larcec',
            Username::make('Luis Arce Cárdenas', 'Maple')
        );
    }
}
