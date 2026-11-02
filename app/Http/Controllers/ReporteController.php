<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Equipment;
use App\Models\EquipmentRequest;
use App\Models\Office;
use App\Models\RequestItem;
use App\Models\StatusHistory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ReporteController extends Controller
{
    public function inventario(Request $request): View
    {
        $query = Equipment::with('category')->orderBy('patrimonial_code');

        if ($request->filled('categoria')) {
            $query->where('category_id', $request->categoria);
        }

        if ($request->filled('estado')) {
            $query->where('status', $request->estado);
        }

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('patrimonial_code', 'ilike', "%{$q}%")
                    ->orWhere('brand', 'ilike', "%{$q}%")
                    ->orWhere('model', 'ilike', "%{$q}%");
            });
        }

        return view('reportes.inventario', [
            'equipos' => $query->paginate(20)->withQueryString(),
            'categorias' => Category::orderBy('name')->get(),
            'filtros' => $request->only(['categoria', 'estado', 'q']),
        ]);
    }

    public function solicitudes(Request $request): View
    {
        $query = EquipmentRequest::with(['office', 'category', 'user'])
            ->orderByDesc('created_at');

        if ($request->filled('estado')) {
            $query->where('status', $request->estado);
        }

        if ($request->filled('oficina')) {
            $query->where('office_id', $request->oficina);
        }

        return view('reportes.solicitudes', [
            'solicitudes' => $query->paginate(20)->withQueryString(),
            'oficinas' => Office::orderBy('name')->get(),
            'filtros' => $request->only(['estado', 'oficina']),
        ]);
    }

    public function asignaciones(Request $request): View
    {
        $query = RequestItem::with(['request.office', 'request.category', 'request.user', 'equipment', 'assignedBy'])
            ->orderByDesc('created_at');

        if ($request->filled('oficina')) {
            $query->whereHas('request', fn ($q) => $q->where('office_id', $request->oficina));
        }

        if ($request->filled('desde')) {
            $query->whereDate('created_at', '>=', $request->desde);
        }

        if ($request->filled('hasta')) {
            $query->whereDate('created_at', '<=', $request->hasta);
        }

        return view('reportes.asignaciones', [
            'items' => $query->paginate(20)->withQueryString(),
            'oficinas' => Office::orderBy('name')->get(),
            'filtros' => $request->only(['oficina', 'desde', 'hasta']),
        ]);
    }

    public function movimientos(Request $request): View
    {
        $query = StatusHistory::with(['equipment:id,patrimonial_code,brand,model', 'user:id,name', 'request:id'])
            ->orderByDesc('created_at');

        if ($request->filled('desde')) {
            $query->whereDate('created_at', '>=', $request->desde);
        }

        if ($request->filled('hasta')) {
            $query->whereDate('created_at', '<=', $request->hasta);
        }

        return view('reportes.movimientos', [
            'movimientos' => $query->paginate(25)->withQueryString(),
            'filtros' => $request->only(['desde', 'hasta']),
        ]);
    }
}
