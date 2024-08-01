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
            'name' => $this->faker->sentence(2, 4),
            'description' => $this->faker->paragraph,
            'area' => $this->faker->randomElement(Task::$areas),
            'status' => $this->faker->randomElement(Task::$statuses),
            'time_limit' => $this->faker->numberBetween(1, 72),
            'finish' => $this->faker->numberBetween(0, 1),
            'user_id' => function () {
                return \App\Models\User::inRandomOrder()->first()->id; },
            'condominium_id' => function () {
                return \App\Models\Condominium::inRandomOrder()->first()->id; },
            'report_id' => function () {
                return \App\Models\Report::inRandomOrder()->first()->id; },
        ];
    }
}
