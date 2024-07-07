<?php

namespace Tests\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Tests\Models\Customer;

class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public function definition(): array
    {
        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
        ];
    }
}
