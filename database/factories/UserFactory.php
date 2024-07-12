<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Condominium;
use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => bcrypt('123'),
            'country' => $this->faker->countryCode(),
            'doc_type' => $this->faker->randomElement(['DNI', 'CE', 'PTP', 'PAS']),
            'document' => $this->faker->unique()->numerify('########'),
            'cellphone' => $this->faker->numerify('#########'),
            'address' => $this->faker->streetAddress(),
            'profile_img' => $this->faker->imageUrl(640, 480, 'people', true, 'Faker'),
            'condominium_id' => function () {
                return \App\Models\Condominium::inRandomOrder()->first()->id; },

            'email_verified_at' => now(),
            'is_active' => $this->faker->boolean(),
            'remember_token' => Str::random(10),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (User $user) {
            Role::create([
                'name' => $this->faker->randomElement(['Residente', 'Vigilante', 'Mantenimiento', 'Supervisor', 'Delegado', 'Administrador', 'Gerente']),
                'salary' => $this->faker->randomFloat(2, 1000, 5000),
                'user_id' => $user->id,
                'condominium_id' => $user->condominium_id,
            ]);
        });
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
