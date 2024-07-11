<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Condominium;
use Illuminate\Database\Eloquent\Factories\Factory;

class CondominiumFactory extends Factory
{
    protected $model = Condominium::class;

    public function definition()
    {
        $condoName = $this->faker->sentence(2, true);

        return [
            'name' => $condoName,
            'address' => $this->faker->address(),
            'is_active' => $this->faker->boolean(80),
        ];
    }

    /**
     * Indicate that the condominium is inactive.
     */
    public function inactive()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_active' => false,
            ];
        });
    }

    /**
     * Indicate that the condominium is active.
     */
    public function active()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_active' => true,
            ];
        });
    }
}