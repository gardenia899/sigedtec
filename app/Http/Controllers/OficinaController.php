<?php

namespace App\Http\Controllers;

use App\Models\Office;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OficinaController extends Controller
{
    public function index(): View
    {
        return view('oficinas.index', [
            'oficinas' => Office::withCount('users')->orderBy('code')->get(),
        ]);
    }

    public function create(): View
    {
        return view('oficinas.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:10', 'unique:offices,code'],
            'name' => ['required', 'string', 'max:120', 'unique:offices,name'],
            'location' => ['nullable', 'string', 'max:160'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active', true);

        Office::create($data);

        return redirect()->route('oficinas.index')->with('success', 'Oficina registrada correctamente.');
    }

    public function edit(Office $office): View
    {
        return view('oficinas.edit', compact('office'));
    }

    public function update(Request $request, Office $office): RedirectResponse
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:10', Rule::unique('offices', 'code')->ignore($office->id)],
            'name' => ['required', 'string', 'max:120', Rule::unique('offices', 'name')->ignore($office->id)],
            'location' => ['nullable', 'string', 'max:160'],
            'is_active' => ['nullable', 'boolean'],
        ]);

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