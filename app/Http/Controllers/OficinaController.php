<?php

namespace App\Http\Controllers;

use App\Http\Requests\OficinaRequest;
use App\Models\Office;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class OficinaController extends Controller
{
    public function index(): View
    {
        return view('oficinas.index', [
            'oficinas' => Office::withCount('users')
                ->select(['id', 'code', 'name', 'location', 'is_active'])
                ->orderBy('code')
                ->get(),
        ]);
    }

    public function create(): View
    {
        return view('oficinas.create');
    }

    public function store(OficinaRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $data['is_active'] = $request->boolean('is_active', true);

        Office::create($data);

        return redirect()->route('oficinas.index')->with('success', 'Oficina registrada correctamente.');
    }

    public function edit(Office $office): View
    {
        return view('oficinas.edit', compact('office'));
    }

    public function update(OficinaRequest $request, Office $office): RedirectResponse
    {
        $data = $request->validated();

        $data['is_active'] = $request->boolean('is_active');

        $office->update($data);

        return redirect()->route('oficinas.index')->with('success', 'Oficina actualizada correctamente.');
    }

    public function toggle(Office $office): RedirectResponse
    {
        if ($office->is_active && $office->users()->exists()) {
            return redirect()->route('oficinas.index')->with('error', 'No se puede desactivar una oficina con usuarios asignados.');
        }

        $office->update(['is_active' => !$office->is_active]);

        $mensaje = $office->is_active ? 'Oficina activada correctamente.' : 'Oficina desactivada correctamente.';

        return redirect()->route('oficinas.index')->with('success', $mensaje);
    }
}