<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConfirmarDevolucionRequest;
use App\Http\Requests\SolicitarDevolucionRequest;
use App\Models\Equipment;
use App\Models\EquipmentRequest;
use App\Models\RequestItem;
use App\Models\StatusHistory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class DevolucionController extends Controller
{
    public function solicitar(
        SolicitarDevolucionRequest $request,
        EquipmentRequest $solicitud,
        RequestItem $item
    ): RedirectResponse {
        abort_unless($solicitud->user_id === $request->user()->id, 404);
        abort_unless($solicitud->status === 'aprobada', 404);
        abort_unless($item->request_id === $solicitud->id, 404);

        $resultado = DB::transaction(function () use ($request, $item) {
            $itemBloqueado = RequestItem::whereKey($item->id)->lockForUpdate()->firstOrFail();
            $equipo = Equipment::whereKey($itemBloqueado->equipment_id)->lockForUpdate()->firstOrFail();

            if ($itemBloqueado->returned_at !== null || $equipo->status !== 'asignado') {
                return 'no_activa';
            }

            if ($itemBloqueado->return_requested_at !== null) {
                return 'ya_solicitada';
            }

            $itemBloqueado->update([
                'return_requested_at' => now(),
                'return_requested_by' => $request->user()->id,
                'return_request_observations' => $request->validated('observations'),
            ]);

            return 'solicitada';
        });

        if ($resultado === 'no_activa') {
            return redirect()->route('solicitudes.show', $solicitud)
                ->with('error', 'Este equipo ya no tiene una asignación activa.');
        }

        if ($resultado === 'ya_solicitada') {
            return redirect()->route('solicitudes.show', $solicitud)
                ->with('error', 'La devolución de este equipo ya está pendiente de revisión.');
        }

        return redirect()->route('solicitudes.show', $solicitud)
            ->with('success', 'Solicitud de devolución enviada a Soporte Técnico.');
    }

    public function index(): View
    {
        $devoluciones = RequestItem::with([
            'request:id,user_id,office_id',
            'request.user:id,name',
            'request.office:id,name',
            'equipment:id,patrimonial_code,brand,model,serial_number',
        ])
            ->whereNotNull('return_requested_at')
            ->whereNull('returned_at')
            ->orderBy('return_requested_at')
            ->paginate(15);

        return view('devoluciones.index', compact('devoluciones'));
    }

    public function confirmar(ConfirmarDevolucionRequest $request, RequestItem $item): RedirectResponse
    {
        $data = $request->validated();

        $resultado = DB::transaction(function () use ($request, $item, $data) {
            $itemBloqueado = RequestItem::whereKey($item->id)->lockForUpdate()->firstOrFail();
            $equipo = Equipment::whereKey($itemBloqueado->equipment_id)->lockForUpdate()->firstOrFail();

            if ($itemBloqueado->returned_at !== null || $itemBloqueado->return_requested_at === null) {
                return 'no_pendiente';
            }

            if ($equipo->status !== 'asignado') {
                return 'estado_invalido';
            }

            $nuevoEstado = $data['return_condition'] === 'operativo'
                ? 'disponible'
                : 'mantenimiento';

            $itemBloqueado->update([
                'returned_at' => now(),
                'return_condition' => $data['return_condition'],
                'return_observations' => $data['return_observations'] ?? null,
            ]);

            $equipo->update(['status' => $nuevoEstado]);

            StatusHistory::create([
                'equipment_id' => $equipo->id,
                'request_id' => $itemBloqueado->request_id,
                'user_id' => $request->user()->id,
                'from_status' => 'asignado',
                'to_status' => $nuevoEstado,
                'reason' => 'devolucion',
                'notes' => $data['return_observations'] ?? 'Devolución confirmada por Soporte Técnico.',
            ]);

            return 'confirmada';
        });

        if ($resultado === 'no_pendiente') {
            return back()->with('error', 'Esta devolución ya fue procesada o no está pendiente.');
        }

        if ($resultado === 'estado_invalido') {
            return back()->with('error', 'El equipo ya no figura como asignado; revise el inventario antes de confirmar.');
        }

        return redirect()->route('admin.devoluciones.index')
            ->with('success', 'Devolución confirmada y estado del equipo actualizado.');
    }
}
