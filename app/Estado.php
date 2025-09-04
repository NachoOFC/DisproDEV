<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Estado extends Model
{
    use SoftDeletes;

    protected $fillable = ["nombre"];

    /**
    * Returns a collection with all \App\Historial with this \App\Estado.
    * @return Illuminate\Database\Eloquent\Collection
    */
    public function historiales()
    {
        return $this->hasMany("App\HistorialEstado");
    }

    /**
    *   Returns a collection with all \App\Requerimento with this \App\Estado.
    *   @return Illuminate\Database\Eloquent\Collection
    */
    public function requerimientos()
    {
        return $this->hasManyThrough("App\Requerimiento", "App\HistorialEstado");
    }


    public function notificacion()
    {
        return $this->hasOne("App\NotificacionEstado");
    }


}
