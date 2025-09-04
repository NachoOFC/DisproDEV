<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bodeguero extends Model
{
    use SoftDeletes;
    protected $fillable = ["nombre", "rut"];

    /**
     * Retorna los Requerimientos que armó ese Bodeguero
     *
     * @return \App\Requerimiento
     */
    public function requerimientos()
    {
        return $this->hasMany('App\Requerimiento');
    }
    
}
