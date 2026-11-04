<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SolicitudRequest extends FormRequest
{
    /**
     * La categoría se valida como existente; que esté activa se comprueba después,
     * porque un cambio de estado entre la carga del formulario y el envío
     * merece un aviso, no un error de campo.
     *
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'quantity' => ['required', 'integer', 'min:1', 'max:50'],
            'justification' => ['required', 'string', 'min:10', 'max:1000'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'category_id.required' => 'Debes seleccionar una categoría.',
            'quantity.min' => 'Solicita al menos un equipo.',
            'quantity.max' => 'El máximo por solicitud es de 50 equipos.',
            'justification.min' => 'Explica el motivo con al menos 10 caracteres.',
        ];
    }
}
