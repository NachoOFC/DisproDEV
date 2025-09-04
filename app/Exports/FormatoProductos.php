<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

class FormatoProductos implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $header = collect(['SKU', 'Detalle', 'Precio Costo']);
        $body = collect([]);

        return collect([$header, $body]);
    }
}
