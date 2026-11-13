<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OficinaRequest extends FormRequest
{
    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        $office = $this->route('office');

        return [
            'name' => ['required', 'string', 'max:120', 'regex:/^[\p{L}\p{M}\s\'-]+$/u', Rule::unique('offices', 'name')->ignore($office?->id)],
            'location' => ['nullable', 'string', 'max:160'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.unique' => 'Ya existe una oficina con ese nombre.',
        ];
    }
}
