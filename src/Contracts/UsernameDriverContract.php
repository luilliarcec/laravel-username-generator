<?php

namespace Luilliarcec\LaravelUsernameGenerator\Contracts;

use Luilliarcec\LaravelUsernameGenerator\Exceptions\UsernameGeneratorException;

interface UsernameDriverContract
{
    /**
     * Create the username from the received parameters
     *
     * @param string $name Firstname or Email
     * @param string|null $lastname Lastname
     * @return string
     * @throws UsernameGeneratorException
     */
    public function make(string $name, string $lastname = null): string;
}
