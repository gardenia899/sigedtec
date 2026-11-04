<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UsuarioController extends Controller
{
    public function index(): View
    {
        $usuarios = User::with('office')
            ->orderBy('name')
            ->get();

        return view('usuarios.index', compact('usuarios'));
    }

    public function edit(User $usuario): View
    {
        return view('usuarios.edit', compact('usuario'));
    }

    public function update(Request $request, User $usuario): RedirectResponse
    {
        $usuarioActual = $request->user();

        if ($usuarioActual->id === $usuario->id) {
            return back()->with('error', 'No puedes cambiar tu propio rol.');
        }

        $data = $request->validate([
            'role' => ['required', Rule::in([User::ROLE_SOLICITANTE, User::ROLE_ADMIN])],
        ], [
            'role.required' => 'Debes seleccionar un rol.',
            'role.in' => 'El rol seleccionado no es válido.',
        ]);

        $usuario->update(['role' => $data['role']]);

        return redirect()->route('usuarios.index')->with('success', 'Rol actualizado correctamente.');
    }
}
