<?php

namespace App\Http\Controllers;

use App\Cierre;
use App\Empresa;
use App\Exports\ArrayExport;
use App\FacturaElectronica;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class FacturaElectronicaController extends Controller
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
            return view("factura_electronica.index")->with(compact("empresas"));
        }
        return view("factura_electronica.index");
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
            return view("factura_electronica.index")->with(compact("empresas", "cierres"));
        }

        $cierres = $cierres->where("empresa_id", Auth::user()->userable->id)->get();
        return view("factura_electronica.index")->with(compact("cierres"));
    }

    public function cierre(Cierre $cierre): View
    {
        $facturas = $cierre->facturasElectronica()->get();
        return view("factura_electronica.cierre")->with(compact("facturas", "cierre"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Cierre $cierre): View
    {
        $ordenes = $cierre->ordenesCompra()->get();
        $route = route("factura_electronica_store", $cierre);

        return view("factura_electronica.create-edit")->with(compact("ordenes", "cierre", "route"));
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
            if (str_contains($input, "orden-") && floatval($value) > 0) {
                $monto = floatval($value);

                $total += $monto;
            }
        }

        if ($total <> floatval($form["monto"])) {
            $error = "La suma del monto de las OCs no es igual al monto del documento.";
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
        $facturaElectronica = FacturaElectronica::create($attributes);

        foreach ($form as $input => $value) {
            if (str_contains($input, "orden-") && floatval($value) > 0) {
                $idString = explode("-", $input);
                $id = $idString[1];
                $monto = floatval($value);

                $facturaElectronica->ordenesCompra()->attach($id, ["monto" => $monto]);
            }
        }

        return redirect(route("factura_electronica_cierre", $cierre), 201);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\FacturaElectronica  $facturaElectronica
     * @return \Illuminate\Http\Response
     */
    public function edit(FacturaElectronica $facturaElectronica): View
    {
        $cierre = $facturaElectronica->cierre;
        $route = route("factura_electronica_update", $facturaElectronica);

        $ordenesCierre = $cierre->ordenesCompra;
        $ordenesFactura = $facturaElectronica->ordenesCompra;

        $ordenes = $ordenesCierre->merge($ordenesFactura);

        return view("factura_electronica.create-edit")->with(compact("ordenes", "cierre", "route", "facturaElectronica"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\FacturaElectronica  $facturaElectronica
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FacturaElectronica $facturaElectronica): RedirectResponse
    {
        $form = $request->input();
        $cierre = $facturaElectronica->cierre;

        $total = 0;
        foreach ($form as $input => $value) {
            if (str_contains($input, "orden-") && floatval($value) > 0) {
                $monto = floatval($value);

                $total += $monto;
            }
        }

        if ($total <> floatval($form["monto"])) {
            $error = "La suma del monto de las OCs no es igual al monto del documento.";
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


        $facturaElectronica->update($attributes);

        $ordenes = [];
        foreach ($form as $input => $value) {
            if (str_contains($input, "orden-") && floatval($value) > 0) {
                $idString = explode("-", $input);
                $id = $idString[1];
                $monto = floatval($value);


                $ordenes[$id] = ["monto" => $monto];
            }
        }

        $facturaElectronica->ordenesCompra()->sync($ordenes);

        return redirect(route("factura_electronica_cierre", $cierre), 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\FacturaElectronica  $facturaElectronica
     * @return \Illuminate\Http\Response
     */
    public function destroy(FacturaElectronica $facturaElectronica): RedirectResponse
    {
        $facturaElectronica->ordenesCompra()->detach();
        $facturaElectronica->delete();

        return back();
    }

    /**
     * Generate excel file with resource.
     */
    public function export(Cierre $cierre)
    {
        $facturas = $cierre->facturasElectronica()->get();

        $excelData = [
            ["EDP", "{$cierre->created_at} {$cierre->id}"],
            ["FOLIO", "MONTO", "FECHA"],
        ];

        foreach ($facturas as $factura) {
            $excelData[] = [
                $factura->folio, $factura->monto, $factura->fecha
            ];
        }

        $export = new ArrayExport($excelData);
        return Excel::download($export, "factura-electronica.xlsx");
    }
}
