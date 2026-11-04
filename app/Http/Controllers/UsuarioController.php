<?php

namespace App\Http\Controllers;

use App\Http\Requests\UsuarioRequest;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class UsuarioController extends Controller
{
    public function index(): View
    {
        $usuarios = User::with('office:id,name')
            ->select(['id', 'name', 'email', 'office_id', 'role'])
            ->orderBy('name')
            ->get();

        return view('usuarios.index', compact('usuarios'));
    }

    public function edit(User $usuario): View
    {
        return view('usuarios.edit', compact('usuario'));
    }

    public function update(UsuarioRequest $request, User $usuario): RedirectResponse
    {
        $usuarioActual = $request->user();

        if ($usuarioActual->id === $usuario->id) {
            return back()->with('error', 'No puedes cambiar tu propio rol.');
        }

        // `role` está fuera de $fillable (nunca llega del cliente):
        // el cambio se hace de forma explícita.
        $usuario->role = $request->validated('role');
        $usuario->save();

        return redirect()->route('usuarios.index')->with('success', 'Rol actualizado correctamente.');
    }
}
