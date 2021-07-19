<?php

namespace Luilliarcec\LaravelUsernameGenerator\Facades;

use Illuminate\Support\Facades\Facade;
use Luilliarcec\LaravelUsernameGenerator\Exceptions\UsernameGeneratorException;
use Luilliarcec\LaravelUsernameGenerator\Support\UsernameGenerator;

/**
 * @method static string|UsernameGeneratorException make(string $name, string $lastname = null)
 * @method static UsernameGenerator withTrashed()
 * @method static UsernameGenerator withoutTrashed()
 * @method static UsernameGenerator setModel(string $model, string $column = null)
 * @method static UsernameGenerator setColum(string $column)
 * @method static UsernameGenerator setCase(string $case)
 * @method static UsernameGenerator setDriver($driver)
 *
 *
 * @see UsernameGenerator
 */
class Username extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return UsernameGenerator::class;
    }
}
