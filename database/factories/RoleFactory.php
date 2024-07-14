<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Condominium;
use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoleFactory extends Factory
{
    protected $model = Role::class;

    public function definition()
    {
        return [
            'name' => $this->faker->randomElement(Role::$areas),
            'salary' => $this->faker->randomFloat(2, 1000, 5000),
        ];
    }
}
