<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReporteController extends Controller
{

    public function showInventario()
    {
        $proveedores = \App\Proveedor::all();
        return view('reportes/inventario')->with(compact('proveedores'));
    }

    public function filterInventario(Request $request)
    {

        $proveedores = \App\Proveedor::all();
        $proveedor = \App\Proveedor::find($request->proveedor_id);
        $filter = [
            "fecha_inicio" => $request->fecha_inicio,
            "fecha_fin" => $request->fecha_fin,
        ];

        $inventario = $proveedor->inventarioFinal($filter);

        return view('reportes/inventario')->with(compact('inventario', 'proveedores'));
    }


    public function showEntrada()
    {
        $proveedores = \App\Proveedor::all();
        return view('reportes/entrada')->with(compact('proveedores'));
    }

    public function filterEntrada(Request $request)
    {

        $proveedores = \App\Proveedor::all();
        $proveedor = \App\Proveedor::find($request->proveedor_id);
        $filter = [
            "fecha_inicio" => $request->fecha_inicio,
            "fecha_fin" => $request->fecha_fin,
        ];

        $inventario = $proveedor->inventarioEntrada($filter);

        return view('reportes/entrada')->with(compact('inventario', 'proveedores'));
    }

    public function showSalida()
    {
        $proveedores = \App\Proveedor::all();
        return view('reportes/salida')->with(compact('proveedores'));
    }

    public function filterSalida(Request $request)
    {
        $proveedores = \App\Proveedor::all();
        $proveedor = \App\Proveedor::find($request->proveedor_id);
        $filter = [
            "fecha_inicio" => $request->fecha_inicio,
            "fecha_fin" => $request->fecha_fin,
        ];

        $inventario = $proveedor->inventarioSalida($filter);

        return view('reportes/salida')->with(compact('inventario', 'proveedores'));
    }
}
