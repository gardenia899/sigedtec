<?php

namespace App\Http\Requests;

use App\Models\Office;
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
        $id = $office instanceof Office ? $office->id : null;

        return [
            'code' => ['required', 'string', 'max:10', Rule::unique('offices', 'code')->ignore($id)],
            'name' => ['required', 'string', 'max:120', Rule::unique('offices', 'name')->ignore($id)],
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
            'code.unique' => 'Ya existe una oficina con ese código.',
            'name.unique' => 'Ya existe una oficina con ese nombre.',
        ];
    }
}
