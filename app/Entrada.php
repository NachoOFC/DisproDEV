<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Entrada extends Model
{
    use SoftDeletes;

    protected $fillable = ['bidon_id', 'cantidad', 'fecha_ingreso', 'fecha_documento', 'folio_documento'];

    protected $appends = ['editRoute', 'deleteRoute', 'bidon', 'qty'];

    public function getEditRouteAttribute()
    {
        return route('entradas.edit', $this->id);
    }

    public function getDeleteRouteAttribute()
    {
        return route('entradas.destroy', $this->id);
    }

    public function getBidonAttribute()
    {
        return $this->bidon()->first();
    }

    public function getQtyAttribute()
    {
        return abs($this->cantidad);
    }

    public function getTipoDoctoAttribute()
    {
        return "FE";
    }

    public function getConceptoAttribute()
    {
        return "Entrada";
    }

    public function getCantidadAttribute($value)
    {
        return intval($value);
    }

    public function bidon()
    {
        return $this->belongsTo('App\Bidon');
    }
}
