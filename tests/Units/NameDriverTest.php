<?php

use Luilliarcec\LaravelUsernameGenerator\Drivers;
use Luilliarcec\LaravelUsernameGenerator\Exceptions\UsernameGeneratorException;

beforeEach(function () {
    $this->driver = new Drivers\Name;
});

it('generate the username with a full name', function () {
    expect($this->driver->make('Luis Andrés Arce Cárdenas'))
        ->toBe('larcec');
});

it('generates the username considering a maximum of 4 words', function () {
    expect($this->driver->make('Luis Andrés Arce Cárdenas', 'Other Lastname'))
        ->toBe('larcec')
        ->and($this->driver->make('Luis Andrés', 'Arce Cárdenas Maple'))
        ->toBe('larcec')
        ->and($this->driver->make('Luis', 'Arce Cárdenas Maple'))
        ->toBe('larcec')
        ->and($this->driver->make('Luis Andrés Arce Cárdenas Maple'))
        ->toBe('larcec')
        ->and($this->driver->make('Luis Arce Cárdenas', 'Maple'))
        ->toBe('larcec');
});

it('generates the username with 1 word', function () {
    expect($this->driver->make('Luis'))
        ->toBe('luis');
});

it('generates the username with 2 word', function () {
    expect($this->driver->make('Luis Arce'))
        ->toBe('larce')
        ->and($this->driver->make('Luis', 'Arce'))
        ->toBe('larce');
});

it('generates the username with 3 word', function () {
    expect($this->driver->make('Luis Andrés', 'Arce'))
        ->toBe('larce')
        ->and($this->driver->make('Luis', 'Arce Cárdenas'))
        ->toBe('larcec')
        ->and($this->driver->make('Luis Arce Cárdenas'))
        ->toBe('larcec');
});

it('throws an exception when the value passed is an email', function () {
    $this->driver->make('luilliarcec@gmail.com');
})->throws(UsernameGeneratorException::class, 'Use the email driver, to generate a username from the email.');

it('throws an exception when the value passed is null or empty', function () {
    $this->driver->make('');
})->throws(UsernameGeneratorException::class, 'The name cannot be empty.');

it('normalizes accents to ASCII', function () {
    expect($this->driver->make('Ángela', 'Núñez Óscar'))
        ->toBe('anunezo')
        ->and($this->driver->make('José', 'Pérez Ñústez'))
        ->toBe('jperezn')
        ->and($this->driver->make('María José', 'García'))
        ->toBe('mgarcia');
});

it('takes the first letter in a multibyte-safe way', function () {
    // La inicial de un nombre acentuado no debe romperse (mb_substr, no str_split).
    expect($this->driver->make('Úrsula', 'Íñigo Ángel'))
        ->toBe('uinigoa');
});

it('strips characters outside [a-z0-9]', function () {
    expect($this->driver->make("D'Angelo", "O'Brien Smith"))
        ->toBe('dobriens');
});
