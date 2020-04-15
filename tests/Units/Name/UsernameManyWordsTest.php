<?php

namespace Luilliarcec\LaravelUsernameGenerator\Tests\Units\Name;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Luilliarcec\LaravelUsernameGenerator\Support\UsernameGenerator;
use Luilliarcec\LaravelUsernameGenerator\Tests\TestCase;

class UsernameManyWordsTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * @var UsernameGenerator
     */
    protected $usernameGenerator;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->usernameGenerator = new UsernameGenerator();
    }

    /**
     * @test
     * @throws \Luilliarcec\LaravelUsernameGenerator\Exceptions\UsernameGeneratorException
     */
    public function check_that_the_username_is_generated_with_the_full_name_and_lastname_of_more()
    {
        $username = $this->usernameGenerator->make('Luis Andrés Arce Cárdenas', 'Other Lastname');
        $this->assertEquals('larcec', $username);
    }

    /**
     * @test
     * @throws \Luilliarcec\LaravelUsernameGenerator\Exceptions\UsernameGeneratorException
     */
    public function check_that_the_username_is_generated_with_many_arguments()
    {
        $username = $this->usernameGenerator->make('Luis Andrés', 'Arce Cárdenas Maple');
        $this->assertEquals('larcec', $username);

        $username = $this->usernameGenerator->make('Luis', 'Arce Cárdenas Maple');
        $this->assertEquals('larcec', $username);

        $username = $this->usernameGenerator->make('Luis Andrés Arce Cárdenas Maple');
        $this->assertEquals('larcec', $username);
    }

    /**
     * @test
     * @throws \Luilliarcec\LaravelUsernameGenerator\Exceptions\UsernameGeneratorException
     */
    public function check_that_the_username_is_generated_with_the_three_words_in_name_and_lastname_of_more()
    {
        $username = $this->usernameGenerator->make('Luis Arce Cárdenas', 'Maple');
        $this->assertEquals('larcec', $username);
    }
}
