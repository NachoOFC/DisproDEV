<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NotaCredito extends Model
{
    use SoftDeletes;

    protected $fillable = ['bidon_id', 'cantidad', 'fecha_ingreso', 'fecha_documento', 'folio_documento'];

    protected $appends = ['editRoute', 'deleteRoute', 'bidon', 'qty'];

    public function getEditRouteAttribute()
    {
        return route('nota-creditos.edit', $this->id);
    }

    public function getBidonAttribute()
    {
        return $this->bidon()->first();
    }

    public function getDeleteRouteAttribute()
    {
        return route('nota-creditos.destroy', $this->id);
    }

    public function getCantidadAttribute($value)
    {
        return -intval($value);
    }

    public function getQtyAttribute()
    {
        return abs($this->cantidad);
    }

    public function getTipoDoctoAttribute()
    {
        return "NC";
    }

    public function getConceptoAttribute()
    {
        return "Nota Credito";
    }

    public function bidon()
    {
        return $this->belongsTo('App\Bidon');
    }
}
