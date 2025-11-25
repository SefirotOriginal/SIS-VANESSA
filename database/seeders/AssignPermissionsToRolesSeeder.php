<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AssignPermissionsToRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $map = config('role_permissions.map', []);

        if (empty($map)) {
            $this->command->warn('role_permissions.map está vacío; no se asignarán permisos.');
            return;
        }

        $allPermissions = Permission::all()->pluck('name')->toArray();

        foreach ($map as $roleName => $patterns) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);

            // Si el patrón es '*' sincronizamos todo
            if (in_array('*', $patterns, true)) {
                $role->syncPermissions($allPermissions);
                $this->command->info("Rol {$roleName}: asignados " . count($allPermissions) . " permisos (todos).");
                continue;
            }

            $matched = [];

            foreach ($allPermissions as $perm) {
                foreach ($patterns as $pattern) {
                    // patrón tipo 'prefix.*' -> startsWith 'prefix.'
                    if (str_ends_with($pattern, '.*')) {
                        $prefix = substr($pattern, 0, -2);
                        if (str_starts_with($perm, $prefix . '.')) {
                            $matched[] = $perm;
                            break;
                        }
                        continue;
                    }

                    // exact match
                    if ($perm === $pattern) {
                        $matched[] = $perm;
                        break;
                    }
                }
            }

            $matched = array_values(array_unique($matched));

            if (!empty($matched)) {
                $role->syncPermissions($matched);
                $this->command->info("Rol {$roleName}: asignados " . count($matched) . " permisos.");
            } else {
                $this->command->warn("Rol {$roleName}: no se encontraron permisos que coincidan con los patrones.");
            }
        }
    }
}
