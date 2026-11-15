<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ConfirmarDevolucionRequest extends FormRequest
{
    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'return_condition' => ['required', Rule::in(['operativo', 'requiere_mantenimiento'])],
            'return_observations' => ['nullable', 'string', 'max:255'],
        ];
    }
}
