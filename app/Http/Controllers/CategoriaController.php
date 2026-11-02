<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoriaController extends Controller
{
    public function index(): View
    {
        return view('categorias.index', [
            'categorias' => Category::withCount('equipments')->orderBy('name')->get(),
        ]);
    }

    public function create(): View
    {
        return view('categorias.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:80', 'unique:categories,name'],
            'description' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['name'] = mb_strtolower(trim($data['name']));
        $data['is_active'] = $request->boolean('is_active', true);

        Category::create($data);

        return redirect()->route('categorias.index')->with('success', 'Categoría registrada correctamente.');
    }

    public function edit(Category $categoria): View
    {
        return view('categorias.edit', compact('categoria'));
    }

    public function update(Request $request, Category $categoria): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:80', Rule::unique('categories', 'name')->ignore($categoria->id)],
            'description' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['name'] = mb_strtolower(trim($data['name']));
        $data['is_active'] = $request->boolean('is_active');

        $categoria->update($data);

        return redirect()->route('categorias.index')->with('success', 'Categoría actualizada correctamente.');
    }

    public function toggle(Category $categoria): RedirectResponse
    {
        if ($categoria->is_active && $categoria->equipments()->exists()) {
            return redirect()->route('categorias.index')->with('error', 'No se puede desactivar una categoría con equipos registrados.');
        }

        $categoria->update(['is_active' => !$categoria->is_active]);

        $mensaje = $categoria->is_active ? 'Categoría activada correctamente.' : 'Categoría desactivada correctamente.';

        return redirect()->route('categorias.index')->with('success', $mensaje);
    }
}
