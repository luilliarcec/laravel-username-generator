<?php

namespace Luilliarcec\LaravelUsernameGenerator\Support;

use Error;
use Illuminate\Support\Str;
use Luilliarcec\LaravelUsernameGenerator\Exceptions\UsernameGeneratorException;

class UsernameGenerator
{
    /**
     * Model Eloquent with username
     *
     * @var \Illuminate\Config\Repository
     */
    protected $model;

    /**
     * Column for Username in database
     *
     * @var \Illuminate\Config\Repository
     */
    protected $column;

    /**
     * Case string lower or upper
     *
     * @var \Illuminate\Config\Repository
     */
    protected $case;

    /**
     * Driver generator name or email
     *
     * @var \Illuminate\Config\Repository
     */
    protected $driver;

    /**
     * UsernameGenerator constructor.
     */
    public function __construct()
    {
        $this->model = config('laravel-username-generator.model');
        $this->column = config('laravel-username-generator.column');
        $this->case = config('laravel-username-generator.case');
        $this->driver = config('laravel-username-generator.driver');
    }

    /**
     * @param $name
     * @param null $lastname
     * @return string
     * @throws UsernameGeneratorException
     */
    public function make($name, $lastname = null)
    {
        $this->validate($name, $lastname);

        $username = $this->getDriver($name, $lastname);

        $username = $this->getCase($username);

        $prefix = $this->getPrefixUsername($username);

        return ($username . $prefix);
    }

    /**
     * Generate user names by person names
     *
     * @param $name
     * @param $lastname
     * @return mixed|string
     * @throws UsernameGeneratorException
     */
    protected function driverName($name, $lastname)
    {
        if (filter_var($name, FILTER_VALIDATE_EMAIL)) {
            throw new UsernameGeneratorException(
                "Use the email driver, to generate a username from the email."
            );
        }

        $words = $this->countName($name, $lastname);

        switch ($words) {
            case 1:
            {
                return $this->makeUsernameWithOneName($name);
            }
            case 2:
            {
                return $username = $this->makeUsernameWithTwoName($name, $lastname);
            }
            case 3:
            {
                return $this->makeUsernameWithThreeName($name, $lastname);
            }
            case 4:
            {
                return $this->makeUsernameWithFullName($name, $lastname);
            }
            default:
            {
                throw new UsernameGeneratorException(
                    "Too many values have been provided to the username generator,
                    the candidate words provided are: {$words}, the number of words supported is: 4."
                );
            }
        }
    }

    /**
     * Generate user names by person emails
     *
     * @param $name
     * @return mixed
     * @throws UsernameGeneratorException
     */
    protected function driverEmail($name)
    {
        if (!filter_var($name, FILTER_VALIDATE_EMAIL)) {
            throw new UsernameGeneratorException(
                "The value provided does not have a valid email format."
            );
        }

        $username = explode('@', $name);
        return $username[0];
    }

    /**
     * Make username with only one name
     *
     * @param $name
     * @return mixed
     */
    protected function makeUsernameWithOneName($name)
    {
        return $name;
    }

    /**
     * Make username with a two names or one surnames
     *
     * @param $name
     * @param $lastname
     * @return string
     */
    protected function makeUsernameWithTwoName($name, $lastname)
    {
        $name_array = explode(' ', $name);

        $firstLetter = $this->getFirstLetterName($name_array);

        return $lastname ? ($firstLetter . $lastname) : ($firstLetter . $name_array[1]);
    }

    /**
     * Make username with a two names and one surnane or vice versa
     *
     * @param $name
     * @param $lastname
     * @return string
     */
    protected function makeUsernameWithThreeName($name, $lastname)
    {
        $name_array = explode(' ', $name);
        $lastname_array = explode(' ', $lastname);

        if (count($name_array) == 3) {
            $lastname_array = array_slice($name_array, 1);
        }

        $firstLetter = $this->getFirstLetterName($name_array);
        $firstLastname = $this->getFirstSurname($lastname_array);

        $firstLetterSecondSurname = count($lastname_array) == 2 ?
            $this->getFirstLetterSecondLastName($lastname_array) : '';

        return ($firstLetter . $firstLastname . $firstLetterSecondSurname);
    }

    /**
     * Make username with fullname
     *
     * @param $name
     * @param $lastname
     * @return string
     */
    protected function makeUsernameWithFullName($name, $lastname)
    {
        $name_array = explode(' ', $name);

        $lastname_array = count($name_array) == 2 ?
            explode(' ', $lastname) : array_slice($name_array, 2, 2);

        $name_array = count($name_array) == 4 ?
            array_slice($name_array, 0, 2) : $name_array;

        $firstLetter = $this->getFirstLetterName($name_array);
        $firstLastname = $this->getFirstSurname($lastname_array);
        $firstLetterSecondSurname = $this->getFirstLetterSecondLastName($lastname_array);

        return ($firstLetter . $firstLastname . $firstLetterSecondSurname);
    }

