<?php

namespace Tests\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Luilliarcec\LaravelUsernameGenerator\Concerns\HasUsername;
use Tests\database\factories\CustomerFactory;

class Customer extends Model
{
    use Notifiable;
    use SoftDeletes;
    use HasFactory;
    use HasUsername;

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
     * Create a new factory instance for the model.
     *
     * @return CustomerFactory
     */
    protected static function newFactory(): CustomerFactory
    {
        return CustomerFactory::new();
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
