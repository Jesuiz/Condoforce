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
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        // create Condominium permissions
        Permission::create(['name' => 'view-any Condominium']);
        Permission::create(['name' => 'view Condominium']);
        Permission::create(['name' => 'create Condominium']);
        Permission::create(['name' => 'update Condominium']);
        Permission::create(['name' => 'delete Condominium']);
        Permission::create(['name' => 'restore Condominium']);
        Permission::create(['name' => 'force-delete Condominium']);
        Permission::create(['name' => 'replicate Condominium']);
        Permission::create(['name' => 'reorder Condominium']);
        // create Inventory permissions
        Permission::create(['name' => 'view-any Inventory']);
        Permission::create(['name' => 'view Inventory']);
        Permission::create(['name' => 'create Inventory']);
        Permission::create(['name' => 'update Inventory']);
        Permission::create(['name' => 'delete Inventory']);
        Permission::create(['name' => 'restore Inventory']);
        Permission::create(['name' => 'force-delete Inventory']);
        Permission::create(['name' => 'replicate Inventory']);
        Permission::create(['name' => 'reorder Inventory']);
        // create Condominium permissions
        Permission::create(['name' => 'view-any Occupation']);
        Permission::create(['name' => 'view Occupation']);
        Permission::create(['name' => 'create Occupation']);
        Permission::create(['name' => 'update Occupation']);
        Permission::create(['name' => 'delete Occupation']);
        Permission::create(['name' => 'restore Occupation']);
        Permission::create(['name' => 'force-delete Occupation']);
        Permission::create(['name' => 'replicate Occupation']);
        Permission::create(['name' => 'reorder Occupation']);
        // create Report permissions
        Permission::create(['name' => 'view-any Report']);
        Permission::create(['name' => 'view Report']);
        Permission::create(['name' => 'create Report']);
        Permission::create(['name' => 'update Report']);
        Permission::create(['name' => 'delete Report']);
        Permission::create(['name' => 'restore Report']);
        Permission::create(['name' => 'force-delete Report']);
        Permission::create(['name' => 'replicate Report']);
        Permission::create(['name' => 'reorder Report']);
        // create Task permissions
        Permission::create(['name' => 'view-any Task']);
        Permission::create(['name' => 'view Task']);
        Permission::create(['name' => 'create Task']);
        Permission::create(['name' => 'update Task']);
        Permission::create(['name' => 'delete Task']);
        Permission::create(['name' => 'restore Task']);
        Permission::create(['name' => 'force-delete Task']);
        Permission::create(['name' => 'replicate Task']);
        Permission::create(['name' => 'reorder Task']);
        // create User permissions
        Permission::create(['name' => 'view-any User']);
        Permission::create(['name' => 'view User']);
        Permission::create(['name' => 'create User']);
        Permission::create(['name' => 'update User']);
        Permission::create(['name' => 'delete User']);
        Permission::create(['name' => 'restore User']);
        Permission::create(['name' => 'force-delete User']);
        Permission::create(['name' => 'replicate User']);
        Permission::create(['name' => 'reorder User']);
        
        // Crear roles y permisos
        $role1 = Role::create(['name' => 'administrador'])
        ->givePermissionTo([
            'view Inventory', 'create Inventory', 'update Inventory', 'delete Inventory', 'restore Inventory', 'replicate Inventory', 'reorder Inventory',
            'view Occupation', 'create Occupation', 'update Occupation', 'delete Occupation', 'restore Occupation', 'replicate Occupation', 'reorder Occupation',
            'view Report', 'create Report', 'update Report', 'delete Report', 'restore Report', 'replicate Report', 'reorder Report',
            'view Task', 'create Task', 'update Task', 'delete Task', 'restore Task', 'replicate Task', 'reorder Task',
            'view User', 'create User', 'update User', 'delete User', 'restore User', 'replicate User', 'reorder User'
        ]);
        $role2 = Role::create(['name' => 'empleado'])
        ->givePermissionTo([
            'view Inventory', 'create Inventory', 'update Inventory',
            'view Report', 'create Report', 'update Report',
            'view Task', 'update Task',
            'view User', 'create User', 'update User'
        ]);
        $role3 = Role::create(['name' => 'residente'])
        ->givePermissionTo([
            'view Report', 'create Report'
        ]);
        $role4 = Role::create(['name' => 'superadmin']);
        $role4->givePermissionTo(Permission::all());


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

        // Crear usuarios adicionales y asignar roles aleatorios
        User::factory(19)->create(['condominium_id' => fn() => $condominiums->random()->id])
        ->each(function ($user) use ($role1, $role2, $role3) {
            $randomRole = [$role1, $role2, $role3][array_rand([0, 1, 2])];
            $user->assignRole($randomRole);
        });

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