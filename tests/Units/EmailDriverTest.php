<?php

use Luilliarcec\LaravelUsernameGenerator\Drivers;
use Luilliarcec\LaravelUsernameGenerator\Exceptions\UsernameGeneratorException;

beforeEach(function () {
    $this->driver = new Drivers\Email();
});

it('generate the username with a email', function () {
    expect($this->driver->make('luilliarcec@gmail.com'))
        ->toBe('luilliarcec');
});

it('throws an exception when the value passed is not an email', function () {
    $this->driver->make('Luis Arce');
})->throws(UsernameGeneratorException::class, 'The value provided does not have a valid email format.');
