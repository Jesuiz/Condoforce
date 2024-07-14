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
        return [
            'name' => $this->faker->randomElement(['Los Pinos', 'Nuevo Alcázar', 'Las Casuarinas', 'Besco', 'La Planicie', 'Los Sauces', 'La Estancia', 'La Pradera', 'El Sol de la Molina']),
            'address' => $this->faker->streetAddress(),
            'budget' => $this->faker->randomFloat(2, 8000, 15000),
            'is_active' => $this->faker->boolean(80),
        ];
    }

    public function inactive()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_active' => false,
            ];
        });
    }

    public function active()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_active' => true,
            ];
        });
    }
}