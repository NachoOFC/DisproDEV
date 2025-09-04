<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Proveedor extends Model
{
    use SoftDeletes;

    protected $fillable = ['razon_social', 'giro', 'rut', 'comuna', 'direccion', 'telefono', 'correo'];

    protected $appends = ['editRoute', 'deleteRoute'];

    public function getEditRouteAttribute()
    {
        return route('proveedores.edit', $this->id);
    }

    public function getDeleteRouteAttribute()
    {
        return route('proveedores.destroy', $this->id);
    }

    public function bidones()
    {
        return $this->hasMany('App\Bidon');
    }

    public function inventarioFinal($filter)
    {
        $inventarios = $this->bidones->map(function($item) use ($filter) {
            return [
                "bidon" => $item,
                "inventario" => $item->inventarioActual($filter)
            ];
        });

        return $inventarios;
    }

    public function inventarioEntrada($filter)
    {
        $inventarios = $this->bidones->map(function($item) use ($filter) {
            return [
                "bidon" => $item,
                "entradas" => $item->totalEntradas($filter)
            ];
        });

        return $inventarios;
    }

    public function inventarioSalida($filter)
    {
        $inventarios = $this->bidones->map(function($item) use ($filter) {
            return [
                "bidon" => $item,
                "salidas" => $item->totalSalidas($filter)
            ];
        });

        return $inventarios;
    }
}
