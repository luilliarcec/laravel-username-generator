<?php

namespace Tests\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class UserQuery extends Builder
{
    /**
     * Save a new model and return the instance.
     *
     * @param  array  $attributes
     * @return \Illuminate\Database\Eloquent\Model|$this
     */
    public function create(array $attributes = [])
    {
        $data = array_replace([
            'name' => Str::random(20),
            'email' => Str::random(16),
            'username' => Str::random(8),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ], $attributes);

        return tap($this->newModelInstance($data), function ($instance) {
            $instance->save();
        });
    }
}
