<?php

namespace Luilliarcec\LaravelUsernameGenerator;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Luilliarcec\LaravelUsernameGenerator\Skeleton\SkeletonClass
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
        return 'laravel-username-generator';
    }
}
