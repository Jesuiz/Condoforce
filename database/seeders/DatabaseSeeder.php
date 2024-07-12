<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Condominium;
use App\Models\Category;
use App\Models\Inventory;
use App\Models\Report;
use App\Models\Task;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        echo "DatabaseSeeder iniciado...\n\n";

        // Paso 1: Crear condominios
        Condominium::truncate();
        Condominium::factory()->count(6)->create();
        Condominium::factory()->inactive()->count(1)->create();
        Condominium::factory()->active()->count(3)->create();

        // Paso 2: Crear usuario administrador
        User::truncate();
        $condominiums = Condominium::all();
        DB::table('users')->insert([
            'name' => 'Jesús Ruiz',
            'email' => 'jesuizmail@gmail.com',
            'password' => Hash::make('123'),
            'country' => 'VE',
            'doc_type' => 'CE',
            'document' => '005180167',
            'cellphone' => '935035069',
            'address' => 'Edif. 2, Dpto. 503, Cond. Los Pinos - El Agustino',
            'profile_img' => 'public/profile_img/jesus_ruiz.png',
            'condominium_id' => $condominiums->random()->id,
        ]);

        // Paso 3: Crear usuarios adicionales
        User::factory(25)->create(['condominium_id' => fn() => $condominiums->random()->id]);

        // Paso 4: Crear categorías
        Category::truncate();
        Category::factory()->count(8)->create();

        // Paso 5: Crear inventarios, reportes y tareas
        Inventory::truncate();
        Report::truncate();
        Task::truncate();
        $faker = Faker::create();
        User::all()->each(function ($user) use ($faker) {
            Inventory::factory()->count($faker->numberBetween(0, 1))->create(['user_id' => $user->id]);
            Report::factory()->count($faker->numberBetween(0, 1))->create(['user_id' => $user->id]);
            Task::factory()->count($faker->numberBetween(0, 2))->create(['user_id' => $user->id]);
        });

        echo "DatabaseSeeder finalizado.\n";
    }
}