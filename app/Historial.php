<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Historial extends Model
{
    /**
     * Retorna el Presupuesto de ese Historial
     *
     */
    public function presupuesto()
    {
        return $this->belongsTo('App\Presupuesto');
    }
    
}
