<?php

namespace Luilliarcec\LaravelUsernameGenerator\Support\Drivers;

use Luilliarcec\LaravelUsernameGenerator\Contracts\UsernameDriverContract;
use Luilliarcec\LaravelUsernameGenerator\Exceptions\UsernameGeneratorException;

class Email implements UsernameDriverContract
{
    /**
     * Create the username from the received parameters
     *
     * @param string $name Firstname or Email
     * @param string|null $lastname Lastname
     * @return string
     * @throws UsernameGeneratorException
     */
    public function make(string $name, string $lastname = null): string
    {
        if (!filter_var($name, FILTER_VALIDATE_EMAIL)) {
            throw new UsernameGeneratorException(
                'The value provided does not have a valid email format.'
            );
        }

        $username = explode('@', $name);

        return $username[0];
    }
}
