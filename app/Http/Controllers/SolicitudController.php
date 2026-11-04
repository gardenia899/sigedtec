<?php

namespace App\Http\Controllers;

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

    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();

        $data = $request->validate([
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'quantity' => ['required', 'integer', 'min:1', 'max:50'],
            'justification' => ['required', 'string', 'min:10', 'max:1000'],
        ]);

        $categoria = Category::find($data['category_id']);
        if (!$categoria || !$categoria->is_active) {
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

        if ($solicitud->user_id !== $user->id && !$user->isAdmin()) {
            abort(404);
        }

        $solicitud->load([
            'category:id,name',
            'office:id,name',
            'user:id,name',
            'procesadaPor:id,name',
            'items:id,request_id,equipment_id',
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

        $solicitud->update(['status' => 'cancelada']);

        return redirect()->route('solicitudes.index')->with('success', 'Solicitud cancelada correctamente.');
    }
}
