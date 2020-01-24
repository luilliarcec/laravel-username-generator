<?php

namespace Luilliarcec\LaravelUsernameGenerator\Facades;

use Illuminate\Support\Facades\Facade;
use Luilliarcec\LaravelUsernameGenerator\Support\LaravelUsernameGenerator;

/**
 * @see LaravelUsernameGenerator
 */
class LaravelUsernameGeneratorFacade extends Facade
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
