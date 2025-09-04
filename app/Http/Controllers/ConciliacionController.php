<?php

namespace App\Http\Controllers;

use App\Cierre;
use App\Empresa;
use App\Exports\ArrayExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class ConciliacionController extends Controller
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
            return view("conciliacion.index")->with(compact("empresas"));
        }
        return view("conciliacion.index");
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
            return view("conciliacion.index")->with(compact("empresas", "cierres"));
        }

        $cierres = $cierres->where("empresa_id", Auth::user()->userable->id)->get();
        return view("conciliacion.index")->with(compact("cierres"));
    }

    public function conciliacion(Cierre $cierre): View
    {
        $empresa = $cierre->empresa;
        $centros = $empresa->centros;
        $inicio = $cierre->desde;
        $fin = $cierre->hasta;

        $ordenesCompra = $cierre->ordenesCompra;
        $totalOrdenesCompra = $ordenesCompra->reduce(function ($carry, $item) {
            return $carry + $item->monto;
        });


        $notasCreditoTributaria = $cierre->notasCredito;
        $totalNotasCredito = $notasCreditoTributaria->reduce(function ($carry, $item) {
            return $carry + $item->monto;
        });
        $totalNotasCreditoPF = 0;


        $facturasElectronica = $cierre->facturasElectronica;
        $totalFacturasElectronica = $facturasElectronica->reduce(function ($carry, $item) {
            return $carry + $item->monto;
        });

        $totalNeto = 0;

        $conciliacion = [];
        foreach ($centros as $centro) {
            $requerimientos = $empresa->requerimientos()
                ->whereIn("estado", ["RECIBIDO", "RECIBIDO CON OBSERVACIONES"])
                ->whereHas("guiasDespacho", function ($query) use ($inicio, $fin) {
                    $query->whereDate("fecha", ">=", $inicio)
                        ->whereDate("fecha", "<=", $fin)
                        ->whereNotNull("febos_id");
                })
                ->where("centro_id", $centro->id)
                ->orderBy("centro_id")
                ->orderBy("created_at")
                ->get();

            $ordenesCentro = $centro
                ->ordenesCompras()
                ->whereIn("orden_compra_id", $ordenesCompra->modelKeys())
                ->get();

            $notasCentro = $centro
                ->notasCredito()
                ->whereIn("nota_credito_tributaria_id", $notasCreditoTributaria->modelKeys())
                ->get();

            $neto = 0;
            $notaCreditoProforma = 0;
            foreach ($requerimientos as $requerimiento) {
                $neto += $requerimiento->neto;
                $notaCreditoProforma += $requerimiento->notaCreditoProforma;
            }

            $ordenCompra = 0;
            foreach ($ordenesCentro as $orden) {
                $ordenCompra += $orden->pivot->monto;
            }

            $notasTributaria = 0;
            foreach ($notasCentro as $nota) {
                $notasTributaria += $nota->pivot->monto;
            }

            $totalNeto += $neto;
            $totalNotasCreditoPF += $notaCreditoProforma;


            $conciliacion[] = [
                "centro" => $centro,
                "neto" => $neto,
                "ordenCompra" => $ordenCompra,
                "deltaNeto" => $neto - $ordenCompra,
                "notaCreditoProforma" => $notaCreditoProforma,
                "notaCreditoTributaria" => $notasTributaria,
                "deltaNC" => $notaCreditoProforma - $notasTributaria,
                "ordenes" => $ordenesCentro,
                "notas" => $notasCentro
            ];
        }

        $liquidacionDoc = $cierre->monto - $totalOrdenesCompra - $totalNotasCredito;
        $deltaLiq = $cierre->monto - $liquidacionDoc;
        $deltaNetoOC = $totalNeto - $totalOrdenesCompra;
        $deltaNC = $totalNotasCreditoPF - $totalNotasCredito;
        $cuadratura = $cierre->monto + $totalNotasCredito - $totalOrdenesCompra;


        return view("conciliacion.conciliacion")->with(compact(
            "conciliacion",
            "cierre",
            "ordenesCompra",
            "notasCreditoTributaria",
            "facturasElectronica",
            "totalOrdenesCompra",
            "totalNotasCredito",
            "totalFacturasElectronica",
            "deltaNetoOC",
            "deltaNC",
            "cuadratura",
            "liquidacionDoc",
            "totalNeto",
            "totalNotasCreditoPF",
            "deltaLiq"
        ));
    }

    public function export(Cierre $cierre)
    {
        $empresa = $cierre->empresa;
        $centros = $empresa->centros;
        $inicio = $cierre->desde;
        $fin = $cierre->hasta;

        $ordenesCompra = $cierre->ordenesCompra;
        $totalOrdenesCompra = $ordenesCompra->reduce(function ($carry, $item) {
            return $carry + $item->monto;
        });


        $notasCreditoTributaria = $cierre->notasCredito;
        $totalNotasCredito = $notasCreditoTributaria->reduce(function ($carry, $item) {
            return $carry + $item->monto;
        });
        $totalNotasCreditoPF = 0;


        $facturasElectronica = $cierre->facturasElectronica;
        $totalFacturasElectronica = $facturasElectronica->reduce(function ($carry, $item) {
            return $carry + $item->monto;
        });

        $totalNeto = 0;

        $conciliacion = [];
        foreach ($centros as $centro) {
            $requerimientos = $empresa->requerimientos()
                ->whereIn("estado", ["RECIBIDO", "RECIBIDO CON OBSERVACIONES"])
                ->whereHas("guiasDespacho", function ($query) use ($inicio, $fin) {
                    $query->whereDate("fecha", ">=", $inicio)
                        ->whereDate("fecha", "<=", $fin)
                        ->whereNotNull("febos_id");
                })
                ->where("centro_id", $centro->id)
                ->orderBy("centro_id")
                ->orderBy("created_at")
                ->get();

            $ordenesCentro = $centro
                ->ordenesCompras()
                ->whereIn("orden_compra_id", $ordenesCompra->modelKeys())
                ->get();

            $notasCentro = $centro
                ->notasCredito()
                ->whereIn("nota_credito_tributaria_id", $notasCreditoTributaria->modelKeys())
                ->get();

            $neto = 0;
            $notaCreditoProforma = 0;
            foreach ($requerimientos as $requerimiento) {
                $neto += $requerimiento->neto;
                $notaCreditoProforma += $requerimiento->notaCreditoProforma;
            }

            $ordenCompra = 0;
            foreach ($ordenesCentro as $orden) {
                $ordenCompra += $orden->pivot->monto;
            }

            $notasTributaria = 0;
            foreach ($notasCentro as $nota) {
                $notasTributaria += $nota->pivot->monto;
            }

            $totalNeto += $neto;
            $totalNotasCreditoPF += $notaCreditoProforma;


            $conciliacion[] = [
                "centro" => $centro,
                "neto" => $neto,
                "ordenCompra" => $ordenCompra,
                "deltaNeto" => $neto - $ordenCompra,
                "notaCreditoProforma" => $notaCreditoProforma,
                "notaCreditoTributaria" => $notasTributaria,
                "deltaNC" => $notaCreditoProforma - $notasTributaria,
                "ordenes" => $ordenesCentro,
                "notas" => $notasCentro
            ];
        }

        $liquidacionDoc = $cierre->monto - $totalOrdenesCompra - $totalNotasCredito;
        $deltaLiq = $cierre->monto - $liquidacionDoc;
        $deltaNetoOC = $totalNeto - $totalOrdenesCompra;
        $deltaNC = $totalNotasCreditoPF - $totalNotasCredito;
        $cuadratura = $cierre->monto + $totalNotasCredito - $totalOrdenesCompra;

        $edp = date_format($cierre->created_at, 'd/m/Y') . " - " . $cierre->id;
        $excelData = [
            ["Liquidacion", $cierre->monto],
            ["Factura", $totalFacturasElectronica],
            ["Ordenes de Compra", $totalOrdenesCompra],
            ["Notas de credito", $totalNotasCredito],
            [""],
            ["EDP", "LIQ INTERNA", "LIQ EXTERNA", "DIF. LIQ", "MONTO", "OC", "DIF. MNT/OC", "NC PF", "NC T", "DIF. NC", "CUADRATURA"],
            [$edp, $cierre->monto, $liquidacionDoc, $deltaLiq, $totalNeto, $totalOrdenesCompra, $deltaNetoOC, $totalNotasCreditoPF, $totalNotasCredito, $deltaNC, $cuadratura],
            [""],
            ["CENTRO", "MONTO", "OC", "DIF. MONTO-OC", "NC PF", "NC Trib.", "DIF. NC"],
        ];

        foreach ($conciliacion as $centro) {
            $excelData[] = [
                $centro["centro"]->nombre, $centro["neto"], $centro["ordenCompra"], $centro["deltaNeto"], $centro["notaCreditoProforma"], $centro["notaCreditoTributaria"], $centro["deltaNC"]
            ];
        }

        $export = new ArrayExport($excelData);
        return Excel::download($export, "conciliacion.xlsx");
    }
}
