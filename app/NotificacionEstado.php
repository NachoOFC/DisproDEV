<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotificacionEstado extends Model
{
    protected $fillable = [
        "centro", "supervisor", "logistica",
        "compras", "despacho"
    ];


    public function estado()
    {
        return $this->belongsTo("App\Estado");
    }
}
