<?php

use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OficinaController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rutas web — SIGEDTEC (SDD §18)
|--------------------------------------------------------------------------
| El mapa completo (equipos, solicitudes, despacho, reportes) se registra
| módulo por módulo según la planificación de fases (SDD §25.2).
*/

Route::get('/', function () {
    return Auth::check() ? redirect()->route('dashboard') : redirect()->route('login');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Módulo 2 — Oficinas (solo Soporte Técnico)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('oficinas')->name('oficinas.')->group(function () {
    Route::get('/', [OficinaController::class, 'index'])->name('index');
    Route::get('/crear', [OficinaController::class, 'create'])->name('create');
    Route::post('/', [OficinaController::class, 'store'])->name('store');
    Route::get('/{office}/editar', [OficinaController::class, 'edit'])->name('edit');
    Route::put('/{office}', [OficinaController::class, 'update'])->name('update');
    Route::patch('/{office}/activar-desactivar', [OficinaController::class, 'toggle'])->name('toggle');
});

/*
|--------------------------------------------------------------------------
| Módulo 3 — Categorías (solo Soporte Técnico)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('categorias')->name('categorias.')->group(function () {
    Route::get('/', [CategoriaController::class, 'index'])->name('index');
    Route::get('/crear', [CategoriaController::class, 'create'])->name('create');
    Route::post('/', [CategoriaController::class, 'store'])->name('store');
    Route::get('/{categoria}/editar', [CategoriaController::class, 'edit'])->name('edit');
    Route::put('/{categoria}', [CategoriaController::class, 'update'])->name('update');
    Route::patch('/{categoria}/activar-desactivar', [CategoriaController::class, 'toggle'])->name('toggle');
});

require __DIR__.'/auth.php';