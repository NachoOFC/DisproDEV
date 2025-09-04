<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;


class RequerimientosImport implements ToCollection, WithHeadingRow
{
    public $centro;

    public function __construct(\App\Centro $centro)
    {
        $this->centro = $centro;
    }
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        $centro = $this->centro;
        $requerimiento = new \App\Requerimiento;
        $requerimiento->nombre = date("Y-m-d") . ' '
            .$centro->empresa->razon_social . ': '
            .$centro->nombre . ': '
            .(is_null(\App\Requerimiento::latest()->first()) ? 0 : \App\Requerimiento::latest()->first()->id);
        $requerimiento->dotacion = $centro->dotacion;
        $requerimiento->estado = "EN BODEGA";
        $centro->requerimientos()->save($requerimiento);
        $productos = $centro->empresa->productosVigentes;

        foreach ($collection as $row)
        {
            Log::info($row);
            $producto = $productos->where('sku', $row['sku'])->first();
            if (!is_null($producto)) {
                $requerimiento->productos()->attach($producto, ["cantidad" => floatval($row['cantidad']), "precio" => $producto->venta]);
            }
        }
    }
}
