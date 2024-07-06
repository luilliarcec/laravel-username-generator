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
        'name',
        'username',
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
