<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

class FormatoAsignacionEmpresas implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $header = collect(['producto']);
        $body = collect([]);

        $productos = \App\Producto::all();
        $empresas = \App\Empresa::all();

        $empresas->map(function ($empresa) use ($header) {
            $header->push($empresa->razon_social);
        });

        $productos->map(function ($producto) use ($body, $empresas) {
            $row = collect([$producto->detalle]);
            $empresas->map(function ($empresa) use ($row) {
                $row->push('');
            });
            $body->push($row);
        });

        return collect([
            $header, $body
        ]);
    }
}
