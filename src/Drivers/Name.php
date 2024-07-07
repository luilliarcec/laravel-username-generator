<?php

namespace Luilliarcec\LaravelUsernameGenerator\Drivers;

use Luilliarcec\LaravelUsernameGenerator\Contracts\DriverContract;
use Luilliarcec\LaravelUsernameGenerator\Exceptions\UsernameGeneratorException;

class Name implements DriverContract
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
        $this->validate($name);

        if ($this->getTotalWords($name, $lastname) == 1) {
            return mb_strtolower($name, 'UTF-8');
        }

        $lastname_array = $this->getLastnameAsArray($name, $lastname);
        $name_array = $this->getNameAsArray($name);

        $first_letter = $this->getFirstLetterName($name_array);
        $first_lastname = $this->getFirstLastname($lastname_array);
        $first_second_lastname = $this->getFirstLetterSecondLastname($lastname_array);

        return mb_strtolower($first_letter.$first_lastname.$first_second_lastname, 'UTF-8');
    }

    /**
     * Get the first letter of the first name.
     */
    protected function getFirstLetterName(array $firstname): string
    {
        return str_split($firstname[0])[0];
    }

    /**
     * Get the first last name.
     */
    protected function getFirstLastname(array $lastname): string
    {
        return count($lastname) > 0 ? $lastname[0] : '';
    }

    /**
     * Get the first letter of the second last name.
     */
    protected function getFirstLetterSecondLastname(array $lastname): string
    {
        return count($lastname) > 1 ? str_split($lastname[1])[0] : '';
    }

    /**
     * Get the number of words that make up the name.
     */
    protected function getTotalWords(string $name, ?string $lastname = null): int
    {
        return $lastname ? count(explode(' ', "{$name} {$lastname}")) : count(explode(' ', $name));
    }

    /**
     * Validate that the initial conditions of the name driver.
     *
     *
     * @throws UsernameGeneratorException
     */
    protected function validate(string $name): void
    {
        if (filter_var($name, FILTER_VALIDATE_EMAIL)) {
            throw new UsernameGeneratorException('Use the email driver, to generate a username from the email.');
        }

        if ($name == null) {
            throw new UsernameGeneratorException('The name cannot be null');
        }
    }

    /**
     * Get valid name as array.
     */
    protected function getNameAsArray(string $name): array
    {
        $name_array = explode(' ', $name);
        $count_name = count($name_array);

        if ($count_name > 2) {
            $name_array = array_slice($name_array, 0, 2);
        }

        return $name_array;
    }

    /**
     * Get valid lastname as array.
     */
    protected function getLastnameAsArray(string $name, ?string $lastname): array
    {
        $name_array = explode(' ', $name);
        $count_name = count($name_array);

        $lastname_array = $lastname ? explode(' ', $lastname) : [];

        if ($count_name > 2) {
            $lastname_array = array_slice($name_array, ((min($count_name, 4)) - 2), 2);
        } elseif (count($lastname_array) == 0) {
            $lastname_array = $count_name > 1 ? [$name_array[1]] : [];
        }

        return $lastname_array;
    }
}
