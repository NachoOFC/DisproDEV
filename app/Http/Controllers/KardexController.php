<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\KardexFilter;
use PDF;

class KardexController extends Controller
{
    public function show()
    {
        return view('reportes/kardex');
    }

    public function filter(KardexFilter $request)
    {
        $filter = $request->validated();

        $producto = \App\Bidon::find($filter['bidon_id']);


        $items = $producto->generateKardex($filter);

        $saldos = collect([]);

        $items->reduce(function($carry, $item) use ($saldos) {
            $sum = $carry + $item->cantidad;
            $saldos->push($sum);

            return $sum;
        });

        if (boolval($filter['pdf'])) {
            $date = date("d-m-Y");
            PDF::AddPage();
            PDF::setFillColor(255, 255, 255);
            PDF::Cell(60, 5, "Fecha: $date", 0, 1);
            PDF::Cell(60, 5, "Fecha", 1, 0);
            PDF::Cell(60, 5, "Tipo Docto", 1, 0);
            PDF::Cell(60, 5, "Folio", 1, 0);
            PDF::Cell(60, 5, "Concepto", 1, 0);
            PDF::Cell(60, 5, "Glosa", 1, 0);
            PDF::Cell(60, 5, "Entrada", 1, 0);
            PDF::Cell(60, 5, "Salida", 1, 0);
            PDF::Cell(60, 5, "Saldo", 1, 0);

            foreach($items as $index => $item) {
                PDF::Cell(60, 5, "{$item->fecha_ingreso}", 1, 0);
                PDF::Cell(60, 5, "{$item->tipoDocto}", 1, 0);
                PDF::Cell(60, 5, "{$item->id}", 1, 0);
                PDF::Cell(60, 5, "{$item->concepto}", 1, 0);
                PDF::Cell(60, 5, "{$item->bidon->proveedor->razon_social}", 1, 0);
                switch(get_class($item)) {
                case "App\Entrada":
                    PDF::Cell(60, 5, "{$item->qty}", 1, 0);
                    break;
                case "App\CargaInicial":
                    PDF::Cell(60, 5, "{$item->qty}", 1, 0);
                    break;
                default:
                    if ($item instanceof \App\Ajuste && boolval($item->suma)) {
                        PDF::Cell(60, 5, "{$item->qty}", 1, 0);
                    } else {
                        PDF::Cell(60, 5, "", 1, 0);
                    }
                    break;
                }

                switch(get_class($item)) {
                case "App\Salida":
                    PDF::Cell(60, 5, "{$item->qty}", 1, 0);
                    break;
                case "App\NotaCredito":
                    PDF::Cell(60, 5, "{$item->qty}", 1, 0);
                    break;
                default:
                    if ($item instanceof \App\Ajuste && !boolval($item->suma)) {
                        PDF::Cell(60, 5, "{$item->qty}", 1, 0);
                    } else {
                        PDF::Cell(60, 5, "", 1, 0);
                    }
                    break;
                }
                PDF::Cell(60, 5, "{$saldos[$index]}", 1, 0);

                PDF::Output("kardex.pdf");
            }
        } else {
            return view('reportes/kardex')->with(compact('items', 'saldos'));
        }


    }
}
