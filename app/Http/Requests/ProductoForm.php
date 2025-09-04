<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductoForm extends FormRequest
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
            "sku" => 'required',
            "detalle" => 'required',
            "costo" => 'required',
            "venta" => "required|gt:costo",
            "desde" => 'required|date',
            "hasta" => 'sometimes|date',
        ];
    }
}
