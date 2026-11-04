<?php

use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DespachoController;
use App\Http\Controllers\EquipoController;
use App\Http\Controllers\OficinaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\SolicitudController;
use App\Http\Controllers\UsuarioController;
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
    return Auth::check() ? redirect()->route('dashboard') : view('home');
})->name('home');

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
| Módulo 5 — Solicitudes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('solicitudes')->name('solicitudes.')->group(function () {
    Route::get('/', [SolicitudController::class, 'index'])->name('index')->middleware('role:solicitante');
    Route::get('/crear', [SolicitudController::class, 'create'])->name('create')->middleware('role:solicitante');
    Route::post('/', [SolicitudController::class, 'store'])->name('store')->middleware('role:solicitante');
    Route::get('/{solicitud}', [SolicitudController::class, 'show'])->name('show');
    Route::post('/{solicitud}/cancelar', [SolicitudController::class, 'cancelar'])->name('cancelar')->middleware('role:solicitante');
});

/*
|--------------------------------------------------------------------------
| Módulo 6 — Aprobación y despacho (Admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin/solicitudes')->name('admin.solicitudes.')->group(function () {
    Route::get('/', [DespachoController::class, 'index'])->name('index');
    Route::get('/{solicitud}/aprobar', [DespachoController::class, 'formAprobar'])->name('form');
    Route::post('/{solicitud}/aprobar', [DespachoController::class, 'aprobar'])->name('aprobar');
    Route::get('/{solicitud}/rechazar', [DespachoController::class, 'formRechazar'])->name('formRechazar');
    Route::post('/{solicitud}/rechazar', [DespachoController::class, 'rechazar'])->name('rechazar');
});

/*
|--------------------------------------------------------------------------
| Módulo 7 — Reportes (Admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('reportes')->name('reportes.')->group(function () {
    Route::get('/inventario', [ReporteController::class, 'inventario'])->name('inventario');
    Route::get('/solicitudes', [ReporteController::class, 'solicitudes'])->name('solicitudes');
    Route::get('/asignaciones', [ReporteController::class, 'asignaciones'])->name('asignaciones');
    Route::get('/movimientos', [ReporteController::class, 'movimientos'])->name('movimientos');
});

/*
|--------------------------------------------------------------------------
| Módulo 8 — Usuarios (Admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('usuarios')->name('usuarios.')->group(function () {
    Route::get('/', [UsuarioController::class, 'index'])->name('index');
    Route::get('/{usuario}/editar', [UsuarioController::class, 'edit'])->name('edit');
    Route::put('/{usuario}', [UsuarioController::class, 'update'])->name('update');
});

require __DIR__.'/auth.php';