<?php

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    // Crear roles usados en la aplicación
    Role::create(['name' => 'SuperUsuario', 'guard_name' => 'web']);
    Role::create(['name' => 'Administrador', 'guard_name' => 'web']);
    Role::create(['name' => 'Cajero', 'guard_name' => 'web']);
    Role::create(['name' => 'Inventario', 'guard_name' => 'web']);
});

it('prevents_administrator_creating_superuser', function () {
    // Crear usuario administrador
    $admin = User::factory()->create();
    $admin->assignRole('Administrador');

    $response = actingAs($admin)->post(route('users.store'), [
        'name' => 'New Super',
        'email' => 'newsuper@example.test',
        'password' => 'password',
        'role' => 'SuperUsuario',
    ]);

    $response->assertSessionHas('error');
    $this->assertDatabaseMissing('users', ['email' => 'newsuper@example.test']);
});

it('allows_superuser_creating_any_role', function () {
    $super = User::factory()->create();
    $super->assignRole('SuperUsuario');

    $response = actingAs($super)->post(route('users.store'), [
        'name' => 'New Admin',
        'email' => 'newadmin@example.test',
        'password' => 'password',
        'role' => 'Administrador',
    ]);

    $response->assertRedirect(route('users.index'));
    $this->assertDatabaseHas('users', ['email' => 'newadmin@example.test']);

    $newUser = App\Models\User::where('email', 'newadmin@example.test')->first();
    expect($newUser->hasRole('Administrador'))->toBeTrue();
});
