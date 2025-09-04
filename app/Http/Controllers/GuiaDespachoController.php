<?php

namespace App\Http\Controllers;

use App\GuiaDespacho;
use Illuminate\Http\Request;

class GuiaDespachoController extends Controller
{
    public function index(\App\Transporte $transporte)
    {
        $requerimientos = $transporte->requerimientos;
        $requerimientos->load("guiasDespacho", "guiasDespacho.productos");

        return view('guia_despacho/index')->with(compact('requerimientos'));
    }

    public function show(GuiaDespacho $guiaDespacho)
    {
        $create = $guiaDespacho->crearDTE();
        if ($create) {
            $response = $guiaDespacho->obtenerDTE();
            return response()->json($response);
        } else {
            return response()->json([
                "status" => "error"
            ]);
        }
    }

    public function pdf(GuiaDespacho $guiaDespacho)
    {
        if (!empty($guiaDespacho->febosId)) {
            $response = $guiaDespacho->obtenerDTE();
            if ($response) {
                $pdf = json_decode($response, true);
                return redirect()->away($pdf["imagenLink"]);
            }
        }
        abort(404);
    }

}
