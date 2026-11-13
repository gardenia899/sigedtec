<?php

namespace App\Http\Requests;

use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoriaRequest extends FormRequest
{
    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        $categoria = $this->route('categoria');
        $id = $categoria instanceof Category ? $categoria->id : null;

        return [
            'name' => ['required', 'string', 'max:80', 'regex:/^[\p{L}\p{M}\s\'-]+$/u', Rule::unique('categories', 'name')->ignore($id)],
            'description' => ['nullable', 'string', 'max:255'],
            'spec_fields' => ['nullable', 'array'],
            'spec_fields.*' => ['string', Rule::in(array_keys(Category::CAMPOS_TECNICOS))],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'El nombre es obligatorio.',
            'name.unique' => 'Ya existe una categoría con ese nombre.',
        ];
    }
}
