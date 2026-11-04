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

            $view->with(
                'navPendientes',
                $user && $user->isAdmin() ? EquipmentRequest::pendientes()->count() : 0
            );
        });
    }
}
