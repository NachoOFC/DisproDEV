<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Empresa extends Model
{
    use SoftDeletes;

    protected $fillable = ['razon_social', 'giro', 'direccion', 'rut'];
    protected $appends = ['productosIndexRoute'];


    public function getProductosIndexRouteAttribute()
    {
        return route('empresas.productos.index', $this->id);
    }

    /**
     * Los usuarios asociados a esa Empresa
     *
     */
    public function users()
    {
        return $this->morphMany('App\User', 'userable');
    }

    /**
     * El holding asociado a esa Empresa
     *
     */
    public function holding()
    {
        return $this->belongsTo('App\Holding');
    }

    /**
     * Los Centros asociados a esa Empresa
     *
     */
    public function centros()
    {
        return $this->hasMany('App\Centro');
    }


    public function requerimientos()
    {
        return $this->hasManyThrough("App\Requerimiento", "App\Centro");
    }


    /**
     * Los productos asociados a ese Empresa
     *
     * @return App\Productos
     */
    public function productos()
    {
        return $this->hasMany('App\Producto');
    }

    /**
     * Retorna el Presupuesto de esa Empresa
     *
     * @return App\Presupuesto
     */
    public function presupuestos()
    {
        return $this->morphMany('App\Presupuesto', 'presupuesteable');
    }

    /**
     * Retorna las Programaciones de Precios de esa Empresa
     *
     * @return App\ProgramacionPrecio
     */
    public function programaciones()
    {
        return $this->hasMany('App\ProgramacionPrecio');
    }

    /**
     * Retorna el Horario de esa Empresa
     *
     * @return \App\Horario
     */
    public function horario()
    {
        return $this->hasOne('App\Horario');
    }

    public function cierres()
    {
        return $this->hasMany("App\Cierre");
    }

    public function getProductosVigentesAttribute()
    {
        $fecha = date("Y-m-d");
        $productos = $this->productos()
            ->where('venta', '>', 0)
            ->where("desde", "<=", $fecha)
            ->where("hasta", ">=", $fecha)
            ->orWhere(function ($query) use ($fecha) {
                $query->where("desde", "<=", $fecha)
                    ->whereNull("hasta")
                    ->where('venta', '>', 0);
            })
            ->latest()
            ->get();
        return $productos->unique("sku");
    }


    /**
     * Retorna true si la Empresa tiene un Presupuesto creado
     *
     * @return bool
     */
    public function hasPresupuesto()
    {
        return $this->presupuesto()->get()->isNotEmpty();
    }


    /**
     * Retorna los Requerimientos de esta empresa segun el estado
     *
     * @return App\Requerimiento
     */
    public function getRequerimientoByEstado(String $estado, $date = null)
    {
        $requerimientos = collect([]);
        $centros = $this->centros()->get();

        foreach ($centros as $centro) {
            $requerimientosCentro = $centro->requerimientos()->where('estado', $estado);
            if ($date === null) {
                $requerimientosCentro = $requerimientosCentro->whereYear('created_at', date("Y"))->whereMonth('created_at', date("m"))->get();
            } else {
                $requerimientosCentro = $requerimientosCentro->whereYear('created_at', $date->year)->whereMonth('created_at', $date->month)->get();
            }
            if (count($requerimientosCentro) > 0) {
                $requerimientos->push($requerimientosCentro);
            }
        }

        return $requerimientos;
    }

    /**
     * Retorna los Requerimientos segun Centros de esa Empresa
     *
     * @return Collection
     */
public function getRequerimientos($dateStart = null, $dateEnd = null, $estadoId = null)
{
    $requerimientos = $this->centros()->get()->map(function ($centro) use ($dateStart, $dateEnd, $estadoId) {
        $query = $centro->requerimientos();
        if ($dateStart && $dateEnd) {
            $query = $query->whereDate('created_at', '>=', $dateStart)
                           ->whereDate('created_at', '<=', $dateEnd);
        }
        if ($estadoId) {
            $query = $query->where('estado_id', $estadoId);
        }
        return $query->get();
    });

    return $requerimientos->flatten();
}

    /**
     * Retorna los Centros de esta Empresa segun el estado
     *
     * @return App\Centro
     */
    public function getCentrosByEstado(Int $estadoId)
    {
        switch ($estadoId) {
            case 0:
                return $this->centros()->whereHas('requerimientos', function ($query) {
                    $query->where('estado', 'ESPERANDO VALIDACION');
                })->get();
                break;
            case 1:
                return $this->centros()->whereHas('requerimientos', function ($query) {
                    $query->where('estado', 'VALIDADO');
                })->get();
                break;
            case 2:
                return $this->centros()->whereHas('requerimientos', function ($query) {
                    $query->where('estado', 'EN PROCESAMIENTO');
                })->get();
                break;
            case 3:
                return $this->centros()->whereHas('requerimientos', function ($query) {
                    $query->where('estado', 'EN BODEGA');
                })->get();
                break;
            case 4:
                return $this->centros()->whereHas('requerimientos', function ($query) {
                    $query->where('estado', 'DESPACHADO');
                })->get();
                break;
            case 5:
                return $this->centros()->whereHas('requerimientos', function ($query) {
                    $query->where('estado', 'ENTREGADO');
                })->get();
                break;
            case 6:
                return $this->centros()->whereHas('requerimientos', function ($query) {
                    $query->where('estado', 'RECHAZADO');
                })->get();
                break;
            default:
                return $this->centros()->get();
                break;
        }
    }

    public function clearPresupuesto($year)
    {
        $centros = $this->centros()->get();
        foreach ($centros as $centro) {
            $centro->clearPresupuesto($year);
        }
    }

    /**
     * Retorna el Total del Presupuesto segun el Mes y el AÃ±o
     *
     * @return Int
     */
public function getTotalPresupuestoByDate($mesId = null, $year = null, $centroId = null, $acumulado = false)
{
    $date = \Carbon\Carbon::create($year ?? date("Y"), $mesId ?? date("m"));

    $presupuestoTotal = $this->centros()
        ->when($centroId, function ($query) use ($centroId) {
            return $query->where('id', $centroId); // Filtrar por centro si se proporciona un centroId
        })
        ->get()
        ->map(function ($centro) use ($date, $mesId, $year, $acumulado) {
            $query = $centro->presupuestos();

            // Filtrar por a«Ðo si est«¡ presente
            if ($year !== null) {
                $query = $query->whereYear('fecha_gestion', $date->year);
            }

            // Filtrar por mes
            if ($mesId !== null) {
                if ($acumulado) {
                    // Si es acumulado, filtrar desde enero hasta el mes seleccionado
                    $query = $query->whereMonth('fecha_gestion', '<=', $date->month);
                } else {
                    // Si no es acumulado, filtrar solo el mes seleccionado
                    $query = $query->whereMonth('fecha_gestion', $date->month);
                }
            }

            // Sumar los montos de los presupuestos filtrados
            return $query->sum('monto');
        })
        ->reduce(function ($carry, $item) {
            return $carry + $item;
        }, 0); // Inicializar el acumulador en 0

    return $presupuestoTotal;
}
    /**
     * Retorna los Gastos segun el Mes y el AÃ±o
     *
     * @params Int $mesId, Int $year
     * @return void
     */
public function getGastoByDate($mesId = null, $year = null)
{
    $date = \Carbon\Carbon::create($year ?? date("Y"), $mesId ?? date("m"));

    $gastoTotal = $this->centros()->get()
        ->map(function ($centro) use ($date, $mesId) {
            $totales = $centro->getTotalByMes($date->year);
            return $totales[$mesId][$mesId] ?? 0; // Manejar el caso en que no exista el mes
        })
        ->reduce(function ($carry, $item) {
            return $carry + $item;
        }, 0); // Inicializar el acumulador en 0

    return $gastoTotal;
}

    /**
     * Retorna True si la Empresa esta habilitada para crear segun su Horario
     *
     * @return Boolean
     */
    public function puedeCrear(\App\Centro $centro = null)
    {
        if (isset($centro) && isset($centro->habilitado)) {
            return $centro->habilitado;
        } else {
            $horario = $this->horario()->get()->first();
            if (is_null($horario)) {
                return false;
            }
            $horaInicio = \Carbon\Carbon::parse(date("Y-m-d ") . $horario->hora_creacion_inicio);
            $horaFin = \Carbon\Carbon::parse(date("Y-m-d ") . $horario->hora_creacion_fin);
            $ahora = \Carbon\Carbon::now('America/Santiago');

            if ($horario->fecha_creacion_fin < $horario->fecha_creacion_inicio) {
                if ($horario->fecha_creacion_inicio >= $ahora->dayOfWeekIso && $ahora->dayOfWeekIso >= $horario->fecha_creacion_fin) {
                    if ($horaInicio->lessThanOrEqualTo($ahora) && $ahora->lessThanOrEqualTo($horaFin)) {
                        return true;
                    }
                }
            } else {
                if ($horario->fecha_creacion_inicio <= $ahora->dayOfWeekIso && $ahora->dayOfWeekIso <= $horario->fecha_creacion_fin) {
                    if ($horaInicio->lessThanOrEqualTo($ahora) && $ahora->lessThanOrEqualTo($horaFin)) {
                        return true;
                    }
                }
            }

            return false;
        }
    }

    /**
     * Retorna True si la Empresa esta habilitada para validar segun su Horario
     *
     * @return Boolean
     */
    public function puedeValidar()
    {
        if (isset($this->habilitado)) {
            return $this->habilitado;
        } else {
            $horario = $this->horario()->get()->first();
            if (is_null($horario)) {
                return false;
            }
            $horaInicio = \Carbon\Carbon::parse(date("Y-m-d ") . $horario->hora_validacion_inicio);
            $horaFin = \Carbon\Carbon::parse(date("Y-m-d ") . $horario->hora_validacion_fin);
            $ahora = \Carbon\Carbon::now();

            if ($horario->fecha_validacion_fin < $horario->fecha_validacion_inicio) {
                if ($horario->fecha_validacion_inicio >= $ahora->dayOfWeekIso && $ahora->dayOfWeekIso >= $horario->fecha_validacion_fin) {
                    if ($horaInicio->lessThanOrEqualTo($ahora) && $ahora->lessThanOrEqualTo($horaFin)) {
                        return true;
                    }
                }
            } else {
                if ($horario->fecha_validacion_inicio <= $ahora->dayOfWeekIso && $ahora->dayOfWeekIso <= $horario->fecha_validacion_fin) {
                    if ($horaInicio->lessThanOrEqualTo($ahora) && $ahora->lessThanOrEqualTo($horaFin)) {
                        return true;
                    }
                }
            }

            return false;
        }
    }
}
