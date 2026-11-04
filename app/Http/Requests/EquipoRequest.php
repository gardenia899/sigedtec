<?php

namespace App\Http\Requests;

use App\Models\Equipment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EquipoRequest extends FormRequest
{
    /**
     * Altas y ediciones comparten estas reglas. En la edición el código patrimonial
     * y el número de serie se ignoran a sí mismos, y la categoría puede estar
     * inactiva para no bloquear equipos que ya existen.
     *
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        $equipo = $this->route('equipo');
        $id = $equipo instanceof Equipment ? $equipo->id : null;
        $esAlta = $id === null;

        $reglas = [
            'patrimonial_code' => [
                'required',
                'string',
                'max:20',
                Rule::unique('equipment', 'patrimonial_code')->ignore($id),
            ],
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
            'ram_gb' => ['nullable', 'integer', 'min:1'],
            'storage_gb' => ['nullable', 'integer', 'min:1'],
            'storage_type' => ['nullable', 'string', Rule::in(['hdd', 'ssd', 'nvme', 'emmc'])],
            'acquisition_date' => ['nullable', 'date'],
            'observations' => ['nullable', 'string'],
        ];

        if ($esAlta) {
            // El estado solo se define al crear; después se cambia desde el listado,
            // que es el único camino que deja historial del movimiento.
            $reglas['status'] = ['required', Rule::in(['disponible', 'asignado', 'mantenimiento'])];
        }

        return $reglas;
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'patrimonial_code.unique' => 'Este código patrimonial ya está registrado.',
            'serial_number.unique' => 'Este número de serie ya está registrado.',
            'category_id.required' => 'Debes seleccionar una categoría.',
        ];
    }
}
