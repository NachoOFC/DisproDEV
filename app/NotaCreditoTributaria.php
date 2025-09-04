<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class NotaCreditoTributaria extends Model
{
    protected $fillable = ["cierre_id", "fecha", "folio", "monto", "documento"];

    public function cierre(): BelongsTo
    {
        return $this->belongsTo("App\Cierre");
    }

    public function centros(): BelongsToMany
    {
        return $this->belongsToMany("App\Centro")->withPivot("monto");
    }
}
