<?php

namespace Luilliarcec\LaravelUsernameGenerator\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Luilliarcec\LaravelUsernameGenerator\Contracts\DriverContract;
use Luilliarcec\LaravelUsernameGenerator\Drivers\Name;
use Luilliarcec\LaravelUsernameGenerator\Exceptions\UsernameGeneratorException;

trait HasUsername
{
    /**
     * The column where the username will be stored.
     */
    abstract protected function getUsernameColumn(): string;

    /**
     * The name/email or value with which the username will be generated.
     */
    abstract protected function getName(): string;

    /**
     * Boot the has username trait for a model.
     */
    public static function bootHasUsername(): void
    {
        static::creating(function (self $model) {
            $model->setAttribute($model->getUsernameColumn(), $model->getUsername());
        });
    }

    /**
     * Driver to use to generate the username.
     */
    protected function getUsernameDriver(): DriverContract
    {
        return new Name();
    }

    /**
     * If the "Name" driver is used and the record stores the last name, separately, the value can be returned here.
     */
    protected function getLastName(): ?string
    {
        return null;
    }

    /**
     * Generate the username for this model.
     *
     * @throws UsernameGeneratorException
     */
    public function getUsername(): string
    {
        $driver = $this->getUsernameDriver();

        $username = $driver->make($this->getName(), $this->getLastName());
        $username = $this->transformUsername($username);

        return $this->setSuffixUsername($username);
    }

    /**
     * Apply transformation code to the username, by default it is transformed to lower case.
     */
    protected function transformUsername(string $username): string
    {
        return mb_strtolower($username, 'UTF-8');
    }

    /**
     * If the username is duplicate, a numeric suffix is set.
     */
    protected function setSuffixUsername(string $username): string
    {
        $length = strlen($username);
        $usernames = $this->getDuplicateOrSimilarUsernames($username);

        if ($usernames->isEmpty()) {
            return $username;
        }

        $lasted = $usernames
            ->map(fn ($value) => intval(substr($value, $length)))
            ->sortDesc()
            ->first();

        $suffix = 1;
        $suffix += $lasted;

        return "{$username}{$suffix}";
    }

    /**
     * Configure the query and use soft delete, if present.
     */
    protected function getUsernameQuery(): Builder
    {
        if (in_array(SoftDeletes::class, class_uses($this))) {
            return $this
                ->query()
                ->withTrashed();
        }

        return $this->query();
    }

    /**
     * Search similar or repeated username.
     */
    protected function getDuplicateOrSimilarUsernames(string $username): Collection
    {
        return $this->getUsernameQuery()
            ->where($this->getUsernameColumn(), 'like', "{$username}%")
            ->where(fn (Builder $query) => $this->getUsernameRegexSimilarityQuery($query, $username))
            ->pluck($this->getUsernameColumn());
    }

    /**
     * Gets the similarity condition for the regex query.
     */
    protected function getUsernameRegexSimilarityQuery(Builder $query, string $username): void
    {
        $column = $this->getUsernameColumn();

        $driver = $this->getConnection()->getDriverName();

        if ($driver === 'mysql') {
            $query
                ->where($column, 'like', $username)
                ->orWhere($column, 'regexp', "^{$username}[0-9]");

            return;
        }

        if ($driver === 'pgsql') {
            $query
                ->where($column, 'like', $username)
                ->orWhere($column, '~', "^{$username}[0-9]");

            return;
        }

        // Working on: sqlsrv
        $query
            ->where($column, 'like', $username)
            ->orWhere($column, 'like', "{$username}[0-9]%");
    }
}
