<?php

namespace App\Http\Controllers;

use App\Cierre;
use App\Empresa;
use App\Exports\ArrayExport;
use App\NotaCreditoTributaria;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class NotaCreditoTributariaController extends Controller
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
            return view("nota_credito.index")->with(compact("empresas"));
        }
        return view("nota_credito.index");
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
            return view("nota_credito.index")->with(compact("empresas", "cierres"));
        }

        $cierres = $cierres->where("empresa_id", Auth::user()->userable->id)->get();
        return view("nota_credito.index")->with(compact("cierres"));
    }

    public function cierre(Cierre $cierre): View
    {
        $notas = $cierre->notasCredito()->get();
        return view("nota_credito.cierre")->with(compact("notas", "cierre"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Cierre $cierre): View
    {
        $centros = $cierre->empresa->centros;
        $route = route("nota_credito_store", $cierre);

        return view("nota_credito.create-edit")->with(compact("centros", "cierre", "route"));
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
        $notaCreditoTributaria = NotaCreditoTributaria::create($attributes);

        foreach ($form as $input => $value) {
            if (str_contains($input, "centro-") && floatval($value) > 0) {
                $idString = explode("-", $input);
                $id = $idString[1];
                $monto = floatval($value);

                $notaCreditoTributaria->centros()->attach($id, ["monto" => $monto]);
            }
        }

        return redirect(route("nota_credito_cierre", $cierre), 201);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\NotaCreditoTributaria  $notaCreditoTributaria
     * @return \Illuminate\Http\Response
     */
    public function edit(NotaCreditoTributaria $notaCreditoTributaria): View
    {
        $cierre = $notaCreditoTributaria->cierre;
        $route = route("nota_credito_update", $notaCreditoTributaria);

        $centrosEmpresa = $cierre->empresa->centros;
        $centrosOrden = $notaCreditoTributaria->centros;

        $centros = $centrosEmpresa->merge($centrosOrden);

        return view("nota_credito.create-edit")->with(compact("centros", "cierre", "route", "notaCreditoTributaria"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\NotaCreditoTributaria  $notaCreditoTributaria
     * @return \Illuminate\Http\Response
     */
    public function update(
        Request $request,
        NotaCreditoTributaria $notaCreditoTributaria
    ): RedirectResponse {
        $form = $request->input();
        $cierre = $notaCreditoTributaria->cierre;

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


        $notaCreditoTributaria->update($attributes);

        $centros = [];
        foreach ($form as $input => $value) {
            if (str_contains($input, "centro-") && floatval($value) > 0) {
                $idString = explode("-", $input);
                $id = $idString[1];
                $monto = floatval($value);


                $centros[$id] = ["monto" => $monto];
            }
        }

        $notaCreditoTributaria->centros()->sync($centros);

        return redirect(route("nota_credito_cierre", $cierre), 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\NotaCreditoTributaria  $notaCreditoTributaria
     * @return \Illuminate\Http\Response
     */
    public function destroy(NotaCreditoTributaria $notaCreditoTributaria): RedirectResponse
    {
        $notaCreditoTributaria->centros()->detach();
        $notaCreditoTributaria->delete();

        return back();
    }

    /**
     * Generate excel file with resource.
     */
    public function export(Cierre $cierre)
    {
        $notas = $cierre->notasCredito()->get();

        $excelData = [
            ["EDP", "{$cierre->created_at} {$cierre->id}"],
            ["FOLIO", "MONTO", "FECHA"],
        ];

        foreach ($notas as $nota) {
            $excelData[] = [
                $nota->folio, $nota->monto, $nota->fecha
            ];
        }

        $export = new ArrayExport($excelData);
        return Excel::download($export, "nota-credito.xlsx");
    }
}
