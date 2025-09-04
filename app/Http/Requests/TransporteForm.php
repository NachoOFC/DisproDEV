<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransporteForm extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'abastecimiento_id' => 'required|exists:abastecimientos,id',
            'nombre_chofer' => 'required',
            'rut_chofer' => 'required',
            'patente' => 'required|alpha_num',
            'rut_empresa' => 'required',
            'contacto' => 'required',
            'fecha_programada' => 'required|date',
            'requerimientos' => 'sometimes',
            'requerimientos.*' => 'exists:requerimientos,id'
        ];
    }
}
