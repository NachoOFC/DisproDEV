<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Producto extends Model
{
    use SoftDeletes;

    protected $fillable = ['sku', 'detalle', 'costo', 'venta', 'desde', 'hasta', 'empresa_id', 'familia', 'marca', 'reemplazo', "formato"];
    protected $appends = ["editRoute", "DeleteRoute"];
    /**
     * Devuelve los requerimientos que contienen a ese Producto
     *
     * @return App\Requerimiento
     */
    public function requerimientos()
    {
        return $this->belongsToMany('App\Requerimiento')->withPivot('cantidad', 'precio', 'real', 'observacion', 'fecha_vencimiento');
    }

    public function guiasDespacho()
    {
        return $this->belongsToMany('App\GuiaDespacho')
            ->withPivot(
                'cantidad',
                'precio',
                'real',
                'observacion',
                'fecha_vencimiento',
                "tipo_observacion_id",
                "cantidad_recibido",
                "genera_nc",
                "liquidado",
                "contenedor",
                "comentario_centro",
                "comentario_reclamo",
            );
    }

    public function rechazos()
    {
        return $this->hasMany("App\Rechazo");
    }

    /**
     * Retorna las Empresas para ese producto
     *
     * @return App\Empresas
     */
    public function empresas()
    {
        return $this->belongsTo('App\Empresa');
    }

    public function setCostoAttribute($value)
    {
        $this->attributes["costo"] = round($value, 2) * 100;
    }

    public function setVentaAttribute($value)
    {
        $this->attributes["venta"] = round($value, 2) * 100;
    }

    public function getVentaAttribute($value)
    {
        return $value / 100;
    }

    public function getCostoAttribute($value)
    {
        return $value / 100;
    }

    public function getEditRouteAttribute()
    {
        return route('productos.edit', $this->id);
    }

    public function getDeleteRouteAttribute()
    {
        return route('productos.destroy', $this->id);
    }

    /**
     * Retorna la cantidad solicitada de ese Producto segun el Mes y el Año
     *
     * @param Int $year
     * @param Int $mes
     * @return Int
     */
    public function getCantidadByDate($year = null, $mes = null)
    {
        $requerimientos = $this->requerimientos();
        $requerimientos = $requerimientos->whereYear('requerimientos.created_at', $year ?? date("Y"));
        if (!is_null($mes)) {
            $requerimientos = $requerimientos->whereMonth('requerimientos.created_at', $mes);
        }

        $requerimientos = $requerimientos->get();
        if ($requerimientos->count() > 0) {
            $cantidad = $requerimientos->reduce(function ($carry, $requerimiento) {
                return $carry + $requerimiento->pivot->cantidad;
            });
        } else {
            $cantidad = 0;
        }

        return $cantidad;
    }

    public function getDetalleGuia($desde = null, $hasta = null, $ids = null, $type = null)
    {

        $guias = $this->guiasDespacho();

        if (isset($desde)) {
            $guias = $guias->where("fecha", ">=", $desde);
        }

        if (isset($hasta)) {
            $guias = $guias->where("fecha", "<=", $hasta);
        }


        $guias = $guias->get();

        if (isset($ids) && isset($type)) {
            switch ($type) {
                case "EMPRESA":
                    $guias = $guias->filter(function ($guia) use ($ids) {
                        return $ids->contains($guia->empresaId);
                    });
                    break;
                case "CENTRO":
                    $guias = $guias->filter(function ($guia) use ($ids) {
                        return $ids->contains($guia->centroId);
                    });
                    break;
                case "ZONA":
                    $guias = $guias->filter(function ($guia) use ($ids) {
                        return $ids->contains($guia->zonaId);
                    });
                    break;
            }
        }

        if ($guias->count() > 0) {
            $cantidad = $guias->reduce(function ($acc, $guia) {
                return $acc + $guia->pivot->cantidad;
            });

            $real = $guias->reduce(function ($acc, $guia) {
                return $acc + $guia->pivot->real;
            });

            $subtotal = $guias->reduce(function ($acc, $guia) {
                return $acc + ($guia->pivot->real * $guia->pivot->precio);
            });

            return [
                "CANTIDAD" => $cantidad,
                "REAL" => $real,
                "SUBTOTAL" => $subtotal
            ];
        }

        return [
            "CANTIDAD" => 0,
            "REAL" => 0,
            "SUBTOTAL" => 0
        ];
    }


    public function getCantidadGuia($desde = null, $hasta = null, $ids = null, $type = null)
    {
        $guias = $this->guiasDespacho();

        if (isset($desde)) {
            $guias = $guias->where("fecha", ">=", $desde);
        }

        if (isset($hasta)) {
            $guias = $guias->where("fecha", "<=", $hasta);
        }


        $guias = $guias->get();

        if (isset($ids) && isset($type)) {
            switch ($type) {
                case "EMPRESA":
                    $guias = $guias->filter(function ($guia) use ($ids) {
                        return $ids->contains($guia->empresaId);
                    });
                    break;
                case "CENTRO":
                    $guias = $guias->filter(function ($guia) use ($ids) {
                        return $ids->contains($guia->centroId);
                    });
                    break;
                case "ZONA":
                    $guias = $guias->filter(function ($guia) use ($ids) {
                        return $ids->contains($guia->zona);
                    });
                    break;
            }
        }

        if ($guias->count() > 0) {
            return $guias->reduce(function ($acc, $guia) {
                return $acc + $guia->pivot->cantidad;
            });
        }

        return 0;
    }


    /**
     * Retorna los Requerimientos con ese Producto segun el Mes y el Año
     *
     * @param Int $year
     * @param Int $mes
     * @return Int
     */
    public function getRequerimientosByDate($year = null, $mes = null)
    {
        $requerimientos = $this->requerimientos();
        $requerimientos = $requerimientos->whereYear('requerimientos.created_at', $year ?? date("Y"));
        if (!is_null($mes)) {
            $requerimientos = $requerimientos->whereMonth('requerimientos.created_at', $mes);
        }

        $requerimientos = $requerimientos->get();
        $data = $requerimientos->map(function ($requerimiento) {
            if (isset($requerimiento->nombre) && isset($requerimiento->centro->nombre)) {
                return [
                    "nombre" => $requerimiento->nombre,
                    "fecha" => \Carbon\Carbon::parse($requerimiento->created_at)->format("d-M-Y"),
                    "centro" => $requerimiento->centro->nombre,
                    "empresa" => $requerimiento->centro->empresa->razon_social
                ];
            }
        });

        return $data;
    }
}
