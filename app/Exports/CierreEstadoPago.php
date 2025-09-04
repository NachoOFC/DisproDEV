<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class CierreEstadoPago implements WithMultipleSheets
{
    use Exportable;

    protected $estadoPago;
    protected $detViveres;
    protected $detGuia;
    protected $liquidacion;

    public function __construct(array $estadoPago, array $detViveres, array $detGuia, array $liquidacion)
    {
        $this->estadoPago = $estadoPago;
        $this->detViveres = $detViveres;
        $this->detGuia = $detGuia;
        $this->liquidacion = $liquidacion;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];

        $sheets[] = new ArrayExport($this->estadoPago);
        $sheets[] = new ArrayExport($this->detViveres);
        $sheets[] = new ArrayExport($this->detGuia);
        $sheets[] = new ArrayExport($this->liquidacion);

        return $sheets;
    }
}
