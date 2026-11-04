<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Equipment;
use App\Models\StatusHistory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class EquipoController extends Controller
{
    public function index(Request $request): View
    {
        // Solo las columnas que muestra el listado: evita traer observaciones y fechas.
        $query = Equipment::with('category:id,name')
            ->select(['id', 'patrimonial_code', 'category_id', 'brand', 'model', 'serial_number', 'cpu', 'ram_gb', 'storage_gb', 'storage_type', 'status'])
            ->orderBy('patrimonial_code');

        if ($request->filled('categoria')) {
            $query->where('category_id', $request->categoria);
        }

        if ($request->filled('estado')) {
            $query->where('status', $request->estado);
        }

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('patrimonial_code', 'ilike', "%{$q}%")
                    ->orWhere('serial_number', 'ilike', "%{$q}%")
                    ->orWhere('brand', 'ilike', "%{$q}%")
                    ->orWhere('model', 'ilike', "%{$q}%");
            });
        }

        if ($request->boolean('eliminados')) {
            $query->onlyTrashed();
        }

        return view('equipos.index', [
            'equipos' => $query->paginate(15)->withQueryString(),
            'categorias' => Category::orderBy('name')->get(['id', 'name']),
            'filtros' => $request->only(['categoria', 'estado', 'q', 'eliminados']),
        ]);
    }

    public function create(): View
    {
        return view('equipos.create', [
            'categorias' => Category::activas()->orderBy('name')->get(['id', 'name', 'spec_fields']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'patrimonial_code' => ['required', 'string', 'max:20', 'unique:equipment,patrimonial_code'],
            'category_id' => ['required', 'integer', Rule::exists('categories', 'id')->where('is_active', true)],
            'brand' => ['required', 'string', 'max:80'],
            'model' => ['required', 'string', 'max:80'],
            'serial_number' => ['nullable', 'string', 'max:80', Rule::unique('equipment', 'serial_number')->whereNull('deleted_at')],
            'cpu' => ['nullable', 'string', 'max:120'],
            'ram_gb' => ['nullable', 'integer', 'min:1'],
            'storage_gb' => ['nullable', 'integer', 'min:1'],
            'storage_type' => ['nullable', 'string', Rule::in(['hdd', 'ssd', 'nvme', 'emmc'])],
            'status' => ['required', Rule::in(['disponible', 'asignado', 'mantenimiento'])],
            'acquisition_date' => ['nullable', 'date'],
            'observations' => ['nullable', 'string'],
        ], [
            'patrimonial_code.unique' => 'Este código patrimonial ya está registrado.',
            'serial_number.unique' => 'Este número de serie ya está registrado.',
            'category_id.required' => 'Debes seleccionar una categoría.',
        ]);

        $data = $this->soloCamposDeLaCategoria(Category::findOrFail($data['category_id']), $data);

        DB::transaction(function () use ($data, $request) {
            $equipo = Equipment::create($data);

            StatusHistory::create([
                'equipment_id' => $equipo->id,
                'user_id' => $request->user()->id,
                'from_status' => null,
                'to_status' => $equipo->status,
                'reason' => 'creacion',
                'notes' => 'Alta de equipo',
            ]);
        });

        return redirect()->route('equipos.index')->with('success', 'Equipo registrado correctamente.');
    }

    public function edit(Equipment $equipo): View
    {
        return view('equipos.edit', [
            'equipo' => $equipo,
            'categorias' => Category::orderBy('name')->get(['id', 'name', 'spec_fields']),
        ]);
    }

    public function update(Request $request, Equipment $equipo): RedirectResponse
    {
        $data = $request->validate([
            'patrimonial_code' => ['required', 'string', 'max:20', Rule::unique('equipment', 'patrimonial_code')->ignore($equipo->id)],
            'category_id' => ['required', 'integer', Rule::exists('categories', 'id')],
            'brand' => ['required', 'string', 'max:80'],
            'model' => ['required', 'string', 'max:80'],
            'serial_number' => ['nullable', 'string', 'max:80', Rule::unique('equipment', 'serial_number')->ignore($equipo->id)->whereNull('deleted_at')],
            'cpu' => ['nullable', 'string', 'max:120'],
            'ram_gb' => ['nullable', 'integer', 'min:1'],
            'storage_gb' => ['nullable', 'integer', 'min:1'],
            'storage_type' => ['nullable', 'string', Rule::in(['hdd', 'ssd', 'nvme', 'emmc'])],
            'acquisition_date' => ['nullable', 'date'],
            'observations' => ['nullable', 'string'],
        ], [
            'patrimonial_code.unique' => 'Este código patrimonial ya está registrado.',
            'serial_number.unique' => 'Este número de serie ya está registrado.',
        ]);

        $data = $this->soloCamposDeLaCategoria(Category::findOrFail($data['category_id']), $data);

        $equipo->update($data);

        return redirect()->route('equipos.index')->with('success', 'Equipo actualizado correctamente.');
    }

    /**
     * Descarta las especificaciones que no aplican a la categoría elegida,
     * para que un equipo no conserve RAM o almacenamiento al cambiar de tipo.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function soloCamposDeLaCategoria(Category $categoria, array $data): array
    {
        foreach (array_keys(Category::CAMPOS_TECNICOS) as $campo) {
            if (! $categoria->requiereCampo($campo)) {
                $data[$campo] = null;
            }
        }

        return $data;
    }

    public function destroy(Equipment $equipo): RedirectResponse
    {
        if ($equipo->status === 'asignado') {
            return redirect()->route('equipos.index')->with('error', 'No se puede dar de baja un equipo asignado.');
        }

        $equipo->delete();

        return redirect()->route('equipos.index')->with('success', 'Equipo dado de baja correctamente.');
    }

    public function restore($id): RedirectResponse
    {
        $equipo = Equipment::withTrashed()->findOrFail($id);
        $equipo->restore();

        return redirect()->route('equipos.index', ['eliminados' => 1])->with('success', 'Equipo restaurado correctamente.');
    }

    public function cambiarEstado(Request $request, Equipment $equipo): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(['disponible', 'asignado', 'mantenimiento'])],
            'notes' => ['nullable', 'string', 'max:255'],
        ]);

        $from = $equipo->status;

        if ($from === $data['status']) {
            return redirect()->route('equipos.index')->with('error', 'El estado es el mismo.');
        }

        DB::transaction(function () use ($equipo, $data, $request, $from) {
            $equipo->update(['status' => $data['status']]);

            StatusHistory::create([
                'equipment_id' => $equipo->id,
                'user_id' => $request->user()->id,
                'from_status' => $from,
                'to_status' => $data['status'],
                'reason' => 'edicion_manual',
                'notes' => $data['notes'] ?: 'Cambio manual de estado',
            ]);
        });

        return redirect()->route('equipos.index')->with('success', 'Estado actualizado correctamente.');
    }
}
