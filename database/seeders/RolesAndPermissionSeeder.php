<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()['cache']->forget('spatie.permission.cache');


        Permission::create(['name' => 'listar usuarios']);
        Permission::create(['name' => 'crear usuarios']);
        Permission::create(['name' => 'actualizar usuarios']);
        Permission::create(['name' => 'eliminar usuarios']);


        $propietario = Role::create(['name' => 'propietario']);
        $presidente = Role::create(['name' => 'presidente']);
        $tesorero = Role::create(['name' => 'tesorero']);
        $comisario = Role::create(['name' => 'comisario']);
        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo(Permission::all());


        $user = User::create([
            'name' => 'Cristian lira perez',
            'phone' => 7224738425,
            'email' => 'al221711754@gmail.com',
            'password' => Hash::make(12345678),
            // 'property_id' => 1
        ]);

        $user->assignRole($admin);
    }

}
