<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class FacturaElectronica extends Model
{
    protected $fillable = ["cierre_id", "fecha", "folio", "monto", "documento"];

    public function cierre(): BelongsTo
    {
        return $this->belongsTo("App\Cierre");
    }

    public function ordenesCompra(): BelongsToMany
    {
        return $this->belongsToMany("App\OrdenCompra")->withPivot("monto");
    }
}
