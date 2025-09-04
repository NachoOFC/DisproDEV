<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

class FormatoRebaja implements FromCollection
{
    private $producto;
    private $totalSolicitado;
    private $totalDespachado;
    private $nroRequerimientos;
    private $requerimientos;
    private $header;

    /**
     * @param \App\Producto $producto
     * @param String $inicio
     * @param String $fin
     */
    public function __construct(\App\Producto $producto, String $inicio, String $fin)
    {
        $this->producto = $producto;
        $this->requerimientos = $producto->requerimientos()->whereDate('created_at', '>=', $inicio)->whereDate('created_at', '<=', $fin)->get();
        $this->totalSolicitado = $this->requerimientos->reduce(function($carry, $requerimiento) {
            return $carry + $requerimiento->pivot->cantidad;
        });
        $this->totalDespachado = $this->requerimientos->reduce(function($carry, $requerimiento) {
            return $carry + $requerimiento->pivot->real;
        });
        $this->nroRequerimientos = $this->requerimientos->count();
        $this->header = collect([]);
        $this->header->push(['SKU', $this->producto->sku]);
        $this->header->push(['Detalle', $this->producto->detalle]);
        $this->header->push([]);
        $this->header->push(['Empresa', 'Centro', 'Requerimiento', 'Folio', 'Cantidad Solicitada', 'Cantidad Despachada', 'Diferencia', 'Observaciones']);
    }
        
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $body = collect([]);
        $producto = $this->producto;
        $this->requerimientos->map(function($requerimiento) use($body, $producto) {
            $row = collect([$requerimiento->centro->empresa->razon_social, $requerimiento->centro->nombre, $requerimiento->nombre, $requerimiento->folio ?? $requerimiento->id, $requerimiento->pivot->cantidad, $requerimiento->pivot->real, strval($requerimiento->pivot->real - $requerimiento->pivot->cantidad), $requerimiento->pivot->observaciones]);
            $body->push($row);
        });

        $body->push([]);
        $body->push(['', '', 'Total Solicitados', $this->totalSolicitado]);
        $body->push(['', '', 'Total Despachados', $this->totalDespachado]);
        $body->push(['', '', 'Total Diferencia', strval($this->totalDespachado - $this->totalSolicitado)]);
        $body->push(['', '', 'N° de Pedidos', $this->nroRequerimientos]);

        return collect([
            $this->header,
            $body
        ]);
    }
}
