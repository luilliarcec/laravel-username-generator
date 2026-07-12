<?php

namespace Luilliarcec\LaravelUsernameGenerator\Drivers;

use Illuminate\Support\Str;
use Luilliarcec\LaravelUsernameGenerator\Contracts\DriverContract;
use Luilliarcec\LaravelUsernameGenerator\Exceptions\UsernameGeneratorException;

/**
 * Genera el username con el patrón:
 *   inicial(primer nombre) + primer apellido + inicial(segundo apellido)
 *
 * Ej.: "Luis Andrés" "Arce Cárdenas" -> "larcec".
 *
 * Mejoras respecto a la versión original:
 *  - Normalización a ASCII (quita tildes/diacríticos): "Núñez" -> "nunez".
 *  - Primera letra multibyte-safe (mb_substr en vez de str_split, que rompía
 *    caracteres acentuados como "Ángel").
 *  - Sanitiza el resultado a [a-z0-9] (elimina apóstrofos, guiones, etc.).
 */
class Name implements DriverContract
{
    public function make(string $name, ?string $lastname = null): string
    {
        $this->validate($name);

        if ($this->getTotalWords($name, $lastname) == 1) {
            return $this->normalize($name);
        }

        $lastname_array = $this->getLastnameAsArray($name, $lastname);
        $name_array = $this->getNameAsArray($name);

        $first_letter = $this->firstLetter($name_array[0] ?? '');
        $first_lastname = $lastname_array[0] ?? '';
        $first_second_lastname = $this->firstLetter($lastname_array[1] ?? '');

        return $this->normalize($first_letter.$first_lastname.$first_second_lastname);
    }

    /**
     * Primera letra (multibyte-safe) de una palabra.
     */
    protected function firstLetter(string $word): string
    {
        return mb_substr($word, 0, 1, 'UTF-8');
    }

    /**
     * Normaliza a un username ASCII en minúsculas y sin caracteres extraños.
     */
    protected function normalize(string $value): string
    {
        $ascii = Str::ascii($value);

        return (string) preg_replace('/[^a-z0-9]/', '', mb_strtolower($ascii, 'UTF-8'));
    }

    /**
     * Get the number of words that make up the name.
     */
    protected function getTotalWords(string $name, ?string $lastname = null): int
    {
        $full = trim($lastname ? "{$name} {$lastname}" : $name);

        return count(preg_split('/\s+/', $full) ?: []);
    }

    /**
     * Validate the initial conditions of the name driver.
     *
     * @throws UsernameGeneratorException
     */
    protected function validate(string $name): void
    {
        if (filter_var($name, FILTER_VALIDATE_EMAIL)) {
            throw new UsernameGeneratorException('Use the email driver, to generate a username from the email.');
        }

        if (trim($name) === '') {
            throw new UsernameGeneratorException('The name cannot be empty.');
        }
    }

    /**
     * Get valid name as array (a lo sumo los dos primeros nombres).
     *
     * @return list<string>
     */
    protected function getNameAsArray(string $name): array
    {
        $name_array = preg_split('/\s+/', trim($name)) ?: [];

        if (count($name_array) > 2) {
            $name_array = array_slice($name_array, 0, 2);
        }

        return $name_array;
    }

    /**
     * Get valid lastname as array.
     *
     * @return list<string>
     */
    protected function getLastnameAsArray(string $name, ?string $lastname): array
    {
        $name_array = preg_split('/\s+/', trim($name)) ?: [];
        $count_name = count($name_array);

        $lastname_array = $lastname ? (preg_split('/\s+/', trim($lastname)) ?: []) : [];

        if ($count_name > 2) {
            $lastname_array = array_slice($name_array, min($count_name, 4) - 2, 2);
        } elseif (count($lastname_array) == 0) {
            $lastname_array = $count_name > 1 ? [$name_array[1]] : [];
        }

        return array_values($lastname_array);
    }
}
