<?php

namespace App\Http\Requests;

use App\Models\EquipmentRequest;
use Illuminate\Foundation\Http\FormRequest;

class AprobarSolicitudRequest extends FormRequest
{
    /**
     * La cantidad exigida sale de la propia solicitud, para que el despacho
     * entregue exactamente lo pedido y no lo que decida el formulario.
     *
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        $solicitud = $this->route('solicitud');
        $cantidad = $solicitud instanceof EquipmentRequest ? (int) $solicitud->quantity : 1;

        return [
            'equipos' => ['required', 'array', 'min:'.$cantidad, 'max:'.$cantidad],
            'equipos.*' => ['integer', 'exists:equipment,id'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'equipos.required' => 'Debes seleccionar los equipos que se entregarán.',
            'equipos.min' => 'Debes seleccionar exactamente la cantidad solicitada.',
            'equipos.max' => 'Seleccionaste más equipos de los solicitados.',
            'equipos.*.exists' => 'Uno de los equipos seleccionados ya no existe.',
        ];
    }
}
