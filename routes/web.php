<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\VentasController;
use App\Http\Controllers\ProductosController;
use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\ReportesController;
use App\Http\Controllers\CortesController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

//Sección de Ventas
Route::get('ventas/consultas', [VentasController::class, 'consultas'])->name('ventas.consulta');
Route::get('ventas/detalles', [VentasController::class, 'detalles'])->name('ventas.detalles');
Route::get('ventas/devoluciones', [VentasController::class, 'devoluciones'])->name('ventas.devoluciones');

//Consulta de Productos
Route::get('productos/consultas', [ProductosController::class, 'consultas'])->name('productos.consulta');
//Creación
Route::get('productos/creacion', [ProductosController::class, 'creacion'])->name('productos.creacion');
Route::post('productos', [ProductosController::class, 'crear'])->name('productos.crear');
//Edición de Productos
Route::get('productos/{id}/edicion', [ProductosController::class, 'edicion'])->name('productos.edicion');
Route::put('productos/{id}/edicion', [ProductosController::class, 'actualizar'])->name('productos.actualizar');
//Eliminación de Productos
Route::delete('productos/{id}', [ProductosController::class, 'eliminar'])->name('productos.eliminar');

//Sección de Usuarios
Route::get('usuarios/creacion', [UsuariosController::class, 'creacion'])->name('usuarios.creacion');
Route::get('usuarios/consultas', [UsuariosController::class, 'consultas'])->name('usuarios.consulta');
Route::get('usuarios/edicion', [UsuariosController::class, 'edicion'])->name('usuarios.edicion');

//Sección de Roles
Route::get('roles/creacion', [RolesController::class, 'creacion'])->name('roles.creacion');
Route::get('roles/consultas', [RolesController::class, 'consultas'])->name('roles.consulta');
Route::get('roles/edicion', [RolesController::class, 'edicion'])->name('roles.edicion');

//Sección de Reportes
Route::get('reportes/creacion', [ReportesController::class, 'creacion'])->name('reportes.creacion');
Route::get('reportes/consultas', [ReportesController::class, 'consultas'])->name('reportes.consulta');
Route::get('reportes/detalles', [ReportesController::class, 'detalles'])->name('reportes.detalles');

//Sección de Cortes de caja
Route::get('cortes/creacion', [CortesController::class, 'creacion'])->name('cortes.creacion');
Route::get('cortes/consultas', [CortesController::class, 'consultas'])->name('cortes.consulta');
Route::get('cortes/detalles', [CortesController::class, 'detalles'])->name('cortes.detalles');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
