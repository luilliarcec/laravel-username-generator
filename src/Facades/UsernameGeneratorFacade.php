<?php

namespace Luilliarcec\LaravelUsernameGenerator\Facades;

use Illuminate\Support\Facades\Facade;
use Luilliarcec\LaravelUsernameGenerator\Exceptions\UsernameGeneratorException;
use Luilliarcec\LaravelUsernameGenerator\Support\UsernameGenerator;

/**
 * @method static string|UsernameGeneratorException make(string $name, string $lastname = null)
 * @see UsernameGenerator
 */
class UsernameGeneratorFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'UsernameGenerator';
    }
}
