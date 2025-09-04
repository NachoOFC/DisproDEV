<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompassRole extends Model
{
  /**
   * Get all Users of that Role.
   *
   */
  public function users()
  {
    return $this->morphMany('App\User', 'userable');
  }
  
}
