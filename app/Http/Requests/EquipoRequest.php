<?php

namespace App\Http\Requests;

use App\Models\Equipment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EquipoRequest extends FormRequest
{
    /**
     * El código patrimonial se genera en el servidor al guardar y no se puede
     * editar. En la edición la serie se ignora a sí misma y una categoría inactiva
     * sigue siendo válida para no bloquear equipos que ya existen.
     *
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        $equipo = $this->route('equipo');
        $id = $equipo instanceof Equipment ? $equipo->id : null;
        $esAlta = $id === null;

        $reglas = [
            'category_id' => [
                'required',
                'integer',
                $esAlta
                    ? Rule::exists('categories', 'id')->where('is_active', true)
                    : Rule::exists('categories', 'id'),
            ],
            'brand' => ['required', 'string', 'max:80'],
            'model' => ['required', 'string', 'max:80'],
            'serial_number' => [
                'nullable',
                'string',
                'max:80',
                Rule::unique('equipment', 'serial_number')->ignore($id)->whereNull('deleted_at'),
            ],
            'cpu' => ['nullable', 'string', 'max:120'],
            'ram_gb' => ['nullable', 'integer', 'min:1', 'max:65535'],
            'storage_gb' => ['nullable', 'integer', 'min:1', 'max:65535'],
            'storage_type' => ['nullable', 'string', Rule::in(['hdd', 'ssd', 'nvme', 'emmc'])],
            'acquisition_date' => ['nullable', 'date'],
            'observations' => ['nullable', 'string'],
        ];

        if ($esAlta) {
            $reglas['status'] = ['required', Rule::in(['disponible', 'mantenimiento'])];
        } elseif ($equipo->status === 'asignado') {
            $reglas['status'] = ['prohibited'];
        } else {
            $reglas['status'] = ['sometimes', 'required', Rule::in(['disponible', 'mantenimiento'])];
        }

        return $reglas;
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'serial_number.unique' => 'Este número de serie ya está registrado.',
            'category_id.required' => 'Debes seleccionar una categoría.',
        ];
    }
}
