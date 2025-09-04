<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use PDF as TCPDF;

class Salida extends Model
{
    use SoftDeletes;

    protected $fillable = ['bidon_id', 'cantidad', 'fecha_ingreso'];

    protected $appends = ['editRoute', 'deleteRoute', 'bidon', 'qty'];

    public function getEditRouteAttribute()
    {
        return route('salidas.edit', $this->id);
    }

    public function getDeleteRouteAttribute()
    {
        return route('salidas.destroy', $this->id);
    }

    public function getBidonAttribute()
    {
        return $this->bidon()->first();
    }

    public function getCantidadAttribute($value)
    {
        return -intval($value);
    }

    public function getQtyAttribute()
    {
        return abs($this->cantidad);
    }

    public function getTipoDoctoAttribute()
    {
        return "S";
    }

    public function getConceptoAttribute()
    {
        return "Salida";
    }

    public function bidon()
    {
        return $this->belongsTo('App\Bidon');
    }

    public function generarPDF()
    {
        $fecha = date("d-m-Y H:i:s");
        $tipo = ucfirst(strtolower($this->tipo));
        $cliente = $this->bidon->proveedor;

        TCPDF::SetFont('helvetica', '', 10);
        TCPDF::AddPage();
        TCPDF::setFillColor(255, 255, 255);
        TCPDF::MultiCell(120, 5, "Compass Catering\nPuerto Montt - Los Lagos\n", 0, 'L', 0, 0);
        TCPDF::MultiCell(0, 5, "Puerto Montt: $fecha\nComprobante de salida n°: {$this->id}", 1, 'C', 0, 0);

        TCPDF::ln(25);

        TCPDF::Cell(60, 5, "Codigo", 1, 0, "C");
        TCPDF::Cell(60, 5, "Descripcion", "TRB", 0, "C");
        TCPDF::Cell(60, 5, "Cantidad", "TRB", 1, "C");

        TCPDF::Cell(60, 5, "{$this->bidon->codigo}", 1, 0, "C");
        TCPDF::Cell(60, 5, "{$this->bidon->nombre}", "TRB", 0, "C");
        TCPDF::Cell(60, 5, "{$this->qty}", "TRB", 1, "C");

        TCPDF::ln(10);

        TCPDF::Cell(60, 5, "", "B", 0);
        TCPDF::Cell(60, 5, "", 0, 0);
        TCPDF::Cell(60, 5, "", "B", 1);

        TCPDF::Cell(60, 5, "Firma Emisor", 0, 0, "C");
        TCPDF::Cell(60, 5, "", 0, 0);
        TCPDF::Cell(60, 5, "Firma Receptor", 0, 1, "C");

        TCPDF::SetFont('helvetica', '', 8);
        TCPDF::SetTextColor(66, 66, 66);

        TCPDF::Output('comprobante.pdf');

    }
}
