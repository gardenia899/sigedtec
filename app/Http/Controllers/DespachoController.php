<?php

namespace App\Http\Controllers;

use App\Http\Requests\AprobarSolicitudRequest;
use App\Http\Requests\RechazarSolicitudRequest;
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
            ->paginate(15)
            ->withQueryString();

        // Un solo recorrido de la tabla para los cuatro contadores (antes: cuatro COUNT).
        $conteos = EquipmentRequest::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $contadores = [
            'pendiente' => (int) ($conteos['pendiente'] ?? 0),
            'aprobada' => (int) ($conteos['aprobada'] ?? 0),
            'rechazada' => (int) ($conteos['rechazada'] ?? 0),
            'cancelada' => (int) ($conteos['cancelada'] ?? 0),
        ];

        return view('despacho.index', compact('solicitudes', 'estado', 'contadores'));
    }

    public function formAprobar(EquipmentRequest $solicitud): View
    {
        if ($solicitud->status !== 'pendiente') {
            abort(409, 'Esta solicitud ya fue procesada.');
        }

        $equipos = Equipment::where('category_id', $solicitud->category_id)
            ->where('status', 'disponible')
            ->whereNull('deleted_at')
            ->orderBy('patrimonial_code')
            ->get();

        return view('despacho.aprobar', compact('solicitud', 'equipos'));
    }

    public function aprobar(AprobarSolicitudRequest $request, EquipmentRequest $solicitud): RedirectResponse
    {
        if ($solicitud->status !== 'pendiente') {
            return redirect()->route('admin.solicitudes.index')->with('error', 'Esta solicitud ya fue procesada.');
        }

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

                // Resultado y auditoría no son masignables: los fija el servidor.
                $solicitud->status = 'aprobada';
                $solicitud->processed_by = $request->user()->id;
                $solicitud->processed_at = now();
                $solicitud->save();
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

    public function rechazar(RechazarSolicitudRequest $request, EquipmentRequest $solicitud): RedirectResponse
    {
        if ($solicitud->status !== 'pendiente') {
            return redirect()->route('admin.solicitudes.index')->with('error', 'Esta solicitud ya fue procesada.');
        }

        // Resultado, motivo y auditoría no son masignables: los fija el servidor.
        $solicitud->status = 'rechazada';
        $solicitud->admin_observation = $request->validated('admin_observation');
        $solicitud->processed_by = $request->user()->id;
        $solicitud->processed_at = now();
        $solicitud->save();

        return redirect()->route('admin.solicitudes.index')->with('success', 'Solicitud rechazada correctamente.');
    }
}
