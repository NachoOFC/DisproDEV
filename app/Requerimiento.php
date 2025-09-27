<?php

namespace App;

use App\Notifications\EstadoUpdated;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class Requerimiento extends Model
{
    use SoftDeletes;
    protected $guarded = ['created_at', 'id'];
    protected $appends = ["updateRoute"];

    protected static function boot()
    {
        parent::boot();
       // static::saved(function ($model) {
         //   $users = $model->getUserByRequerimiento();
          //  $users->map(function ($user) use ($model) {
           //     $user->notify((new EstadoUpdated($model))->delay(\Carbon\Carbon::now()->addSeconds(60)));
           // });
       // });
    }

    /**
     * Retorna el folio del Requerimiento
     *
     * @param string $value
     * @return Collection
     */
    public function getFolioAttribute($value)
    {
        return collect(explode(",", str_replace(["[", "]"], "", $value)));
    }

    public function getUpdateRouteAttribute()
    {
        return route('requerimientos.update', $this->id);
    }

    public function historialEstados()
    {
        return $this->hasMany(HistorialEstado::class);
    }

    public function estados()
    {
        return $this->hasManyThrough(Estado::class, HistorialEstado::class);
    }

    public function productosRechazados()
    {
        return $this->hasManyThrough(Rechazo::class, GuiaDespacho::class);
    }

    /**
     * Retorna los productos relacionados a ese Requerimietno
     *
     * @return App\Producto
     */
    public function productos()
    {
        return $this->belongsToMany(Producto::class)->withPivot('cantidad', 'precio', 'real', 'observacion', 'fecha_vencimiento');
    }

    /**
     * Retorna el Centro al que pertenece ese requerimiento
     *
     * @return App\Centro
     */
    public function centro()
    {
        return $this->belongsTo(Centro::class);
    }

    /**
     * Retorna el transporte de ese Requerimiento
     *
     * @return \App\Requerimiento
     */
    public function transporte()
    {
        return $this->belongsTo(Transporte::class);
    }

    /**
     * Retorna el Bodeguero encargado de ese Requerimiento
     *
     * @return \App\Bodeguero
     */
    public function bodeguero()
    {
        return $this->belongsTo(Bodeguero::class);
    }

    public function guiasDespacho()
    {
        return $this->hasMany(GuiaDespacho::class);
    }

    /**
     * Retorna los Usuarios con este Requerimiento en su libreria
     *
     * @return App\User
     */
    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('nombre');
    }


    public function getEstadoActualAttribute()
    {
        return $this->historialEstados()->latest()->first();
    }

    /**
     * @param string $nombreEstado
     * @return string
     */
    public function detalleEstado($nombreEstado)
    {
        return $this
            ->historialEstados()
            ->whereHas("estado", function ($query) use ($nombreEstado) {
                return $query->where("nombre", $nombreEstado);
            })
            ->latest()
            ->first();
    }


    /**
     * Retornar lista de Usuarios relacionados a ese Requerimiento
     *
     * @return \App\User
     */
    public function getUserByRequerimiento()
{
    $users = collect([]);

    $centro = $this->centro()->first();
    Log::info('Centro del requerimiento:', ['centro_id' => $centro ? $centro->id : null]);
    $centroUser = $centro->users()->first();
    if (isset($centroUser)) {
        $users->push($centroUser);
        Log::info('Usuario del centro agregado:', ['email' => $centroUser->email]);
    }

    $empresa = $centro->empresa()->first();
    Log::info('Empresa del centro:', ['empresa_id' => $empresa ? $empresa->id : null]);
    $empresaUser = $empresa->users()->first();
    if (isset($empresaUser)) {
        $users->push($empresaUser);
        Log::info('Usuario de la empresa agregado:', ['email' => $empresaUser->email]);
    }

    $compassUsers = \App\User::whereHasMorph(
        'userable',
        ['App\CompassRole'],
        function ($query) {
            $query->where('name', 'like', 'Compras');
        }
    )->get();
    Log::info('Usuarios con rol de Compras encontrados:', ['count' => $compassUsers->count()]);
    foreach ($compassUsers as $user) {
        $users->push($user);
        Log::info('Usuario de Compras agregado:', ['email' => $user->email]);
    }

    Log::info('Lista final de usuarios para notificación:', ['emails' => $users->pluck('email')->toArray()]);

    return $users;
}

    /**
     * Retorna el Total de ese Requerimiento
     *
     * @return Int
     */
    public function getTotal()
    {
        $productos = $this->productos()->get();
        $total = $productos->map(function ($producto) {
            if ($producto->pivot->real) {
                return $producto->pivot->real * $producto->pivot->precio;
            }
            return $producto->pivot->cantidad * $producto->pivot->precio;
        })->reduce(function ($carry, $item) {
            return ($carry + $item);
        });

        return $total;
    }

    /**
     * Genera un txt con los datos para la guia de despacho electronica
     *
     * @return Boolean
     */
    public function generarGuiaDespacho()
    {
        $folios = $this->folio;
        $productos = $this->productos->chunk(29);


        if ($this->guiasDespacho->count() > 0) {
            return $this->guiasDespacho;
        }

        $folios->map(function ($folio, $index) use ($productos) {
            $guiaDespacho = $this->guiasDespacho()->create([
                "folio" => str_pad($folio, 10, "0", STR_PAD_LEFT),
                "fecha" => date("Y-m-d"),
                "rut_receptor" => str_replace(".", "", strtoupper($this->centro->empresa->rut)),
                "razon_social_receptor" => $this->centro->empresa->razon_social,
                "giro_receptor" => $this->centro->empresa->giro,
                "direccion_receptor" => $this->centro->direccion,
                "comuna_receptor" => $this->centro->comuna,
                "nombre_receptor" => $this->centro->nombre,
                "nombre_centro" => $this->centro->nombre,
                "ciudad_receptor" => $this->centro->ciudad,
                "direccion_destino" => $this->transporte->abastecimiento->nombre,
                "comuna_destino" => $this->transporte->abastecimiento->comuna,
                "ciudad_destino" => $this->transporte->abastecimiento->ciudad,
                "transporte_rut" => str_replace(".", "", $this->transporte->rut_chofer),
                "transporte_nombre" => $this->transporte->nombre_chofer,
            ]);
            $guiaDespacho->agregarProductos($productos[$index]);
        });

        return $this->guiasDespacho;
    }


  /**  public function cambiarEstado($nombreEstado, $usuario_id, $observacion = null)
    {
        try {
            $this->estado = $nombreEstado;
            $this->save();
            $estado = \App\Estado::where("nombre", $nombreEstado)->firstOrFail();
            $this->historialEstados()->create([
                "estado_id" => $estado->id,
                "user_id" => $usuario_id,
                "observacion" => $observacion
            ]);
            return true;
        } catch (ModelNotFoundException $e) {
            Log::error($e);
            return false;
        }
    }*/
