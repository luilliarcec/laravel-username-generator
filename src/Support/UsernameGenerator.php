<?php

namespace Luilliarcec\LaravelUsernameGenerator\Support;

use BadMethodCallException;
use Error;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Luilliarcec\LaravelUsernameGenerator\Contracts\UsernameDriverContract;
use Luilliarcec\LaravelUsernameGenerator\Exceptions\UsernameGeneratorException;
use Luilliarcec\LaravelUsernameGenerator\Support\Drivers\Email;
use Luilliarcec\LaravelUsernameGenerator\Support\Drivers\Name;

class UsernameGenerator
{
    /**
     * Drivers aliases
     *
     * @var string[]
     */
    protected const DRIVER_ALIASES = [
        'name' => Name::class,
        'email' => Email::class,
    ];

    /**
     * Model Eloquent use SoftDelete
     *
     * @var bool
     */
    protected $withTrashed = false;

    /**
     * Model Eloquent with username
     *
     * @var string
     */
    protected $model = 'App\Models\User';

    /**
     * Column for Username in database
     *
     * @var string
     */
    protected $column = 'username';

    /**
     * Case string lower or upper
     *
     * @var string
     */
    protected $case = 'lower';

    /**
     * Driver generator name or email
     *
     * @var mixed
     */
    protected $driver = 'name';

    /**
     * Indicates if you use softDeletes
     *
     * @return $this
     */
    public function withTrashed(): UsernameGenerator
    {
        $this->withTrashed = true;

        return $this;
    }

    /**
     * Indicates if you dont use softDeletes
     *
     * @return $this
     */
    public function withoutTrashed(): UsernameGenerator
    {
        $this->withTrashed = false;

        return $this;
    }

    /**
     * Set the model to use for the generation of usernames
     *
     * @param string $model
     * @param string|null $column
     * @return $this
     */
    public function setModel(string $model, string $column = null): UsernameGenerator
    {
        $this->model = $model;

        if ($column) {
            $this->setColum($column);
        }

        return $this;
    }

    /**
     * Set the column to use for the generation of usernames
     *
     * @param string $column
     * @return $this
     */
    public function setColum(string $column): UsernameGenerator
    {
        $this->column = $column;

        return $this;
    }

    /**
     * Set the case to use for the generation of usernames
     *
     * @param string $case
     * @return $this
     */
    public function setCase(string $case): UsernameGenerator
    {
        $this->case = $case;

        return $this;
    }

    /**
     * Set the driver to use for the generation of usernames
     *
     * @param mixed $driver
     * @return $this
     */
    public function setDriver($driver): UsernameGenerator
    {
        $this->driver = $driver;

        return $this;
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

        return $username . $this->getPrefixUsername($username);
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
        $length = strlen($username);
        $users = $this->findDuplicateUsername($username);
        $prefix = '';

        if ($users->isNotEmpty()) {
            $users = $users->filter(function ($user) use ($length) {
                return is_numeric(
                    substr($user->{$this->column}, $length)
                );
            });

            if ($users->isNotEmpty()) {
                $user = $users
                    ->sortByDesc(function ($user) use ($length) {
                        return substr($user->{$this->column}, $length);
                    })
                    ->first();

                $prefix = substr($user->{$this->column}, $length) + 1;
            } else {
                $prefix = 1;
            }
        }

        return $prefix;
    }

    /**
     * Set the model to use for the generation of usernames
     *
     * @return \Illuminate\Database\Eloquent\Builder
     *
     * @throws UsernameGeneratorException
     */
    protected function query(): \Illuminate\Database\Eloquent\Builder
    {
        if ($this->withTrashed) {
            return $this
                ->getModel()
                ->newQuery()
                ->withTrashed();
        } else {
            return $this
                ->getModel()
                ->newQuery();
        }
    }

    /**
     * Search for similar or repeated username
     *
     * @param string $username
     * @return mixed
     * @throws UsernameGeneratorException
     */
    protected function findDuplicateUsername(string $username): \Illuminate\Database\Eloquent\Collection
    {
        $duplicate = $this->query()
            ->where($this->column, $username)
            ->get([$this->column]);

        return $duplicate->isNotEmpty()
            ? $this->query()
                ->where($this->column, 'LIKE', "$username%")
                ->get([$this->column])
            : $duplicate;
    }

    /**
     * Returns an instance of the model in its configuration file
     *
     * @return Model
     * @throws UsernameGeneratorException
     */
    protected function getModel(): Model
    {
        try {
            $model = new $this->model;

            if (!$model instanceof Model) {
                throw new UsernameGeneratorException('[' . strval($this->model) . '] is not an instance of ' . Model::class, null);
            }

            return $model;
        } catch (Error $e) {
            throw new UsernameGeneratorException('Unable to instantiate the model [' . strval($this->model) . ']: ' . str_replace('"', '\'', $e->getMessage()), null, $e);
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
        if (is_string($this->driver)) {
            if ($driver = $this->resolveDriverByAlias()) {
                return $driver;
            } elseif ($driver = $this->resolveDriverByClassName()) {
                return $driver;
            }
        }

        return $this->resolveDriverByObject();
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

    /**
     * Resolve Driver by Alias
     *
     * @return UsernameDriverContract|null
     */
    private function resolveDriverByAlias(): ?UsernameDriverContract
    {
        if (Arr::exists(self::DRIVER_ALIASES, $this->driver)) {
            $driver = self::DRIVER_ALIASES[$this->driver];

            return new $driver;
        }

        return null;
    }

    /**
     * Resolve Driver by ClassName
     *
     * @return UsernameDriverContract|null
     * @throws UsernameGeneratorException
     */
    private function resolveDriverByClassName(): ?UsernameDriverContract
    {
        try {
            return $this->resolveDriverByObject(new $this->driver);
        } catch (Error $e) {
            throw new UsernameGeneratorException('Unable to resolve the driver [' . strval($this->driver) . ']: ' . str_replace('"', '\'', $e->getMessage()), null, $e);
        }
    }

    /**
     * Resolve Driver by Object
     *
     * @param object|null $driver
     * @return UsernameDriverContract|mixed
     * @throws UsernameGeneratorException
     */
    private function resolveDriverByObject(object $driver = null): UsernameDriverContract
    {
        $driver = $driver ?: $this->driver;

        if ($driver instanceof UsernameDriverContract) {
            return $driver;
        } else {
            throw new UsernameGeneratorException('[' . strval($driver) . '] is not an instance of ' . UsernameDriverContract::class, null);
        }
    }
}
