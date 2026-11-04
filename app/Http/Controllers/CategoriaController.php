<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoriaRequest;
use App\Models\Category;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

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

    public function store(CategoriaRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $data['name'] = mb_strtolower(trim($data['name']));
        $data['spec_fields'] = $this->camposSeleccionados($request);
        $data['is_active'] = $request->boolean('is_active', true);

        Category::create($data);

        return redirect()->route('categorias.index')->with('success', 'Categoría registrada correctamente.');
    }

    public function edit(Category $categoria): View
    {
        return view('categorias.edit', compact('categoria'));
    }

    public function update(CategoriaRequest $request, Category $categoria): RedirectResponse
    {
        $data = $request->validated();

        $data['name'] = mb_strtolower(trim($data['name']));
        $data['spec_fields'] = $this->camposSeleccionados($request);
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

    /**
     * Campos técnicos marcados, normalizados al orden del catálogo.
     *
     * @return list<string>
     */
    private function camposSeleccionados(Request $request): array
    {
        return array_values(array_intersect(
            array_keys(Category::CAMPOS_TECNICOS),
            array_map('strval', (array) $request->input('spec_fields', [])),
        ));
    }
}
