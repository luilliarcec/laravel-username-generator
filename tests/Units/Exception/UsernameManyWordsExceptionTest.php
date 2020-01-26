<?php

namespace Luilliarcec\LaravelUsernameGenerator\Tests\Units\Exception;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Luilliarcec\LaravelUsernameGenerator\Exceptions\UsernameGeneratorException;
use Luilliarcec\LaravelUsernameGenerator\Facades\UsernameGeneratorFacade;
use Luilliarcec\LaravelUsernameGenerator\Models\User;
use Luilliarcec\LaravelUsernameGenerator\Support\UsernameGenerator;
use Luilliarcec\LaravelUsernameGenerator\Tests\TestCase;

class UsernameManyWordsExceptionTest extends TestCase
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

    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);
    }

    /**
     * @test
     * @throws \Luilliarcec\LaravelUsernameGenerator\Exceptions\UsernameGeneratorException
     */
    public function name_is_null()
    {
        $this->expectException(UsernameGeneratorException::class);
        $this->expectExceptionMessage(
            "The name cannot be null"
        );

        $this->usernameGenerator->make(null);
    }

    /**
     * @test
     * @throws \Luilliarcec\LaravelUsernameGenerator\Exceptions\UsernameGeneratorException
     */
    public function the_lastname_parameter_is_not_supported_when_the_name_has_four_words()
    {
        $this->expectException(UsernameGeneratorException::class);
        $this->expectExceptionMessage(
            "The lastname parameter is not supported when the name has fullname (four words)"
        );

        $this->usernameGenerator->make('Luis Andrés Arce Cárdenas', 'Más');
    }

    /**
     * @test
     * @throws \Luilliarcec\LaravelUsernameGenerator\Exceptions\UsernameGeneratorException
     */
    public function only_one_or_two_lastname_are_supported()
    {
        $this->expectException(UsernameGeneratorException::class);
        $this->expectExceptionMessage(
            "Only one or two lastname are supported"
        );

        $this->usernameGenerator->make('Luis Andrés', 'Arce Cárdenas Maple');
        $this->usernameGenerator->make('Luis', 'Arce Cárdenas Maple');
    }

    /**
     * @test
     * @throws \Luilliarcec\LaravelUsernameGenerator\Exceptions\UsernameGeneratorException
     */
    public function the_lastname_parameter_is_not_supported_when_the_name_has_three_words()
    {
        $this->expectException(UsernameGeneratorException::class);
        $this->expectExceptionMessage(
            "The lastname parameter is not supported when the name has three words"
        );

        $this->usernameGenerator->make('Luis Arce Cárdenas', 'Maple');
    }
}
