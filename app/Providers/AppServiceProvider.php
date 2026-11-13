<?php

namespace App\Providers;

use App\Models\EquipmentRequest;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Contador del aviso de la barra superior (SDD §20.4): una sola consulta
        // agregada, ejecutada únicamente para el administrador.
        View::composer('layouts.app', function ($view) {
            $user = auth()->user();
            $administrador = $user && $user->isAdmin();

            $view->with([
                'navPendientes' => $administrador ? EquipmentRequest::pendientes()->count() : 0,
                'navNotificaciones' => $administrador
                    ? EquipmentRequest::with('category:id,name')->pendientes()->latest()->limit(5)->get()
                    : EquipmentRequest::with('category:id,name')->where('user_id', $user?->id)->latest()->limit(5)->get(),
            ]);
        });
    }
}
