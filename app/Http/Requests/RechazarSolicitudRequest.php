<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RechazarSolicitudRequest extends FormRequest
{
    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'admin_observation' => ['required', 'string', 'min:5', 'max:500'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'admin_observation.required' => 'Debes indicar el motivo del rechazo.',
            'admin_observation.min' => 'Explica el motivo con al menos 5 caracteres.',
            'admin_observation.max' => 'El motivo no puede superar los 500 caracteres.',
        ];
    }
}
