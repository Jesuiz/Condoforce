<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Condominium;
use App\Models\Occupation;
use App\Models\Inventory;
use App\Models\Report;
use App\Models\Task;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Crear condominios
        Condominium::factory()->count(6)->create();
        Condominium::factory()->inactive()->count(1)->create();
        Condominium::factory()->active()->count(3)->create();

        // Crear ocupaciones
        Occupation::factory()->count(7)->create();

        // Crear usuario Superadmin
        $condominiums = Condominium::all();
        $occupation = Occupation::all();
        DB::table('users')->insert([
            'name' => 'Jesús Ruiz',
            'email' => 'jesuizmail@gmail.com',
            'password' => Hash::make('123'),
            'country' => 'VE',
            'doc_type' => 'CE',
            'document' => '005180167',
            'cellphone' => '935035069',
            'address' => 'Edif. 2, Dpto. 503, Cond. Los Pinos - El Agustino',
            'profile_img' => 'https://ui-avatars.com/api/?name=Jesus+Ruiz',
            'condominium_id' => 1,
            'occupation_id' => 7,
        ]);

        // Crear usuarios adicionales
        User::factory(9)->create(['condominium_id' => fn() => $condominiums->random()->id]);

        // Crear inventarios, reportes y tareas
        $faker = Faker::create();
        User::all()->each(function ($user) use ($faker) {
            Inventory::factory()->count($faker->numberBetween(0, 20))->create(['user_id' => $user->id]);
            
            $reports = Report::factory()->count($faker->numberBetween(0, 1))->create(['user_id' => $user->id]);

            if ($reports->isNotEmpty()) {
                Task::factory()->count($faker->numberBetween(0, 2))->create([
                    'user_id' => $user->id,
                    'report_id' => $reports->random()->id,
                ]);
            }
        });

        echo "DatabaseSeeder finalizado.\n";
    }
}