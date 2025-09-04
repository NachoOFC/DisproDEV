<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BidonForm extends FormRequest
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
            'proveedor_id' => 'required|exists:proveedors,id',
            'nombre' => 'required',
            'codigo' => 'required',
            'continue' => 'sometimes|integer'
        ];
    }
}
