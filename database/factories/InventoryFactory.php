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
            'category' => $this->faker->randomElement(Inventory::$categories),
            'units' => $this->faker->numberBetween(1, 100),
            'amount' => $this->faker->randomFloat(2, 10, 1000),
            'expiration' => function () {
                if ($this->faker->boolean(90)) {
                        return $this->faker->dateTimeBetween('now', '+5 years')->format('Y-m-d');
                    } return null; },
            'user_id' => function () {
                return \App\Models\User::inRandomOrder()->first()->id; },
            'condominium_id' => function () {
                return \App\Models\Condominium::inRandomOrder()->first()->id; },
        ];
    }
}
