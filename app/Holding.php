<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Holding extends Model
{

  /**
   * Los usuarios asociados a ese Holding
   *
   */
  public function users()
  {
    return $this->morphMany('App\User', 'userable');
  }

  /**
   * Las Empresas asociadas a ese Holding
   *
   */
  public function empresas()
  {
    return $this->hasMany('App\Empresa');
  }
  
  
  /**
   * Retorna el Presupuesto de ese Holding
   *
   * @return App\Presupuesto
   */
  public function presupuesto()
  {
      return $this->morphOne('App\Presupuesto', 'presupuesteable');
  }

  /**
   * Retorna true si el Holding tiene un Presupuesto creado
   *
   * @return bool
   */
  public function hasPresupuesto()
  {
      return $this->presupuesto()->get()->isNotEmpty();
  }
  
}
