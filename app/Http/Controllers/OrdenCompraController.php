<?php

namespace App\Http\Controllers;

use App\Cierre;
use App\Empresa;
use App\Exports\ArrayExport;
use App\OrdenCompra;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class OrdenCompraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): View
    {
        if (Auth::user()->userable instanceof \App\CompassRole) {
            $empresas = Empresa::all();
            return view("orden_compra.index")->with(compact("empresas"));
        }
        return view("orden_compra.index");
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function filtrar(Request $request): View
    {
        $inicio = $request->input("inicio");
        $fin = $request->input("fin");

        $cierres = Cierre::whereDate("desde", ">=", $inicio)->whereDate("hasta", "<=", $fin);

        if (Auth::user()->userable instanceof \App\CompassRole) {
            $empresas = Empresa::all();
            $empresa = $request->input("empresa_id");

            $cierres = $cierres->where("empresa_id", $empresa)->get();
            return view("orden_compra.index")->with(compact("empresas", "cierres"));
        }

        $cierres = $cierres->where("empresa_id", Auth::user()->userable->id)->get();
        return view("orden_compra.index")->with(compact("cierres"));
    }

    public function cierre(Cierre $cierre): View
    {
        $ordenes = $cierre->ordenesCompra()->get();
        return view("orden_compra.cierre")->with(compact("ordenes", "cierre"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Cierre $cierre): View
    {
        $centros = $cierre->empresa->centros;
        $route = route("orden_compra_store", $cierre);

        return view("orden_compra.create-edit")->with(compact("centros", "cierre", "route"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Cierre $cierre, Request $request)
    {
        $form = $request->input();

        $total = 0;
        foreach ($form as $input => $value) {
            if (str_contains($input, "centro-") && floatval($value) > 0) {
                $monto = floatval($value);

                $total += $monto;
            }
        }

        if ($total <> floatval($form["monto"])) {
            $error = "La suma del monto de los centros no es igual al monto del documento.";
            return redirect()->back()->withInput()->with("error", $error);
        }

        $path = "";
        if ($request->hasFile("documento") && $request->file("documento")->isValid()) {
            $path = $request->file("documento")->store(
                'documentos',
                "real_public"
            );
        }
        $attributes = [
            "cierre_id" => $cierre->id,
            "fecha" => $form["fecha"],
            "folio" => $form["folio"],
            "monto" => $form["monto"],
            "documento" => $path
        ];
        $ordenCompra = OrdenCompra::create($attributes);

        foreach ($form as $input => $value) {
            if (str_contains($input, "centro-") && floatval($value) > 0) {
                $idString = explode("-", $input);
                $id = $idString[1];
                $monto = floatval($value);

                $ordenCompra->centros()->attach($id, ["monto" => $monto]);
            }
        }

        return redirect(route("orden_compra_cierre", $cierre), 201);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\OrdenCompra  $ordenCompra
     * @return \Illuminate\Http\Response
     */
    public function edit(OrdenCompra $ordenCompra): View
    {
        $cierre = $ordenCompra->cierre;
        $route = route("orden_compra_update", $ordenCompra);

        $centrosEmpresa = $cierre->empresa->centros;
        $centrosOrden = $ordenCompra->centros;

        $centros = $centrosEmpresa->merge($centrosOrden);

        return view("orden_compra.create-edit")->with(compact("centros", "cierre", "route", "ordenCompra"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\OrdenCompra  $ordenCompra
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OrdenCompra $ordenCompra): RedirectResponse
    {
        $form = $request->input();
        $cierre = $ordenCompra->cierre;

        $total = 0;
        foreach ($form as $input => $value) {
            if (str_contains($input, "centro-") && floatval($value) > 0) {
                $monto = floatval($value);

                $total += $monto;
            }
        }

        if ($total <> floatval($form["monto"])) {
            $error = "La suma del monto de los centros no es igual al monto del documento.";
            return redirect()->back()->withInput()->with("error", $error);
        }

        $attributes = [
            "fecha" => $form["fecha"],
            "folio" => $form["folio"],
            "monto" => $form["monto"],
        ];

        if ($request->hasFile("documento") && $request->file("documento")->isValid()) {
            $path = $request->file("documento")->store(
                'documentos',
                "real_public"
            );

            $attributes["documento"] = $path;
        }


        $ordenCompra->update($attributes);

        $centros = [];
        foreach ($form as $input => $value) {
            if (str_contains($input, "centro-") && floatval($value) > 0) {
                $idString = explode("-", $input);
                $id = $idString[1];
                $monto = floatval($value);


                $centros[$id] = ["monto" => $monto];
            }
        }

        $ordenCompra->centros()->sync($centros);

        return redirect(route("orden_compra_cierre", $cierre), 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\OrdenCompra  $ordenCompra
     * @return \Illuminate\Http\Response
     */
    public function destroy(OrdenCompra $ordenCompra): RedirectResponse
    {
        $ordenCompra->centros()->detach();
        $ordenCompra->delete();

        return back();
    }

    /**
     * Generate excel file with resource.
     */
    public function export(Cierre $cierre)
    {
        $ordenes = $cierre->ordenesCompra()->get();

        $excelData = [
            ["EDP", "{$cierre->created_at} {$cierre->id}"],
            ["FOLIO", "MONTO", "FECHA"],
        ];

        foreach ($ordenes as $orden) {
            $excelData[] = [
                $orden->folio, $orden->monto, $orden->fecha
            ];
        }

        $export = new ArrayExport($excelData);
        return Excel::download($export, "orden-compra.xlsx");
    }
}
