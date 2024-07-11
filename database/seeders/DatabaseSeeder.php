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
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Condominium::factory()->count(1)->create();
        Condominium::factory()->inactive()->create();
        Condominium::factory()->active()->count(2)->create();

        DB::table('users')->insert([
            'name' => 'Jesús Ruiz',
            'email' => 'jesuizmail@gmail.com',
            'password' => Hash::make('123'),
            'country' => 'VE',
            'doc_type' => 'CE',
            'document' => '005180167',
            'cellphone' => '935035069',
            'address' => 'Edif. 2, Dpto. 503, Cond. Los Pinos - El Agustino',
            'condominium_id' => 1,
        ]);

        User::factory(10)->create()->each(function ($user) use ($condominiums) {
            $user->condominium_id = $condominiums->random()->id;
            $user->save();
        });

        Category::factory()->count(5)->create();

        $faker = Faker::create();
        User::all()->each(function ($user) use ($faker) {
            Inventory::factory()->count($faker->numberBetween(1, 5))->create(['user_id' => $user->id]);
            Report::factory()->count($faker->numberBetween(0, 3))->create(['user_id' => $user->id]);
            Task::factory()->count($faker->numberBetween(0, 3))->create(['user_id' => $user->id]);
        });
    }
}