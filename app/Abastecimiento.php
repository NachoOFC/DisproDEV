<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Abastecimiento extends Model
{
    use SoftDeletes;
    protected $fillable = ['nombre'];

    /**
     * Retorna los despachos hacia ese Abastecimiento
     *
     * @return \App\Transporte
     */
    public function transportes()
    {
        return $this->hasMany('App\Transporte');
    }
    
    public function requerimientos()
    {
        return $this->hasManyThrough("App\Requerimiento", "App\Transporte");
    }
}
