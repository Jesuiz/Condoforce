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
        $condoName = $this->faker->sentence(2, true);

        return [
            'name' => $this->faker->randomElement(Role::$areas),
            'salary' => $this->faker->randomFloat(2, 1000, 5000),
            'user_id' => function () {
                return \App\Models\User::inRandomOrder()->first()->id; },
            'condominium_id' => function () {
                return \App\Models\Condominium::inRandomOrder()->first()->id; },
        ];
    }
}
