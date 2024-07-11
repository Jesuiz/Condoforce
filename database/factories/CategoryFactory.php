<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'description' => $this->faker->paragraph(),
            'area' => $this->faker->randomElement(['Residente', 'Vigilancia', 'Mantenimiento', 'Supervisión', 'Delegación', 'Administración', 'Gerencia']),
        ];
    }
}