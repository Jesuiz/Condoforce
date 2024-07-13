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
            'name' => $this->faker->randomElement(['Los Pinos', 'Nuevo Alcázar', 'Las Casuarinas', 'Besco', 'La Planicie', 'Los Sauces', 'La Estancia', 'La Pradera', 'El Sol de la Molina']),
            'address' => $this->faker->streetAddress(),
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