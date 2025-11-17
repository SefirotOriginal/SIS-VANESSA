<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\VentasController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\LaboratoryController;
use App\Http\Controllers\ReportesController;
use App\Http\Controllers\CortesController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\BatchController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
})->name('home');

//Sección de Ventas
Route::get('/buscar-producto/{codigo}', [VentasController::class, 'ventas.buscarProducto']);
Route::post('ventas/consultas', [VentasController::class, 'crear'])->name('ventas.crear');
Route::get('ventas/consultas', [VentasController::class, 'consultas'])->name('ventas.consulta');
Route::get('ventas/detalles/{id}', [VentasController::class, 'detalles'])->name('ventas.detalles');
// Muestra la página para confirmar la devolución
Route::get('ventas/devoluciones/{id}', [VentasController::class, 'devoluciones'])->name('ventas.devoluciones');
// Procesa la devolución (la acción de devolver)
Route::post('ventas/devoluciones/{id}', [VentasController::class, 'procesarDevolucion'])->name('ventas.devolucion.procesar');


//Creación de products
Route::resource('products', ProductController::class);
//GONZALO: Probaré a dejar el controlador en manos de una sola linea general de Route.
/*
Route::get('products/create', [ProductController::class, 'create'])->name('products.create');
Route::post('products', [ProductController::class, 'store'])->name('products.store');
//Consulta de products
Route::get('products/index', [ProductController::class, 'index'])->name('products.index');
Route::get('products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
Route::put('products/{id}', [ProductController::class, 'update'])->name('products.update');
Route::delete('products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');
*/

//Lotes
Route::get('batches/index', [BatchController::class, 'index'])->name('batches.index');
Route::get('batches/create', [BatchController::class, 'create'])->name('batches.create');
Route::post('batches', [BatchController::class, 'store'])->name('batches.store');
Route::get('batches/{batch}/edit', [BatchController::class, 'edit'])->name('batches.edit');
Route::put('batches/{batch}', [BatchController::class, 'update'])->name('batches.update');
Route::delete('batches/{batch}', [BatchController::class, 'destroy'])->name('batches.destroy');

//Categorías
Route::resource('categories', CategoryController::class);
//GONZALO: Probaré a dejar el controlador en manos de una sola linea general de Route.
/*
Route::get('categories/index', [CategoryController::class, 'index'])->name('categories.index');
Route::get('categories/create', [CategoryController::class, 'create'])->name('categories.create');
Route::post('categories', [CategoryController::class, 'store'])->name('categories.store');
Route::get('categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
Route::put('categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
Route::delete('categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
*/

//Laboratorios
Route::get('laboratories/index', [LaboratoryController::class, 'index'])->name('laboratories.index');
Route::get('laboratories/create', [LaboratoryController::class, 'create'])->name('laboratories.create');
Route::post('laboratories', [LaboratoryController::class, 'store'])->name('laboratories.store');
Route::get('laboratories/{laboratory}/edit', [LaboratoryController::class, 'edit'])->name('laboratories.edit');
Route::put('laboratories/{laboratory}', [LaboratoryController::class, 'update'])->name('laboratories.update');
Route::delete('laboratories/{laboratory}', [LaboratoryController::class, 'destroy'])->name('laboratories.destroy');

// Presentaciones
Route::get('presentations/index', [App\Http\Controllers\PresentationController::class, 'index'])->name('presentations.index');
Route::get('presentations/create', [App\Http\Controllers\PresentationController::class, 'create'])->name('presentations.create');
Route::post('presentations', [App\Http\Controllers\PresentationController::class, 'store'])->name('presentations.store');
Route::get('presentations/{presentation}/edit', [App\Http\Controllers\PresentationController::class, 'edit'])->name('presentations.edit');
Route::put('presentations/{presentation}', [App\Http\Controllers\PresentationController::class, 'update'])->name('presentations.update');
Route::delete('presentations/{presentation}', [App\Http\Controllers\PresentationController::class, 'destroy'])->name('presentations.destroy');

//Creación de Usuarios
Route::get('users/create', [UsuariosController::class, 'create'])->name('users.create');
Route::post('users', [UsuariosController::class, 'store'])->name('users.store');
//Consulta de Usuarios
Route::get('users/index', [UsuariosController::class, 'index'])->name('users.index');
Route::get('users/{id}/edit', [UsuariosController::class, 'edit'])->name('users.edit');
Route::put('users/{id}/update', [UsuariosController::class, 'update'])->name('users.update');
//Eliminación de Usuarios
Route::delete('users/{id}', [UsuariosController::class, 'destroy'])->name('users.destroy');
//Edición de Usuarios
Route::get('/perfil', [UsuariosController::class, 'perfil'])->name('usuarios.perfil');


// Route::get('usuarios/{id}/edicion', [UsuariosController::class, 'edicion'])->name('usuarios.edicion');
// Route::put('usuarios/{id}/edicion', [UsuariosController::class, 'actualizar'])->name('usuarios.actualizar');
// //Eliminación de Usuarios
// Route::delete('usuarios/{id}/edicion', [UsuariosController::class, 'eliminar'])->name('usuarios.eliminar');


//Sección de Roles
// Route::get('roles/creacion', [RolesController::class, 'creacion'])->name('roles.creacion');
// Route::get('roles/consultas', [RolesController::class, 'consultas'])->name('roles.consulta');
// Route::get('roles/edicion', [RolesController::class, 'edicion'])->name('roles.edicion');
Route::get('roles/create', [RoleController::class, 'create'])->name('roles.create');
Route::post('roles', [RoleController::class, 'store'])->name('roles.store');
Route::get('roles/index', [RoleController::class, 'index'])->name('roles.index');
Route::get('roles/{id}/edit', [RoleController::class, 'edit'])->name('roles.edit');
Route::put('roles/{id}/edit', [RoleController::class, 'update'])->name('roles.update');
Route::delete('roles/{id}', [RoleController::class, 'destroy'])->name('roles.destroy');

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
