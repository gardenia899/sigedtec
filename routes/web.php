<?php

use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EquipoController;
use App\Http\Controllers\OficinaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SolicitudController;
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

/*
|--------------------------------------------------------------------------
| Módulo 4 — Inventario / Equipos (solo Soporte Técnico)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('inventario')->name('equipos.')->group(function () {
    Route::get('/', [EquipoController::class, 'index'])->name('index');
    Route::get('/crear', [EquipoController::class, 'create'])->name('create');
    Route::post('/', [EquipoController::class, 'store'])->name('store');
    Route::get('/{equipo}/editar', [EquipoController::class, 'edit'])->name('edit');
    Route::put('/{equipo}', [EquipoController::class, 'update'])->name('update');
    Route::delete('/{equipo}', [EquipoController::class, 'destroy'])->name('destroy');
    Route::patch('/{equipo}/restaurar', [EquipoController::class, 'restore'])->name('restore');
    Route::patch('/{equipo}/estado', [EquipoController::class, 'cambiarEstado'])->name('estado');
});

/*
|--------------------------------------------------------------------------
| Módulo 5 — Solicitudes (Solicitante)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:solicitante'])->prefix('solicitudes')->name('solicitudes.')->group(function () {
    Route::get('/', [SolicitudController::class, 'index'])->name('index');
    Route::get('/crear', [SolicitudController::class, 'create'])->name('create');
    Route::post('/', [SolicitudController::class, 'store'])->name('store');
    Route::get('/{solicitud}', [SolicitudController::class, 'show'])->name('show');
    Route::post('/{solicitud}/cancelar', [SolicitudController::class, 'cancelar'])->name('cancelar');
});

require __DIR__.'/auth.php';