public function cambiarEstado($nombreEstado, $usuario_id, $observacion = null)
{
    try {
        $this->estado = $nombreEstado;
        $this->save();
        $estado = \App\Estado::where("nombre", $nombreEstado)->firstOrFail();
        $this->historialEstados()->create([
            "estado_id" => $estado->id,
            "user_id" => $usuario_id,
            "observacion" => $observacion
        ]);

        // Notificar a los usuarios de tipo Empresa sin centros asignados
        if ($nombreEstado === 'ESPERANDO APROBACION GERENTE') {
            $empresaUsers = \App\User::whereHasMorph(
                'userable',
                ['App\Empresa'],
                function ($query) {
                    $query->whereDoesntHave('centros');
                }
            )->get();

            foreach ($empresaUsers as $user) {
                $user->notify(new \App\Notifications\EstadoUpdated($this));
            }
        }

        return true;
    } catch (ModelNotFoundException $e) {
        Log::error($e);
        return false;
    }
}
    public function getNetoAttribute()
    {
        return $this->guiasDespacho->reduce(function ($carry, $item) {
            return $carry + $item->neto;
        });
    }

    public function getEstadoPagoAttribute()
    {
        return $this->guiasDespacho->reduce(function ($carry, $item) {
            return $carry + $item->liquidacion;
        });
    }

    public function getNotaCreditoProformaAttribute()
    {
        return $this->guiasDespacho->reduce(function ($carry, $item) {
            return $carry + ($item->notaCredito + $item->notaCreditoContenedor);
        });
    }
}
