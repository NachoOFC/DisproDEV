<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreHorario extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (Auth::user()->userable instanceof \App\CompassRole) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "empresa" => 'required',
            "fechaCreacionInicio" => 'required',
            "horaCreacionInicio" => 'required',
            "fechaCreacionFin" => 'required',
            "horaCreacionFin" => 'required',
            "fechaValidacionInicio" => 'required',
            "horaValidacionInicio" => 'required',
            "fechaValidacionFin" => 'required',
            "horaValidacionFin" => 'required'
        ];
    }
}
