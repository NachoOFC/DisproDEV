<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ajuste extends Model
{
    use SoftDeletes;

    protected $fillable = ['bidon_id', 'cantidad', 'fecha_ingreso', 'suma'];

    protected $appends = ['editRoute', 'deleteRoute', 'bidon', 'qty'];

    public function getEditRouteAttribute()
    {
        return route('ajustes.edit', $this->id);
    }

    public function getDeleteRouteAttribute()
    {
        return route('ajustes.destroy', $this->id);
    }

    public function getBidonAttribute()
    {
        return $this->bidon()->first();
    }

    public function getCantidadAttribute($value)
    {
        if (boolval($this->suma)) {
            return intval($value);
        } else {
            return -intval($value);
        }
    }

    public function getQtyAttribute()
    {
        return abs($this->cantidad);
    }

    public function getTipoDoctoAttribute()
    {
        return "AJ";
    }

    public function getConceptoAttribute()
    {
        return "Ajuste";
    }

    public function bidon()
    {
        return $this->belongsTo('App\Bidon');
    }
}
