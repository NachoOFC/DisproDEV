<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProveedorForm extends FormRequest
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
            'razon_social' => 'required',
            'giro' => 'required',
            'rut' => 'required',
            'comuna' => 'required',
            'direccion' => 'required',
            'telefono' => 'required',
            'correo' => 'required|email:rfc',
            'continue' => 'sometimes|integer'
        ];
    }
}
