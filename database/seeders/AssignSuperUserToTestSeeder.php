<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AssignSuperUserToTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Asegurar que el rol exista
        $role = Role::firstOrCreate(['name' => 'SuperUsuario', 'guard_name' => 'web']);

        // Email objetivo para el usuario de pruebas
        $email = 'test@user.test';

        // Buscar o crear el usuario de pruebas
        $user = User::where('email', $email)->first();

        if (! $user) {
            $user = User::create([
                'name' => 'Test User',
                'email' => $email,
                'password' => bcrypt('password'),
            ]);
            $this->command->info("Usuario de pruebas creado: {$email} / password: password");
        }

        // Asignar rol SuperUsuario
        $user->assignRole($role->name);

        // Sincronizar todos los permisos existentes al rol SuperUsuario
        $allPermissions = Permission::all();
        if ($allPermissions->isNotEmpty()) {
            $role->syncPermissions($allPermissions);
            $this->command->info('Se asignaron ' . $allPermissions->count() . " permisos al rol SuperUsuario");
        } else {
            $this->command->info('No se encontraron permisos para asignar al rol SuperUsuario');
        }

        $this->command->info("Rol 'SuperUsuario' asignado a: {$email}");
    }
}
