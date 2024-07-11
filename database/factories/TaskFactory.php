<?php

namespace Database\Factories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'description' => $this->faker->paragraph,
            'area' => $this->faker->randomElement(Task::$areas),
            'status' => $this->faker->randomElement(Task::$statuses),
            'time_limit' => $this->faker->numberBetween(1, 72),
            'reason' => $this->faker->optional()->sentence,
            'user_id' => \App\Models\User::factory(),
            'condominium_id' => \App\Models\Condominium::factory(),
        ];
    }
}
