<?php

namespace Luilliarcec\LaravelUsernameGenerator\Tests;

use Orchestra\Testbench\TestCase;
use Luilliarcec\LaravelUsernameGenerator\LaravelUsernameGeneratorServiceProvider;

class ExampleTest extends TestCase
{

    protected function getPackageProviders($app)
    {
        return [LaravelUsernameGeneratorServiceProvider::class];
    }
    
    /** @test */
    public function true_is_true()
    {
        $this->assertTrue(true);
    }
}
