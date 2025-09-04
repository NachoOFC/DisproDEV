<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoObservacion extends Model
{
    protected $fillable = ["estado", "nombre", "cantidad"];
}
