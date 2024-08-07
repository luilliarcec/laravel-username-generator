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
    use HasFactory;
    use HasUsername;
    use Notifiable;
    use SoftDeletes;

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
     */
    protected function getName(): string
    {
        return $this->first_name;
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): CustomerFactory
    {
        return CustomerFactory::new();
    }

    /**
     * If the "Name" driver is used and the record stores the last name, separately, the value can be returned here.
     */
    protected function getLastName(): ?string
    {
        return $this->last_name;
    }

    /**
     * The column where the username will be stored.
     */
    protected function getUsernameColumn(): string
    {
        return 'username';
    }

    /**
     * Apply transformation code to the username, by default it is transformed to lower case.
     */
    protected function transformUsername(string $username): string
    {
        return mb_strtoupper($username, 'UTF-8');
    }
}
