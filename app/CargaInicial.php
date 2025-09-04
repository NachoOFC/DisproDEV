<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CargaInicial extends Model
{
    use SoftDeletes;

    protected $fillable = ['bidon_id', 'cantidad', 'fecha_ingreso'];

    protected $appends = ['editRoute', 'deleteRoute', 'bidon', 'qty'];

    public function getEditRouteAttribute()
    {
        return route('carga-iniciales.edit', $this->id);
    }

    public function getDeleteRouteAttribute()
    {
        return route('carga-iniciales.destroy', $this->id);
    }

    public function getQtyAttribute()
    {
        return abs($this->cantidad);
    }

    public function getBidonAttribute()
    {
        return $this->bidon()->first();
    }

    public function getCantidadAttribute($value)
    {
        return intval($value);
    }

    public function getTipoDoctoAttribute()
    {
        return "CI";
    }

    public function getConceptoAttribute()
    {
        return "Carga Inicial";
    }

    public function bidon()
    {
        return $this->belongsTo('App\Bidon');
    }
}
