<?php

namespace Luilliarcec\LaravelUsernameGenerator\Drivers;

use Luilliarcec\LaravelUsernameGenerator\Contracts\DriverContract;
use Luilliarcec\LaravelUsernameGenerator\Exceptions\UsernameGeneratorException;

class Email implements DriverContract
{
    /**
     * Create the username from the received parameters.
     *
     * @param  string  $name  Firstname or Email
     * @param  string|null  $lastname  Lastname
     *
     * @throws UsernameGeneratorException
     */
    public function make(string $name, ?string $lastname = null): string
    {
        if (! filter_var($name, FILTER_VALIDATE_EMAIL)) {
            throw new UsernameGeneratorException(
                'The value provided does not have a valid email format.'
            );
        }

        $username = explode('@', $name);

        return mb_strtolower(trim($username[0]));
    }
}
