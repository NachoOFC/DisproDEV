<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GetProveedorProductoController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $proveedores = \App\Proveedor::all();
        $productos = \App\Bidon::all();

        return response()->json(compact('proveedores', 'productos'));
    }
}
