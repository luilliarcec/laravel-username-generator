<?php

namespace Luilliarcec\LaravelUsernameGenerator\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Luilliarcec\LaravelUsernameGenerator\Contracts\DriverContract;
use Luilliarcec\LaravelUsernameGenerator\Drivers\Name;
use Luilliarcec\LaravelUsernameGenerator\Exceptions\UsernameGeneratorException;

trait HasUsername
{
    /**
     * The column where the username will be stored.
     *
     * @return string
     */
    protected abstract function getUsernameColumn(): string;

    /**
     * The name/email or value with which the username will be generated.
     *
     * @return string
     */
    protected abstract function getName(): string;

    /**
     * Boot the has username trait for a model.
     *
     * @return void
     */
    public static function bootHasUsername(): void
    {
        static::creating(function (self $model) {
            $model->setAttribute($model->getUsernameColumn(), $model->getUsername());
        });
    }

    /**
     * Driver to use to generate the username.
     *
     * @return DriverContract
     */
    protected function getUsernameDriver(): DriverContract
    {
        return new Name();
    }

    /**
     * If the "Name" driver is used and the record stores the last name, separately, the value can be returned here.
     *
     * @return string|null
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
        $username = $this->setUsernameCase($username);

        return $this->setSuffixUsername($username);
    }

    /**
     * Applies text case to username, by default lowercase.
     *
     * @param  string  $username
     *
     * @return string
     */
    protected function setUsernameCase(string $username): string
    {
        return mb_strtolower($username, 'UTF-8');
    }

    /**
     * If the username is duplicate, a numeric suffix is set.
     *
     * @param  string  $username
     *
     * @return string
     *
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
     *
     * @return Builder
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
     *
     * @param  string  $username
     *
     * @return Collection
     */
    protected function getDuplicateOrSimilarUsernames(string $username): Collection
    {
        return $this->getUsernameQuery()
            ->where($this->getUsernameColumn(), 'regexp', "{$username}[0-9]*$")
            ->pluck($this->getUsernameColumn());
    }
}
