<?php

namespace Tests\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Luilliarcec\LaravelUsernameGenerator\Concerns\HasUsername;
use Tests\database\factories\UserFactory;

class User extends Authenticatable
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
        'name',
        'username',
        'email',
    ];

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }

    /**
     * The name/email or value with which the username will be generated.
     */
    protected function getName(): string
    {
        return $this->name;
    }

    /**
     * The column where the username will be stored.
     */
    protected function getUsernameColumn(): string
    {
        return 'username';
    }
}
