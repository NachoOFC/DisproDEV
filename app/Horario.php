<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    /**
     * Retorna la Empresa de ese Horario
     *
     * @return \App\Empresa
     */
    public function empresa()
    {
        return $this->belongsTo('App\Empresa');
    }
    
}
