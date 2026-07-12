<?php

namespace Luilliarcec\LaravelUsernameGenerator\Drivers;

use Illuminate\Support\Str;
use Luilliarcec\LaravelUsernameGenerator\Contracts\DriverContract;
use Luilliarcec\LaravelUsernameGenerator\Exceptions\UsernameGeneratorException;

class Email implements DriverContract
{
    public function make(string $name, ?string $lastname = null): string
    {
        if (! filter_var($name, FILTER_VALIDATE_EMAIL)) {
            throw new UsernameGeneratorException('The value provided does not have a valid email format.');
        }

        $local = explode('@', $name)[0];

        return (string) preg_replace('/[^a-z0-9._-]/', '', mb_strtolower(Str::ascii(trim($local)), 'UTF-8'));
    }
}
