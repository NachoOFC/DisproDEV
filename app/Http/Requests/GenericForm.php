<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GenericForm extends FormRequest
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
            'bidon_id' => 'sometimes|exists:bidons,id',
            'cantidad' => 'required|integer',
            'continue' => 'sometimes|integer',
            'fecha_ingreso' => 'required|date',
            'fecha_documento' => 'sometimes|required',
            'folio_documento' => 'sometimes|required',
        ];
    }
}
