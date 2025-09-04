<?php

namespace App\Imports;

use App\Producto;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductosMasiva implements ToModel, WithHeadingRow
{
    /**
     * @param Collection $collection
     */
    public function model(array $row)
    {
        if (!isset($row['sku']) || !isset($row['detalle']) || !isset($row['precio_costo'])) {
            return null;
        }

        return new Producto([
            'sku' => $row['sku'],
            'detalle' => $row['detalle'],
            'costo' => intval($row['precio_costo']),
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);
    }
}
