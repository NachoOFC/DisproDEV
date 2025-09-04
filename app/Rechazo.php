<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rechazo extends Model
{
    use SoftDeletes;

    protected $fillable = ["producto_id", "guia_despacho_id", "motivo"];


    public function producto()
    {
        return $this->belongsTo("App\Producto");
    }


    public function guiaDespacho()
    {
        return $this->belongsTo("App\GuiaDespacho");
    }

    public function getProductoGuiaAttribute()
    {
        return $this->guiaDespacho
            ->productos
            ->where("id", $this->producto_id)->first();
    }
}
