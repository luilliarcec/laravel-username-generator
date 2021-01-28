<?php

namespace Luilliarcec\LaravelUsernameGenerator\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Customer extends Model
{
    use Notifiable;

    protected $fillable = [
        'name', 'username',
    ];
}
