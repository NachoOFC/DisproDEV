<?php

namespace App\Http\Controllers;

use App\Exports\ArrayExport;
use App\Rechazo;
use App\TipoObservacion;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class RechazoController extends Controller
{
    public function show(\App\Requerimiento $requerimiento)
    {
        $guiasDespacho = $requerimiento->guiasDespacho;

        $observados = collect([]);

        foreach ($guiasDespacho as $guia) {
            foreach ($guia->productos as $producto) {
                if ($producto->pivot->tipo_observacion_id > 1) {
                    $observados->push(["producto" => $producto, "guia" => $guia, "motivo" => TipoObservacion::find($producto->pivot->tipo_observacion_id)]);
                }
            }
        }

        return view("requerimiento.rechazo.show", compact("observados", "requerimiento"));
    }

    public function showExport(\App\Requerimiento $requerimiento)
    {
        $excelData = [
            ["FOLIO GUIA", "DETALLE", "DESPACHADO", "RECIBIDO", "TIPO OBS.", "MOTIVO", "COMENTARIO"]
        ];

        foreach ($requerimiento->guiasDespacho as $guia) {
            foreach ($guia->productos as $producto) {
                if ($producto->pivot->tipo_observacion_id > 1) {
                    $observacion = TipoObservacion::find($producto->pivot->tipo_observacion_id);
                    $excelData[] = [
                        $guia->folio,
                        $producto->detalle,
                        $producto->pivot->real,
                        $producto->pivot->cantidad_recibido,
                        $observacion->estado,
                        $observacion->nombre,
                        $producto->pivot->observacion
                    ];
                }
            }
        }


        $export = new ArrayExport($excelData);
        return Excel::download($export, "Observaciones {$requerimiento->nombre}.xlsx");
    }

    public function estadoView(\App\GuiaDespacho $guiaDespacho = null)
    {
        if (isset($guiaDespacho)) {

            $rechazos = $guiaDespacho->rechazos;
        } else {
            $empresa = \Auth::user()->userable;
            $requerimientos = $empresa->requerimientos()->where("estado", "RECIBIDO CON OBSERVACIONES")->get();
            $requerimientos->load("productosRechazados");
            $rechazos = $requerimientos->flatMap(function ($requerimiento) {
                return $requerimiento->productosRechazados;
            });
        }

        return view("requerimiento.rechazo.estado", compact("rechazos"));
    }

    public function cambiarEstado(\App\Rechazo $rechazo, Request $request)
    {
        $rechazo->estadoPago = $request->state;
        $rechazo->save();

        return response('OK', 200);
    }

    public function guardarEstados(Request $request)
    {
        Rechazo::whereIn("id", json_decode($request->rechazos))
            ->update(["cierre" => true]);

        return redirect()->route("home");
    }
}
