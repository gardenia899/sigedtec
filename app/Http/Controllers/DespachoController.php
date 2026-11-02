<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\EquipmentRequest;
use App\Models\RequestItem;
use App\Models\StatusHistory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DespachoController extends Controller
{
    public function index(Request $request): View
    {
        $estado = $request->get('estado', 'pendiente');

        $solicitudes = EquipmentRequest::with(['office:id,name', 'category:id,name', 'user:id,name'])
            ->when($estado !== 'todas', fn ($q) => $q->where('status', $estado))
            ->orderByDesc('created_at')
            ->paginate(15);

        $contadores = [
            'pendiente' => EquipmentRequest::where('status', 'pendiente')->count(),
            'aprobada' => EquipmentRequest::where('status', 'aprobada')->count(),
            'rechazada' => EquipmentRequest::where('status', 'rechazada')->count(),
            'cancelada' => EquipmentRequest::where('status', 'cancelada')->count(),
        ];

        return view('despacho.index', compact('solicitudes', 'estado', 'contadores'));
    }

    public function formAprobar(EquipmentRequest $solicitud): View
    {
        if ($solicitud->status !== 'pendiente') {
            abort(409, 'Esta solicitud ya fue procesada.');
        }

        $equipos = Equipment::with('category')
            ->where('category_id', $solicitud->category_id)
            ->where('status', 'disponible')
            ->whereNull('deleted_at')
            ->orderBy('patrimonial_code')
            ->get();

        return view('despacho.aprobar', compact('solicitud', 'equipos'));
    }

    public function aprobar(Request $request, EquipmentRequest $solicitud): RedirectResponse
    {
        if ($solicitud->status !== 'pendiente') {
            return redirect()->route('admin.solicitudes.index')->with('error', 'Esta solicitud ya fue procesada.');
        }

        $request->validate([
            'equipos' => ['required', 'array', 'min:' . $solicitud->quantity, 'max:' . $solicitud->quantity],
            'equipos.*' => ['integer', 'exists:equipment,id'],
        ]);

        try {
            DB::transaction(function () use ($request, $solicitud) {
                $solicitudBloqueada = EquipmentRequest::where('id', $solicitud->id)->lockForUpdate()->first();

                if (!$solicitudBloqueada || $solicitudBloqueada->status !== 'pendiente') {
                    abort(409, 'Esta solicitud ya fue procesada.');
                }

                $equiposIds = array_values(array_unique($request->input('equipos')));

                if (count($equiposIds) !== (int) $solicitud->quantity) {
                    abort(422, 'Debes seleccionar exactamente la cantidad solicitada.');
                }

                $equipos = Equipment::whereIn('id', $equiposIds)
                    ->where('category_id', $solicitud->category_id)
                    ->where('status', 'disponible')
                    ->whereNull('deleted_at')
                    ->lockForUpdate()
                    ->get();

                if ($equipos->count() !== count($equiposIds)) {
                    abort(422, 'Algunos equipos ya no están disponibles o no corresponden a la categoría.');
                }

                foreach ($equipos as $equipo) {
                    $equipo->update(['status' => 'asignado']);

                    RequestItem::create([
                        'request_id' => $solicitud->id,
                        'equipment_id' => $equipo->id,
                        'assigned_by' => $request->user()->id,
                        'status_before' => 'disponible',
                        'status_after' => 'asignado',
                    ]);

                    StatusHistory::create([
                        'equipment_id' => $equipo->id,
                        'request_id' => $solicitud->id,
                        'user_id' => $request->user()->id,
                        'from_status' => 'disponible',
                        'to_status' => 'asignado',
                        'reason' => 'asignacion',
                        'notes' => 'Asignado por despacho de solicitud #' . $solicitud->id,
                    ]);
                }

                $solicitud->update([
                    'status' => 'aprobada',
                    'processed_by' => $request->user()->id,
                    'processed_at' => now(),
                ]);
            });
        } catch (\Throwable $e) {
            return back()->with('error', 'No se pudo aprobar la solicitud. Intenta de nuevo.');
        }

        return redirect()->route('admin.solicitudes.index')->with('success', 'Solicitud aprobada y equipos asignados correctamente.');
    }

    public function formRechazar(EquipmentRequest $solicitud): View
    {
        if ($solicitud->status !== 'pendiente') {
            abort(409, 'Esta solicitud ya fue procesada.');
        }

        return view('despacho.rechazar', compact('solicitud'));
    }

    public function rechazar(Request $request, EquipmentRequest $solicitud): RedirectResponse
    {
        if ($solicitud->status !== 'pendiente') {
            return redirect()->route('admin.solicitudes.index')->with('error', 'Esta solicitud ya fue procesada.');
        }

        $request->validate([
            'admin_observation' => ['required', 'string', 'min:5', 'max:500'],
        ]);

        $solicitud->update([
            'status' => 'rechazada',
            'admin_observation' => $request->admin_observation,
            'processed_by' => $request->user()->id,
            'processed_at' => now(),
        ]);

        return redirect()->route('admin.solicitudes.index')->with('success', 'Solicitud rechazada correctamente.');
    }
}
