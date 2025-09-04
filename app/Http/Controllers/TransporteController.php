<?php

namespace App\Http\Controllers;

use App\Abastecimiento;
use App\Http\Requests\TransporteForm;
use App\Requerimiento;
use App\Transporte;

class TransporteController extends Controller
{

    public function create()
    {
        $requerimientos = Requerimiento::doesntHave('transporte')->where('estado', 'EN BODEGA')->whereNotNull('folio')->get();
        $abastecimientos = Abastecimiento::all();

        return view('compass.programar_despachos')->with(compact('requerimientos', 'abastecimientos'));
    }


    public function store(TransporteForm $request)
    {
        $form = $request->validated();
        $despacho = Transporte::create($form);

        $msg = [
            "meta" => [
                "title" => "Error!",
                "msg" => "No hay requerimientos seleccionados"
            ]
        ];

        if (isset($form["requerimientos"])) {
            foreach ($form["requerimientos"] as $requerimientoId) {
                $requerimiento = Requerimiento::find($requerimientoId);
                if ($requerimiento) {
                    $requerimiento->transporte()->associate($despacho);
                    $requerimiento->save();
                }
            }

            $msg = [
                "meta" => [
                    "title" => "¡Despacho Programado Exitosamente!",
                    "msg" => "El Despacho ha sido programado exitosamente"
                ]
            ];
        }


        return back()->with(compact('msg'));
    }

    public function generarGuia(\App\Transporte $transporte)
    {

        $exito = $transporte->requerimientos->map(function ($requerimiento) {
            return $requerimiento->generarGuiaDespacho();
        });

        if ($exito->contains(false)) {
            $msg = [
                "meta" => [
                    "title" => "Guias de Despacho Electronica Generadas",
                    "msg" => "No todas las guias de despachos pudieron ser generadas"
                ]
            ];
        } else {
            $msg = [
                "meta" => [
                    "title" => "Guias de Despacho Electronica Generadas",
                    "msg" => "Todas las guias de despacho fueron generadas exitosamente"
                ]
            ];
        }

        return response()->json($msg);
    }
}
