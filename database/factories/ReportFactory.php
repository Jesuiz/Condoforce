<?php

namespace Database\Factories;

use App\Models\Report;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ReportFactory extends Factory
{
    protected $model = Report::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'description' => $this->faker->paragraph,
            'area' => $this->faker->randomElement(Report::$areas),
            'user_id' => \App\Models\User::factory(),
            'condominium_id' => \App\Models\Condominium::factory(),
        ];
    }
}
