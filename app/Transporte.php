<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transporte extends Model
{

    use SoftDeletes;
    protected $fillable = ['abastecimiento_id', 'nombre_chofer', 'rut_chofer',
                           'patente', 'rut_empresa', 'contacto',
                           'fecha_programada'];

    /**
     * Retorna los Requerimientos de ese Transporte
     *
     * @return \App\Requerimientos
     */
    public function requerimientos()
    {
        return $this->hasMany('App\Requerimiento');
    }
    
    /**
     * Retorna el Abastecimiento para ese Transporte
     *
     * @return \App\Abastecimiento
     */
    public function abastecimiento()
    {
        return $this->belongsTo('App\Abastecimiento');
    }
    
}
