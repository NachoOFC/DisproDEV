<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','bloqueado',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    /**
     * Get the owning User Model
     *
     */
    public function userable()
    {
        return $this->morphTo();
    }
 /**
     * Relación con el centro asignado.
     */
  /**  public function centro()
    {
        return $this->belongsTo(\App\Centro::class, 'centro_id');
    }*/
    /**
     * Retorna los Requerimientos guardados de ese Usuario
     *
     * @return \App\Requerimiento
     */
    public function requerimientos()
    {
        if ($this->centro_id) {
        return $this->belongsToMany(Requerimiento::class)
            ->where('centro_id', $this->centro_id)
            ->withPivot('nombre');
    } else {
        return $this->belongsToMany(Requerimiento::class)->withPivot('nombre');
    }
    }

public static function getEmpresaUsersWithoutCentros()
{
    return self::whereHasMorph(
        'userable',
        ['App\Empresa'],
        function ($query) {
            $query->whereDoesntHave('centros');
        }
    )->get();
}
public function centros()
    {
        return $this->belongsToMany(Centro::class, 'centro_user', 'user_id', 'centro_id');
    }
    /**
     * Retorna el Nombre o la Razon Social del Objeto Relacionado
     *
     * @return String
     */
    public function getNombreRelacionado()
    {
        switch (get_class($this->userable)) {
        case 'App\Empresa':
            return $this->userable->razon_social;
            break;
        case 'App\Centro':
            return $this->userable->empresa->razon_social . " " . $this->userable->nombre;
            break;
        case 'App\CompassRole':
            return __('Alogis');
            break;
        default:
            return '';
            break;
        }
    }

    /**
     * Retorna True si el Usuario es de Compass
     *
     * @return Boolean
     */
    public function isCompass()
    {
        if ($this->userable instanceof \App\CompassRole) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Retorna True si el Usuario tiene ese Requerimiento en su libreria
     *
     * @return Boolean
     */
    public function hasRequerimiento(\App\Requerimiento $requerimiento)
    {
        $test = $this->requerimientos()->find($requerimiento->id);
        return (is_null($test) ? false : true);
    }
    
}



