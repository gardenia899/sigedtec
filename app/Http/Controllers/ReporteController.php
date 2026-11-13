<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Equipment;
use App\Models\EquipmentRequest;
use App\Models\Office;
use App\Models\RequestItem;
use App\Models\StatusHistory;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ReporteController extends Controller
{
    public function inventario(Request $request): View
    {
        return view('reportes.inventario', [
            'equipos' => $this->inventarioQuery($request)->paginate(20)->withQueryString(),
            'categorias' => Category::orderBy('name')->get(['id', 'name']),
            'filtros' => $request->only(['categoria', 'estado', 'q']),
        ]);
    }

    public function inventarioPdf(Request $request): Response
    {
        return $this->pdf('inventario', $this->inventarioQuery($request)->get(), 'inventario', 'landscape');
    }

    public function solicitudes(Request $request): View
    {
        return view('reportes.solicitudes', [
            'solicitudes' => $this->solicitudesQuery($request)->paginate(20)->withQueryString(),
            'oficinas' => Office::orderBy('name')->get(['id', 'name']),
            'filtros' => $request->only(['estado', 'oficina']),
        ]);
    }

    public function solicitudesPdf(Request $request): Response
    {
        return $this->pdf('solicitudes', $this->solicitudesQuery($request)->get(), 'solicitudes');
    }

    public function asignaciones(Request $request): View
    {
        return view('reportes.asignaciones', [
            'items' => $this->asignacionesQuery($request)->paginate(20)->withQueryString(),
            'oficinas' => Office::orderBy('name')->get(['id', 'name']),
            'filtros' => $request->only(['oficina', 'desde', 'hasta']),
        ]);
    }

    public function asignacionesPdf(Request $request): Response
    {
        return $this->pdf('asignaciones', $this->asignacionesQuery($request)->get(), 'asignaciones', 'landscape');
    }

    public function movimientos(Request $request): View
    {
        return view('reportes.movimientos', [
            'movimientos' => $this->movimientosQuery($request)->paginate(25)->withQueryString(),
            'filtros' => $request->only(['desde', 'hasta']),
        ]);
    }

    public function movimientosPdf(Request $request): Response
    {
        return $this->pdf('movimientos', $this->movimientosQuery($request)->get(), 'movimientos');
    }

    private function inventarioQuery(Request $request): Builder
    {
        return Equipment::with('category:id,name')
            ->select(['id', 'patrimonial_code', 'category_id', 'brand', 'model', 'serial_number', 'status'])
            ->when($request->filled('categoria'), fn ($query) => $query->where('category_id', $request->categoria))
            ->when($request->filled('estado'), fn ($query) => $query->where('status', $request->estado))
            ->when($request->filled('q'), function ($query) use ($request) {
                $q = $request->string('q')->toString();
                $query->where(function ($sub) use ($q) {
                    $sub->where('patrimonial_code', 'ilike', "%{$q}%")
                        ->orWhere('brand', 'ilike', "%{$q}%")
                        ->orWhere('model', 'ilike', "%{$q}%");
                });
            })
            ->orderBy('patrimonial_code');
    }

    private function solicitudesQuery(Request $request): Builder
    {
        return EquipmentRequest::with(['office:id,name', 'category:id,name', 'user:id,name'])
            ->when($request->filled('estado'), fn ($query) => $query->where('status', $request->estado))
            ->when($request->filled('oficina'), fn ($query) => $query->where('office_id', $request->oficina))
            ->orderByDesc('created_at');
    }

    private function asignacionesQuery(Request $request): Builder
    {
        return RequestItem::with([
            'request:id,office_id,user_id,category_id',
            'request.office:id,name',
            'request.category:id,name',
            'request.user:id,name',
            'equipment:id,patrimonial_code,brand,model',
            'assignedBy:id,name',
        ])
            ->when($request->filled('oficina'), fn ($query) => $query->whereHas('request', fn ($sub) => $sub->where('office_id', $request->oficina)))
            ->when($request->filled('desde'), fn ($query) => $query->whereDate('created_at', '>=', $request->desde))
            ->when($request->filled('hasta'), fn ($query) => $query->whereDate('created_at', '<=', $request->hasta))
            ->orderByDesc('created_at');
    }

    private function movimientosQuery(Request $request): Builder
    {
        return StatusHistory::with(['equipment:id,patrimonial_code', 'user:id,name'])
            ->select(['id', 'equipment_id', 'user_id', 'from_status', 'to_status', 'reason', 'notes', 'created_at'])
            ->when($request->filled('desde'), fn ($query) => $query->whereDate('created_at', '>=', $request->desde))
            ->when($request->filled('hasta'), fn ($query) => $query->whereDate('created_at', '<=', $request->hasta))
            ->orderByDesc('created_at');
    }

    private function pdf(string $tipo, $registros, string $archivo, string $orientacion = 'portrait'): Response
    {
        return Pdf::loadView('reportes.pdf', [
            'tipo' => $tipo,
            'registros' => $registros,
            'generado' => now(),
        ])
            ->setPaper('a4', $orientacion)
            ->download('reporte-'.$archivo.'-'.now()->format('Ymd-His').'.pdf');
    }
}
