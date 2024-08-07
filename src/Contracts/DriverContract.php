<?php

namespace Luilliarcec\LaravelUsernameGenerator\Contracts;

use Luilliarcec\LaravelUsernameGenerator\Exceptions\UsernameGeneratorException;

interface DriverContract
{
    /**
     * Create the username from the received parameters.
     *
     * @param  string  $name  Firstname or Email
     * @param  string|null  $lastname  Lastname
     *
     * @throws UsernameGeneratorException
     */
    public function make(string $name, ?string $lastname = null): string;
}
