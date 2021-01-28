<?php

namespace Luilliarcec\LaravelUsernameGenerator\Support;

use BadMethodCallException;
use Error;
use Illuminate\Support\Str;
use Luilliarcec\LaravelUsernameGenerator\Contracts\UsernameDriverContract;
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
        $this->model = config('username-generator.model');
        $this->column = config('username-generator.column');
        $this->case = config('username-generator.case');
        $this->driver = config('username-generator.driver');
    }

    /**
     * Create the username from the received parameters
     *
     * @param string $name
     * @param string|null $lastname
     * @return string
     * @throws UsernameGeneratorException
     */
    public function make(string $name, string $lastname = null): string
    {
        $username = $this->getDriver()->make($name, $lastname);
        $username = $this->applyCase($username);
        $prefix = $this->getPrefixUsername($username);
        return ($username . $prefix);
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
     * Search for similar or repeated username
     *
     * @param string $username
     * @return mixed
     * @throws UsernameGeneratorException
     */
    protected function findDuplicateUsername(string $username)
    {
        $model = $this->getModel();

        $duplicate = $model->newQuery()->where($this->column, $username)->get([$this->column]);

        return $duplicate->count() ?
            $model->newQuery()->where($this->column, 'LIKE', "$username%")->get([$this->column]) :
            $duplicate;
    }

    /**
     * Returns an instance of the model in its configuration file
     *
     * @return mixed
     * @throws UsernameGeneratorException
     */
    protected function getModel()
    {
        try {
            return new $this->model;
        } catch (Error $e) {
            throw new UsernameGeneratorException('Unable to instantiate the model [' . strval($this->model) . ']: ' . $e->getMessage(), null, $e);
        }
    }

    /**
     * Get the driver instance
     *
     * @return UsernameDriverContract
     * @throws UsernameGeneratorException
     */
    protected function getDriver(): UsernameDriverContract
    {
        $driver = '\Luilliarcec\LaravelUsernameGenerator\Support\Drivers\\' . Str::studly(strval($this->driver));

        try {
            return new $driver;
        } catch (Error $e) {
            throw new UsernameGeneratorException('Driver type not supported [' . strval($this->driver) . ']: ' . $e->getMessage(), null, $e);
        }
    }

    /**
     * Get parsed by the case
     *
     * @param string $username
     * @return string
     * @throws UsernameGeneratorException
     */
    protected function applyCase(string $username): string
    {
        try {
            return Str::{$this->case}($username);
        } catch (BadMethodCallException $e) {
            throw new UsernameGeneratorException('Case type not supported [' . strval($this->case) . ']: ' . $e->getMessage(), null, $e);
        }
    }
}
