<?php

namespace App\Imports;

use App\Centro;
use App\Producto;
use App\Requerimiento;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class RequerimientoImport implements ToCollection, WithHeadingRow
{
    private $centro;
    public $err;

    public function __construct(Centro $centro)
    {
        $this->centro = $centro;
        $this->err = collect([]);
    }

    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        $nombre = date("Y-m-d")
                . " - " . $this->centro->empresa->razon_social
                . " - " . $this->centro->nombre
                . " - " . Requerimiento::count();

        $requerimiento = $this->centro->requerimientos()->create([
            "nombre" => $nombre,
            "dotacion" => $this->centro->dotacion,
        ]);

        $collection->map(function($row) use ($requerimiento) {
            if ($row["cantidad"] > 0) {
                $producto = Producto::where([
                    ["sku", $row["sku"]],
                    ["desde", '<=', date("Y-m-d")],
                    ["hasta", ">=", date("Y-m-d")],
                ])->orWhere([
                    ["sku", $row["sku"]],
                    ["desde", '<=', date("Y-m-d")],
                    ["hasta", null],
                ])->latest()
                    ->first();

                if (isset($producto)) {
                    $requerimiento->productos()->attach($producto, [
                        "cantidad" => floatval($row["cantidad"]),
                        "precio" => $producto->venta,
                    ]);
                } else {
                    $this->err->push($row);
                }
            }
        });

        return $requerimiento->productos;

    }
}
