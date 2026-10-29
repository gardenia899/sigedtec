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
            return view('dashboard.admin', [
                'disponibles' => Equipment::disponibles()->count(),
                'asignados' => Equipment::where('status', 'asignado')->count(),
                'mantenimiento' => Equipment::where('status', 'mantenimiento')->count(),
                'pendientes' => EquipmentRequest::pendientes()->count(),
                'solicitudesRecientes' => EquipmentRequest::with(['office:id,name', 'category:id,name', 'user:id,name'])
                    ->latest()
                    ->limit(5)
                    ->get(),
                'movimientosRecientes' => StatusHistory::with(['equipment:id,patrimonial_code', 'user:id,name'])
                    ->latest('created_at')
                    ->limit(8)
                    ->get(),
                'porCategoria' => Category::withCount(['equipments' => fn ($q) => $q->withoutTrashed()])
                    ->get(),
            ]);
        }

        return view('dashboard.solicitante', [
            'solicitudes' => EquipmentRequest::with(['category:id,name', 'office:id,name'])
                ->where('user_id', $user->id)
                ->latest()
                ->limit(8)
                ->get(),
            'pendientes' => EquipmentRequest::where('user_id', $user->id)->where('status', 'pendiente')->count(),
            'equiposOficina' => Equipment::whereHas('activeAssignment.request', fn ($q) => $q->where('office_id', $user->office_id))
                ->count(),
        ]);
    }
}