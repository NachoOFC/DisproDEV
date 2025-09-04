<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class EmpresasMasivaImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        $empresas = \App\Empresa::all();
        $collection->map(function($row) use ($empresas) {
            $producto = \App\Producto::where('detalle', $row['producto'])->first();
            $empresas->map(function($empresa) use ($row, $producto) {
                $nombre = str_replace([",", "."], "", str_replace(" ", "_", strtolower($empresa->razon_social)));
                $empresa->productos()->updateExistingPivot(
                    $producto->id,
                    ['precio' => (is_int($row[$nombre]) ? $row[$nombre] : 0)]
                );
            });
        });
    }
}
