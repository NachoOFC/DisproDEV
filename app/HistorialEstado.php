<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HistorialEstado extends Model
{
    use SoftDeletes;

    protected $fillable = [
        "requerimiento_id", "estado_id", "user_id",
        "observacion"
    ];

    /**
     * Returns the \App\Estado related to this \App\HistorialEstado
     * @return \App\Estado
     */
    public function estado()
    {
        return $this->belongsTo("App\Estado");
    }

    /**
     * Returns the \App\Requerimiento related to this \App\HistorialEstado
     * @return \App\Requerimiento
     */
    public function requerimiento()
    {
        return $this->belongsTo("App\Requerimiento");
    }

    /**
     * Returns the \App\User related to this \App\HistorialEstado
     * @return \App\User
     */
    public function user()
    {
        return $this->belongsTo("App\User");
    }

}
