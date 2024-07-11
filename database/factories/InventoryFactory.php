<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Condominium;
use App\Models\Inventory;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class InventoryFactory extends Factory
{
    protected $model = Inventory::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'description' => $this->faker->paragraph(),
            'units' => $this->faker->numberBetween(1, 100),
            'amount' => $this->faker->randomFloat(2, 10, 1000),
            'expiration' => $this->faker->numberBetween(1, 72),
            'user_id' => User::factory(),
            'condominium_id' => Condominium::factory(),
            'category_id' => Category::factory(),
        ];
    }
}