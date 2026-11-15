<?php

namespace App\Http\Controllers;

use App\Http\Requests\SolicitudRequest;
use App\Models\Category;
use App\Models\EquipmentRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SolicitudController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $solicitudes = EquipmentRequest::with(['category:id,name', 'office:id,name'])
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('solicitudes.index', compact('solicitudes'));
    }

    public function create(Request $request): View
    {
        return view('solicitudes.create', [
            'categorias' => Category::activas()->orderBy('name')->get(),
        ]);
    }

    public function store(SolicitudRequest $request): RedirectResponse
    {
        $user = $request->user();
        $data = $request->validated();

        $categoria = Category::find($data['category_id']);
        if (! $categoria || ! $categoria->is_active) {
            return back()->withInput()->with('error', 'La categoría seleccionada no está activa.');
        }

        EquipmentRequest::create([
            'office_id' => $user->office_id,
            'user_id' => $user->id,
            'category_id' => $data['category_id'],
            'quantity' => $data['quantity'],
            'justification' => $data['justification'],
            'status' => 'pendiente',
        ]);

        return redirect()->route('solicitudes.index')->with('success', 'Solicitud enviada correctamente.');
    }

    public function show(Request $request, EquipmentRequest $solicitud): View
    {
        $user = $request->user();

        if ($solicitud->user_id !== $user->id && ! $user->isAdmin()) {
            abort(404);
        }

        $solicitud->load([
            'category:id,name',
            'office:id,name',
            'user:id,name',
            'procesadaPor:id,name',
            'items.equipment:id,patrimonial_code,brand,model,serial_number',
        ]);

        return view('solicitudes.show', compact('solicitud'));
    }

    public function cancelar(Request $request, EquipmentRequest $solicitud): RedirectResponse
    {
        $user = $request->user();

        if ($solicitud->user_id !== $user->id) {
            abort(404);
        }

        if ($solicitud->status !== 'pendiente') {
            return back()->with('error', 'Solo se pueden cancelar solicitudes pendientes.');
        }

        // `status` no es masignable: la cancelación se fija de forma explícita.
        $solicitud->status = 'cancelada';
        $solicitud->save();

        return redirect()->route('solicitudes.index')->with('success', 'Solicitud cancelada correctamente.');
    }
}
