<?php

namespace App\Http\Controllers;

use App\Empresa;
use App\Exports\ArrayExport;
use App\Http\Requests\ProductoForm;
use App\Imports\ProductoImport;
use App\Producto;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Empresa $empresa)
    {
        $productos = $empresa->productosVigentes->flatten();

        return view('control/productos/index')->with(compact('productos', 'empresa'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Empresa $empresa)
    {
        return view('control/productos/create')->with(compact('empresa'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Empresa $empresa, Request $request)
    {
        $productos = $request->file("productos");

        $import = new ProductoImport($empresa);

        Excel::import($import, $productos);

        $msg = [
            'meta' => [
                'title' => 'Productos Importados',
                'msg' => "Carga finalizada."
            ]
        ];

        return back()->with(compact('msg'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function show(Empresa $empresa)
    {

        $productos = $empresa->productosVigentes;

        $excelData = [
            ["sku", "familia", "detalle", "marca", "formato", "costo", "venta", "desde", "hasta", "reemplazo"],
        ];

        if ($productos->count() > 0) {
            foreach ($productos as $producto) {
                $excelData[] = [
                    $producto->sku, $producto->familia,  $producto->detalle, $producto->marca, $producto->formato, $producto->costo,
                    $producto->venta, $producto->desde, $producto->hasta, $producto->reemplazo ? "Si" : ""
                ];
            }
        }

        $export =  new ArrayExport($excelData);
        return Excel::download($export, "Productos Vigentes {$empresa->razon_social}.xlsx");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function edit(Producto $producto)
    {
        return view('control/productos/edit')->with(compact('producto'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function update(ProductoForm $request, Producto $producto)
    {
        $producto->update($request->validated());

        return redirect()->route('empresas.productos.index', $producto->empresa);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function destroy(Producto $producto)
    {
        $producto->delete();

        return response()->json([
            "meta" => [
                "status" => "Producto Borrado",
                "msg" => "OK"
            ]
        ]);
    }

    /**
     * Generate an excel file with the required headers
     * to import Producto
     * @return Excel
     */
    public function formatoCargaMasiva()
    {
        $excelData = [
            ["sku",  "familia", "detalle", "marca", "formato", "costo", "venta", "desde", "hasta", "reemplazo"]
        ];

        $export = new ArrayExport($excelData);
        return Excel::download($export, "Productos.xlsx");
    }
}
