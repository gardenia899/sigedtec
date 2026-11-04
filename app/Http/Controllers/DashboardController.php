<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Equipment;
use App\Models\EquipmentRequest;
use App\Models\StatusHistory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

/**
 * Panel principal con indicadores reales de PostgreSQL (SDD §14-M8, RN-22).
 * El administrador ve cifras globales; el solicitante ve lo suyo.
 */
class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            // Una sola agregación por tabla en vez de una consulta por estado.
            $conteoEquipos = Equipment::selectRaw('status, count(*) as total')
                ->groupBy('status')
                ->pluck('total', 'status');

            $conteoSolicitudes = EquipmentRequest::selectRaw('status, count(*) as total')
                ->groupBy('status')
                ->pluck('total', 'status');

            return view('dashboard.admin', [
                'disponibles' => (int) ($conteoEquipos['disponible'] ?? 0),
                'asignados' => (int) ($conteoEquipos['asignado'] ?? 0),
                'mantenimiento' => (int) ($conteoEquipos['mantenimiento'] ?? 0),
                'pendientes' => (int) ($conteoSolicitudes['pendiente'] ?? 0),
                'totalSolicitudes' => (int) $conteoSolicitudes->sum(),
                'solicitudesRecientes' => EquipmentRequest::with(['office:id,name', 'category:id,name', 'user:id,name'])
                    ->latest()
                    ->limit(5)
                    ->get(),
                'movimientosRecientes' => StatusHistory::with(['equipment:id,patrimonial_code', 'user:id,name'])
                    ->latest('created_at')
                    ->limit(8)
                    ->get(),
                // Solo las categorías con más equipos: el panel muestra las seis primeras.
                'porCategoria' => Category::withCount(['equipments' => fn ($q) => $q->withoutTrashed()])
                    ->orderByDesc('equipments_count')
                    ->limit(6)
                    ->get(['id', 'name']),
            ]);
        }

        return view('dashboard.solicitante', [
            'usuario' => $user,
            'solicitudes' => EquipmentRequest::with(['category:id,name', 'office:id,name'])
                ->where('user_id', $user->id)
                ->latest()
                ->limit(8)
                ->get(),
            'pendientes' => EquipmentRequest::where('user_id', $user->id)->where('status', 'pendiente')->count(),
            'porEstado' => EquipmentRequest::where('user_id', $user->id)
                ->selectRaw('status, count(*) as total')
                ->groupBy('status')
                ->pluck('total', 'status'),
            'equiposOficina' => Equipment::whereHas('activeAssignment.request', fn ($q) => $q->where('office_id', $user->office_id))
                ->count(),
        ]);
    }
}
