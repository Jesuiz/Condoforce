<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Condominium;
use App\Models\Occupation;
use Illuminate\Database\Eloquent\Factories\Factory;

class OccupationFactory extends Factory
{
    protected $model = Occupation::class;
    protected static $index = 0;

    public function definition()
    {
        $areas = Occupation::$areas;
        $area = $areas[self::$index];
        self::$index = (self::$index + 1) % count($areas);

        return [
            'name' => $area,
            'salary' => $this->faker->randomFloat(2, 1000, 5000),
        ];
    }
}