    /**
     * Check if the user already exists and return a differentiating number
     *
     * @param string $username
     * @return int|string
     * @throws UsernameGeneratorException
     */
    protected function getPrefixUsername(string $username)
    {
        $len = strlen($username);
        $users = $this->findDuplicateUsername($username);
        $prefix = "";

        if ($users->count()) {

            $prefixes = [];

            foreach ($users as $user) {
                $prefixAux = substr($user->{$this->column}, $len);

                if (is_numeric($prefixAux)) {
                    array_push($prefixes, $prefixAux);
                }
            }

            sort($prefixes, SORT_NUMERIC);

            $prefix = (int)end($prefixes) + 1;
        }

        return $prefix;
    }


    /**
     * Get the first letter of the first name
     *
     * @param array $firstname
     * @return mixed
     */
    protected function getFirstLetterName(array $firstname)
    {
        $firstname_letters = str_split($firstname[0]);
        return $firstname_letters[0];
    }

    /**
     * Get the first last name
     *
     * @param array $lastname
     * @return mixed
     */
    protected function getFirstSurname(array $lastname)
    {
        return $lastname[0];
    }

    /**
     * Get the first letter of the second last name
     *
     * @param array $lastname
     * @return string
     */
    protected function getFirstLetterSecondLastName(array $lastname)
    {
        $lastsurname_letters = str_split($lastname[1]);
        return $lastsurname_letters[0];
    }

    /**
     * Get the number of words that make up the name
     *
     * @param string $name
     * @param string|null $lastname
     * @return int
     */
    protected function countName($name, $lastname = null)
    {
        return $lastname ?
            count(explode(' ', $name . ' ' . $lastname)) :
            count(explode(' ', $name));
    }

    /**
     * Search for similar or repeated username
     *
     * @param $username
     * @return mixed
     * @throws UsernameGeneratorException
     */
    protected function findDuplicateUsername($username)
    {
        $model = $this->getModel($this->model);

        $duplicate = $model->newQuery()->where($this->column, $username)->get([$this->column]);

        return $duplicate->count() ?
            $model->newQuery()->where($this->column, 'LIKE', "$username%")->get([$this->column]) :
            $duplicate;
    }

    /**
     * Returns an instance of the model in its configuration file
     *
     * @param $model
     * @return mixed
     * @throws UsernameGeneratorException
     */
    protected function getModel($model)
    {
        try {
            return new $model;
        } catch (Error $e) {
            throw new UsernameGeneratorException(
                "Unable to instantiate the model [$model]: " . $e->getMessage(), null, $e
            );
        }
    }

    /**
     * Get the username according to the driver
     *
     * @param $name
     * @param $lastname
     * @return mixed|string
     * @throws UsernameGeneratorException
     */
    protected function getDriver($name, $lastname)
    {
        switch ($this->driver) {
            case 'name':
            {
                $username = $this->driverName($name, $lastname);
                break;
            }
            case 'email':
            {
                $username = $this->driverEmail($name);
                break;
            }
            default:
            {
                throw new UsernameGeneratorException("Driver not supported");
            }
        }

        return $username;
    }

    /**
     * Get parsed by the case
     *
     * @param string $username
     * @return string
     * @throws UsernameGeneratorException
     */
    protected function getCase(string $username): string
    {
        switch ($this->case) {
            case 'lower':
            {
                $username = Str::lower($username);
                break;
            }
            case 'upper':
            {
                $username = Str::upper($username);
                break;
            }
            default:
            {
                throw new UsernameGeneratorException("Case not supported");
            }
        }

        return $username;
    }

    /**
     * Valid that the general conditions of the package are met
     *
     * @param $name
     * @param $lastname
     * @throws UsernameGeneratorException
     */
    protected function validate($name, $lastname)
    {
        $countName = count(explode(' ', $name));
        $countLastName = $lastname == null ? 0 : count(explode(' ', $lastname));

        if ($name == null) {
            throw new UsernameGeneratorException(
                "The name cannot be null"
            );
        }

        if ($countName == 4 && $countLastName > 0) {
            throw new UsernameGeneratorException(
                "The lastname parameter is not supported when the name has fullname (four words)"
            );
        }

        if (($countName == 1 || $countName == 2) && $countLastName > 2) {
            throw new UsernameGeneratorException(
                "Only one or two lastname are supported"
            );
        }

        if ($countName == 3 && $countLastName > 0) {
            throw new UsernameGeneratorException(
                "The lastname parameter is not supported when the name has three words"
            );
        }
    }
}
