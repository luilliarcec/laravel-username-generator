<?php

namespace Tests\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Luilliarcec\LaravelUsernameGenerator\Concerns\HasUsername;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes, HasUsername;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The name/email or value with which the username will be generated.
     *
     * @return string
     */
    protected function getName(): string
    {
        return $this->name;
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
}
