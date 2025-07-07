<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\File;

class RolesAndPermissions extends Seeder
{
    /**
     * Extract the fully qualified class name from a file.
     */
    private function getClassFullName($filePath)
    {
        $src = file_get_contents($filePath);
        $namespace = '';
        if (preg_match('/^namespace\s+(.+?);/m', $src, $matches)) {
            $namespace = $matches[1] . '\\';
        }
        if (preg_match('/^class\s+(\w+)/m', $src, $matches)) {
            return $namespace . $matches[1];
        }
        return null;
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $roles = [
            ['name' => 'SuperUsuario', 'guard_name' => 'web'],
            ['name' => 'Administrador', 'guard_name' => 'web'],
            ['name' => 'Cajero', 'guard_name' => 'web'],
            ['name' => 'Inventario', 'guard_name' => 'web'],
        ];

        foreach ($roles as $roleData) {
            Role::firstOrCreate($roleData);
        }

        $permissions = [];

        $controllerPath = app_path('Http/Controllers');
        $controllers = File::allFiles($controllerPath);

        foreach ($controllers as $controller) {
            $className = $this->getClassFullName($controller->getRealPath());

            if (!class_exists($className)) {
                continue;
            }

            $reflection = new \ReflectionClass($className);
            $baseName = strtolower(str_replace('Controller', '', $reflection->getShortName()));

            foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
                if ($method->class !== $className) continue;
                if (in_array($method->name, ['__construct'])) continue;

                $permissionName = "{$baseName}.{$method->name}";

                $permission = Permission::firstOrCreate(['name' => $permissionName]);
                $permissions[] = $permission->name;
            }
        }
        $superUsuario = Role::where('name', 'SuperUsuario')->first();
        $superUsuario->syncPermissions($permissions);

        $admin = Role::where('name', 'Administrador')->first();
        $admin->syncPermissions(array_filter($permissions, fn($p) => str_starts_with($p, 'user.') || str_starts_with($p, 'product.')));

        $cajero = Role::where('name', 'Cajero')->first();
        $cajero->syncPermissions(array_filter($permissions, fn($p) => str_starts_with($p, 'sale.') || str_ends_with($p, '.index') || str_ends_with($p, '.store')));

        $inventario = Role::where('name', 'Inventario')->first();
        $inventario->syncPermissions(array_filter($permissions, fn($p) => str_starts_with($p, 'product.') || str_starts_with($p, 'stock.')));

        $this->command->info('Roles and permissions seeded and assigned successfully.');
    }
}
