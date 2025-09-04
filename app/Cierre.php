<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cierre extends Model
{
    use SoftDeletes;

    protected $fillable = ["empresa_id", "desde", "hasta", "monto"];

    public function empresa(): BelongsTo
    {
        return $this->belongsTo("App\Empresa");
    }

    public function ordenesCompra(): HasMany
    {
        return $this->hasMany("App\OrdenCompra");
    }

    public function notasCredito(): HasMany
    {
        return $this->hasMany("App\NotaCreditoTributaria");
    }

    public function facturasElectronica(): HasMany
    {
        return $this->hasMany("App\FacturaElectronica");
    }
}
