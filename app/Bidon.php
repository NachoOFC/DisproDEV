<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bidon extends Model
{
    use SoftDeletes;

    protected $appends = ['editRoute', 'deleteRoute'];
    protected $fillable = ['nombre', 'codigo', 'proveedor_id'];

    private function filterByDate($query, $filter)
    {
        return $query->where([
            ['fecha_ingreso', '>=', $filter['fecha_inicio']],
            ['fecha_ingreso', '<=', $filter['fecha_fin']]
        ])->get();
    }

    public function getEditRouteAttribute()
    {
        return route('bidones.edit', $this->id);
    }

    public function getDeleteRouteAttribute()
    {
        return route('bidones.destroy', $this);
    }

    public function proveedor()
    {
        return $this->belongsTo('App\Proveedor');
    }

    public function entradas()
    {
        return $this->hasMany('App\Entrada');
    }

    public function salidas()
    {
        return $this->hasMany('App\Salida');
    }

    public function ajustes()
    {
        return $this->hasMany('App\Ajuste');
    }

    public function cargasInicial()
    {
        return $this->hasMany('App\CargaInicial');
    }

    public function notasCredito()
    {
        return $this->hasMany('App\NotaCredito');
    }

    public function generateKardex($filter)
    {
        $entradas = $this->filterByDate($this->entradas(), $filter);
        $salidas = $this->filterByDate($this->salidas(), $filter);
        $ajustes = $this->filterByDate($this->ajustes(), $filter);
        $cargasIniciales = $this->filterByDate($this->cargasInicial(), $filter);
        $notasCredito = $this->filterByDate($this->notasCredito(), $filter);


        $ajustesSuma = clone $ajustes;
        $ajustesResta = clone $ajustes;

        $ajustesSuma = $ajustesSuma->filter(function($value, $key) {
            return boolval($value->suma);
        });

        $ajustesResta = $ajustesResta->filter(function($value, $key) {
            return !boolval($value->suma);
        });

        $timeline = collect([$entradas, $salidas, $ajustesSuma, $ajustesResta, $cargasIniciales, $notasCredito])->flatten();
        $timeline = $timeline->sortBy('fecha_ingreso');
        return $timeline;
    }

    public function inventarioActual($filter)
    {
        $entradas = $this->filterByDate($this->entradas(), $filter)->reduce(function($carry, $item) {
            return $carry + $item->cantidad;
        });
        $salidas = $this->filterByDate($this->salidas(), $filter)->reduce(function($carry, $item) {
            return $carry + $item->cantidad;
        });
        $cargasIniciales = $this->filterByDate($this->cargasInicial(), $filter)->reduce(function($carry, $item) {
            return $carry + $item->cantidad;
        });
        $notasCredito = $this->filterByDate($this->notasCredito(), $filter)->reduce(function($carry, $item) {
            return $carry + $item->cantidad;
        });

        $ajustes = $this->filterByDate($this->ajustes(), $filter);
        $ajustesSuma = clone $ajustes;
        $ajustesResta = clone $ajustes;

        $ajustesSuma = $ajustesSuma->filter(function($value, $key) {
            return boolval($value->suma);
        })->reduce(function($carry, $item) {
            return $carry + $item->cantidad;
        });

        $ajustesResta = $ajustesResta->filter(function($value, $key) {
            return !boolval($value->suma);
        })->reduce(function($carry, $item) {
            return $carry + $item->cantidad;
        });

        return $entradas + $cargasIniciales + $ajustesSuma + $salidas + $notasCredito + $ajustesResta;

    }

    public function totalEntradas($filter)
    {
        return $entradas = $this->filterByDate($this->entradas(), $filter)->reduce(function($carry, $item) {
            return $carry + $item->cantidad;
        });

    }

    public function totalSalidas($filter)
    {
        return $salidas = $this->filterByDate($this->salidas(), $filter)->reduce(function($carry, $item) {
            return $carry + $item->cantidad;
        });
    }
}
