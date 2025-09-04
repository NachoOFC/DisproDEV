<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProgramacionPrecio extends Model
{
    public $timestamps = false;

    /**
     * Retorna la empresa de esa Asignacion
     *
     * @return \Illuminate\Http\Response
     */
    public function empresa()
    {
        return $this->belongsTo('App\Empresa');
    }
    
}
