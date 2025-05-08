<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('ventas/consultasVenta', function () {
    return view('ventas.consulta');
})->name('ventas.consulta');

Route::get('ventas/detallesVenta', function () {
    return view('ventas.detalles');
})->name('ventas.detalles');

Route::get('ventas/devolucionesVenta', function () {
    return view('ventas.devoluciones');
})->name('ventas.devoluciones');

Route::get('productos/consultasProducto', function () {
    return view('productos.consulta');
})->name('productos.consulta');

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
