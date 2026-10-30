<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('home'))->name('home');

// Catálogo público de servicios
Route::get('/servicios', [ServiceController::class, 'index'])->name('servicios.index');

// Autenticación: solo para visitantes sin sesión
Route::middleware('guest')->group(function () {
    Route::get('/registro', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/registro', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    // Máximo 5 intentos por minuto contra fuerza bruta
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// CRUD de servicios: solo administradores
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/servicios/crear', [ServiceController::class, 'create'])->name('servicios.create');
    Route::post('/servicios', [ServiceController::class, 'store'])->name('servicios.store');
    Route::get('/servicios/{servicio}/editar', [ServiceController::class, 'edit'])->name('servicios.edit');
    Route::put('/servicios/{servicio}', [ServiceController::class, 'update'])->name('servicios.update');
    Route::delete('/servicios/{servicio}', [ServiceController::class, 'destroy'])->name('servicios.destroy');
});