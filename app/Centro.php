<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Centro extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'nombre', 'direccion', 'comuna',
        'ciudad', 'zona', 'empresa_id', 'dotacion'
    ];

    /**
     * Los usuarios asociados a ese Centro
     *
     */
    public function users()
    {
        return $this->morphMany('App\User', 'userable');
    }

    /**
     * La Empresa asociada a ese Centro
     *
     */
    public function empresa()
    {
        return $this->belongsTo('App\Empresa');
    }

    /**
     * Los Requerimientos asociados a ese Centro
     *
     * @return App\Requerimiento
     */
    public function requerimientos()
    {
        return $this->hasMany('App\Requerimiento');
    }

    public function guiasDespacho()
    {
        return $this->hasManyThrough("App\GuiaDespacho", "App\Requerimiento");
    }

    /**
     * Retorna el Presupuesto de ese Centro
     *
     * @return App\Presupuesto
     */
    public function presupuestos()
    {
        return $this->morphMany('App\Presupuesto', 'presupuesteable');
    }

    public function ordenesCompras()
    {
        return $this->belongsToMany("App\OrdenCompra")->withPivot("monto");
    }

    public function notasCredito()
    {
        return $this->belongsToMany("App\NotaCreditoTributaria")->withPivot("monto");
    }

    /**
     * Retorna los Requerimientos de ese Centro segun el Id de Estado
     *
     * @return \App\Requerimiento
     */
    public function getRequerimientosByEstado($estadoId = null)
    {
        if ($estadoId > 0) {
            $estado = \App\Estado::find($estadoId);

            if ($estado) {
                return $this->requerimientos()->where("estado", $estado->nombre)->get();
            }
        }

        return $this->requerimientos;
    }

    /**
     * Retorna el Total del Presupuesto segun el Mes y el Año
     *
     * @return Int
     */
public function getTotalPresupuestoByDate($mesId = null, $year = null)
{
    $date = \Carbon\Carbon::create($year ?? date("Y"), $mesId ?? date("m"));
    $query = $this->presupuestos()->latest();
    if ($year !== null) {
        $query = $query->whereYear('fecha_gestion', $date->year);
    }
    if ($mesId !== null) {
        $query = $query->whereMonth('fecha_gestion', $date->month);
    }
    $presupuesto = $query->first();
    return $presupuesto ? $presupuesto->monto : 0; // Devuelve 0 si no hay presupuesto
}

    /**
     * Retornar el total de todos los Requerimientos segun el Mes y el Año
     *
     * @return Collection[int]
     */
    public function getTotalByMes($year = null)
    {
        if (is_null($year)) {
            $requerimientos = $this->requerimientos()->whereYear('created_at', date("Y"))->get()->groupBy(function ($d) {
                return \Carbon\Carbon::parse($d->created_at)->format('m');
            });
        } else {
            $requerimientos = $this->requerimientos()->whereYear('created_at', $year)->get()->groupBy(function ($d) {
                return \Carbon\Carbon::parse($d->created_at)->format('m');
            });
        }
        $total = $requerimientos->map(function ($mes, $index) {
            $totalMes = $mes->reduce(function ($carry, $requerimiento) {
                return $carry + ($requerimiento->getTotal() ?? 0);
            });
            return [$index => $totalMes];
        });
        return $total;
    }

    public function cierre(string $desde, string $hasta)
    {
        $guias = $this->guiasDespacho()->whereHas("productos", function (Builder $query) {
            $query->whereIn("tipo_observacion_id", [1, 2, 3, 4, 5, 6, 7, 8]);
        })
            ->whereDate("fecha", ">=", $desde)
            ->whereDate("fecha", "<=", $hasta)
            ->whereNotNull("febos_id")
            ->get();

        $cierre = 0;
        foreach ($guias as $guia) {
            $cierre += $guia->liquidacion;
        }

        return $cierre;
    }

    public function clearPresupuesto($year)
    {
        $presupuestos = $this->presupuestos->whereYear('fecha_gestion', $year)->get();
        foreach ($presupuestos as $presupuesto) {
            $presupuesto->delete();
        }
    }
}
