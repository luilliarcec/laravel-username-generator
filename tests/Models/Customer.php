<?php

namespace Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Luilliarcec\LaravelUsernameGenerator\Concerns\HasUsername;

class Customer extends Model
{
    use Notifiable, HasUsername;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'username',
    ];

    /**
     * The name/email or value with which the username will be generated.
     *
     * @return string
     */
    protected function getName(): string
    {
        return $this->firts_name;
    }

    /**
     * If the "Name" driver is used and the record stores the last name, separately, the value can be returned here.
     *
     * @return string|null
     */
    protected function getLastName(): ?string
    {
        return $this->last_name;
    }

    /**
     * The column where the username will be stored.
     *
     * @return string
     */
    protected function getUsernameColumn(): string
    {
        return 'username';
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
        return mb_strtoupper($username, 'UTF-8');
    }
}
