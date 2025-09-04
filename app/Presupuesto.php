<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Presupuesto extends Model
{
    protected $fillable = ['monto', 'fecha_gestion'];
    /**
     * Retorna el dueño de ese Presupuesto
     *
     */
    public function presupuesteable()
    {
        return $this->morphTo();
    }

    /**
     * Retorna el Historial de ese Presupuesto
     *
     */
    public function movimientos()
    {
        return $this->hasMany('App\Historial');
    }
    
    
}
