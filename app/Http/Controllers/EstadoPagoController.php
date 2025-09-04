<?php

namespace App\Http\Controllers;

use App\Centro;
use App\Cierre;
use App\Empresa;
use App\Exports\ArrayExport;
use App\Exports\CierreEstadoPago;
use App\GuiaDespacho;
use App\Mail\Reclamo;
use App\Mail\EstadoPagoActualizado;
use App\Producto;
use App\TipoObservacion;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;

class EstadoPagoController extends Controller
{

    public function cuadroEstadoGeneral(Request $request)
    {
        $type = Auth::user()->userable;

        if ($type instanceof \App\CompassRole) {
            $empresas = Empresa::all();
            return view("estado_pago.general", compact("empresas"));
        }

        return view("estado_pago.general");
    }

    public function generateEstadoGeneral(Request $request)
    {
        $inicio = $request->input("inicio");
        $fin = $request->input("fin");

        $guias = GuiaDespacho::whereHas("productos", function (Builder $query) {
            $query->whereIn("tipo_observacion_id", [1, 2, 3, 4, 5, 6, 7, 8]);
        });

        if (isset($inicio)) {
            $guias = $guias->whereDate("fecha", ">=", $inicio);
        } else {
            $inicio = Carbon::now()->subMonth();
            $guias = $guias->whereDate("fecha", ">=", $inicio->format("Y-m-d"));
        }

        if (isset($fin)) {
            $guias = $guias->whereDate("fecha", "<=", $fin);
        } else {
            $fin = Carbon::now();
            $guias = $guias->whereDate("fecha", "<=", $fin->format("Y-m-d"));
        }

        $centros = [];
        if (null !== $request->input("empresa_id")) {
            $empresa = Empresa::find($request->input("empresa_id"));
            $centros = $empresa->centros;
        } else {
            $empresa = Auth::user()->userable;
            $centros = $empresa->centros;
        }
        $guias = $guias->whereHas("requerimiento", function (Builder $query) use ($centros) {
            $query->whereIn("centro_id", $centros->modelKeys());
        });

        $guias = $guias->orderBy("nombre_centro", "asc")->get();

        $aceptadas = collect();
        $rechazadas = collect();
        $observadas = collect();

        foreach ($guias as $guia) {
            if ($guia->hasAceptadas()) {
                $aceptadas->push($guia);
            }

            if ($guia->hasRechazadas()) {
                $rechazadas->push($guia);
            }

            if ($guia->hasObservadas()) {
                $observadas->push($guia);
            }
        }



        $empresas = [];
        if (Auth::user()->userable instanceof \App\CompassRole) {
            $empresas = Empresa::all();
        }

        return view("estado_pago.general", compact("aceptadas", "rechazadas", "observadas", "empresas"));
    }

    public function concepto(GuiaDespacho $guiaDespacho, Request $request)
    {
        $ids = explode(",", $request->input("concepto"));
        $tipoObservaciones = TipoObservacion::whereIn("id", $ids)->get();
        $productos = $guiaDespacho->productos()->wherePivotIn("tipo_observacion_id", $ids)->get();
        $storeRoute = route("estado_pago_concepto_store", [$guiaDespacho]);
        $actualizacionRoute = route("estado_pago_actualizado", ["guiaDespacho" => $guiaDespacho, "concepto" => implode(",", $ids)]);
        $observaciones = TipoObservacion::where("id", ">=", 2)->get();

        return view("estado_pago.concepto", compact("guiaDespacho", "tipoObservaciones", "productos", "storeRoute", "observaciones", "actualizacionRoute"));
    }

    public function conceptoStore(GuiaDespacho $guiaDespacho, Request $request)
    {
        $producto = $request->input("producto");
        $producto["pivot"]["liquidado"] = true;
        $guiaDespacho->productos()->updateExistingPivot($producto["id"], $producto["pivot"]);

        return response()->json($producto);
    }

    public function conceptoMassiveStore(GuiaDespacho $guiaDespacho, Request $request)
    {
        $productos = $request->input();
        foreach ($productos as $producto) {
            $producto["pivot"]["liquidado"] = true;
            $guiaDespacho->productos()->updateExistingPivot($producto["id"], $producto["pivot"]);
        }

        return response()->json($productos);
    }

    public function generarReclamo(GuiaDespacho $guiaDespacho, Producto $producto, Request $request)
    {
        $user = Auth::user();
        $destinatarios = User::where("userable_type", "App\CompassRole")->where("userable_id", 1)->get();
        $message = $request->input("message");
        Mail::to($destinatarios)->send(new Reclamo($guiaDespacho, $producto, $user, $message));
        return response()->json();
    }

    public function enviarActualizacion(GuiaDespacho $guiaDespacho, Request $request)
    {
        $ids = explode(",", $request->input("concepto"));
        $tipoObservaciones = TipoObservacion::whereIn("id", $ids)->get();
        $empresa = $guiaDespacho->requerimiento->centro->empresa;
        $users = User::where("userable_type", "App\Empresa")->where("userable_id", $empresa->id)->where('logistica', 1)->get();
        $emails = [];
        foreach ($users as $user) {
            $emails[] = $user->email;
        }
        try {
            Mail::to($emails)->send(new EstadoPagoActualizado($guiaDespacho, $tipoObservaciones));
        } catch (\Exception $e) {
        }
        return response()->json();
    }

    public function resumen(Request $request)
    {
        $user = Auth::user();
        $empresas = null;
        $centros = null;
        $zonas = null;

        if ($user->userable instanceof \App\CompassRole) {
            $empresas = Empresa::all();
            $centros = Centro::all();
            $zonas =  \App\Abastecimiento::all();
        } else {
            $centros = $user->userable->centros;
        }


        return view("estado_pago.resumen", compact("empresas", "centros", "zonas"));
    }

    public function generarResumen(Request $request)
    {
        $inicio = $request->input("inicio");
        $fin = $request->input("fin");

        $requerimientos = collect();
        $isLogistica = Auth::user()->userable instanceof \App\Empresa;

        if (isset($request->empresas) || ($isLogistica && !isset($request->centros))) {
            if ($isLogistica) {
                $reqEmpresas = Auth::user()->userable->requerimientos()
                    ->whereHas("guiasDespacho", function ($query) use ($inicio, $fin) {
                        $query->whereDate("fecha", ">=", $inicio)
                            ->whereDate("fecha", "<=", $fin)
                            ->whereNotNull("febos_id");
                    })
                    ->orderBy("created_at")
                    ->get();
                $reqEmpresas->load("guiasDespacho");
                $requerimientos->push($reqEmpresas);
                $requerimientos = $requerimientos->flatten();
            } else {
                $empresas = explode(",", $request->empresas);
                foreach ($empresas as $empresa) {
                    $empresa = \App\Empresa::find($empresa);
                    $reqEmpresas = $empresa->requerimientos()
                        ->whereHas("guiasDespacho", function ($query) use ($inicio, $fin) {
                            $query->whereDate("fecha", ">=", $inicio)
                                ->whereDate("fecha", "<=", $fin)
                                ->whereNotNull("febos_id");
                        })
                        ->orderBy("created_at")
                        ->get();
                    $reqEmpresas->load("guiasDespacho");
                    $requerimientos->push($reqEmpresas);
                }
                $requerimientos = $requerimientos->flatten();
            }
        } elseif (isset($request->centros)) {
            $centros = explode(",", $request->centros);
            $requerimientos = \App\Requerimiento::whereIn("centro_id", $centros)
                ->whereHas("guiasDespacho", function ($query) use ($inicio, $fin) {
                    $query->whereDate("fecha", ">=", $inicio)
                        ->whereDate("fecha", "<=", $fin)
                        ->whereNotNull("febos_id");
                })
                ->orderBy("created_at")
                ->get();
            $requerimientos->load("guiasDespacho");
        } elseif (isset($request->zonas)) {
            $abastecimientos = explode(",", $request->zonas);
            $centros = \App\Centro::whereIn("zona", $abastecimientos)->pluck("id")->toArray();
            $requerimientos = \App\Requerimiento::whereIn("centro_id", $centros)
                ->whereHas("guiasDespacho", function ($query) use ($inicio, $fin) {
                    $query->whereDate("fecha", ">=", $inicio)
                        ->whereDate("fecha", "<=", $fin)
                        ->whereNotNull("febos_id");
                })
                ->orderBy("created_at")
                ->get();
            $requerimientos->load("guiasDespacho");
        }

        $guiasDespacho = GuiaDespacho::whereIn("requerimiento_id", $requerimientos->pluck("id"))->distinct()->get();

        if ($request->has("excel") && boolval($request->input("excel"))) {
            $excelData[] = [
                ["CENTRO", "ID REQ.", "FOLIO", "FECHA", "MONTO", "NOTA CREDITO", "NC CONTENEDORES", "SIN NOTA CREDITO", "LIQUIDACION"],
            ];

            foreach ($guiasDespacho as $guia) {
                $excelData[] = [
                    $guia->nombre_centro,
                    $guia->requerimiento_id,
                    $guia->folio,
                    $guia->fecha,
                    number_format($guia->neto, 0, ".", ""),
                    number_format($guia->notaCredito, 0, ".", ""),
                    number_format($guia->notaCreditoContenedor, 0, ".", ""),
                    number_format($guia->sinNotaCredito, 0, ".", ""),
                    number_format($guia->liquidacion, 0, ".", ""),
                ];
            }

            $export = new ArrayExport($excelData);
            return Excel::download($export, "Resumen estado de pago.xlsx");
        }

        $user = Auth::user();
        $empresas = null;
        $centros = null;
        $zonas = null;

        if ($user->userable instanceof \App\CompassRole) {
            $empresas = Empresa::all();
            $centros = Centro::all();
            $zonas =  \App\Abastecimiento::all();
        } else {
            $centros = $user->userable->centros;
        }


        return view("estado_pago.resumen", compact("empresas", "centros", "zonas", "guiasDespacho", "isLogistica"));
    }

    public function cierre(Request $request)
    {
        if (Auth::user()->userable instanceof \App\Empresa) {
            return view("estado_pago.cierre");
        }

        $empresas = Empresa::all();
        return view("estado_pago.cierre", compact("empresas"));
    }

    public function marcarGuiaLiquidado(GuiaDespacho $guiaDespacho)
    {
        if (isset($guiaDespacho->liquidado)) {
            $guiaDespacho->liquidado = null;
        } else {
            $guiaDespacho->liquidado = Carbon::now();
        }
        $guiaDespacho->save();

        return back();
    }

    public function notaCredito(GuiaDespacho $guiaDespacho)
    {
        $neto = 0;
        $productos = $guiaDespacho->productos()->wherePivot("genera_nc", true)->get()->map(function (Producto $producto) {
            $cantidad = 0;
            if ($producto->pivot->tipo_observacion_id == 2) {
                $cantidad = $producto->pivot->real;
            } else {
                $cantidad = $producto->pivot->real - $producto->pivot->cantidad_recibido;
            }
            $producto["cntd"] = $cantidad;
            $producto["subtotal"] =  abs($cantidad * $producto->pivot->precio);
            return $producto;
        });
        foreach ($productos as $producto) {
            $neto += $producto["subtotal"];
        }
        $iva = $neto * 0.19;
        $total = $neto + $iva;
        return view("estado_pago.nota-credito", compact("guiaDespacho", "productos", "neto", "iva", "total"));
    }

    public function generarCierre(Request $request)
    {
        $inicio = $request->inicio;
        $fin = $request->fin;
        $empresa = null;

        if ($request->has("empresa")) {
            $empresa = Empresa::findOrFail($request->input("empresa"));
        } elseif (Auth::user()->userable instanceof \App\Empresa) {
            $empresa = Auth::user()->userable;
        } else {
            return back();
        }

        $requerimientos = $empresa->requerimientos()
            ->whereIn("estado", ["RECIBIDO", "RECIBIDO CON OBSERVACIONES"])
            ->whereHas("guiasDespacho", function ($query) use ($inicio, $fin) {
                $query->whereDate("fecha", ">=", $inicio)
                    ->whereDate("fecha", "<=", $fin)
                    ->whereNotNull("febos_id");
            })
            ->orderBy("centro_id")
            ->orderBy("created_at")
            ->get()
            ->load("guiasDespacho");

        $estadoPago = [
            ["ESTADO DE PAGO"],
            [""],
            ["PERIODO", "$inicio -> $fin"],
            ["CONTRATO", $empresa->razon_social],
            ["SECTOR", "PUERTO MONTT"],
            ["SERVICIOS", "VIVERES"],
            ["RESPONSABLE", ""],
            [""],
            ["RESUMEN SERVICIOS MENSUAL"],
            ["SERVICIOS", "TOTAL"]
        ];

        $detViveres = [
            ["PACK ABASTECIMIENTO CENTROS $inicio -> $fin"],
            [''],
            [
                'FECHA',
                'BODEGA ORIGEN',
                'TRATAMIENTO',
                'CENTRO',
                'GUIA',
                'PRODUCTO',
                'CANTIDAD DESPACHADA',
                'UNIT',
                'TOTAL',
                'OBSERVACION',
                "NOTA CREDITO",
                "CANTIDAD RECIBIDA"
            ]
        ];

        $detGuia = [
            ["CENTRO", "FECHA", "GUIA", "TOTAL"]
        ];

        $liquidacion[] = [
            ["CENTRO", "ID REQ.", "FOLIO", "FECHA", "MONTO", "NOTA CREDITO", "NC CONTENEDORES", "SIN NOTA CREDITO", "LIQUIDACION"],
        ];


        if ($requerimientos->count() > 0) {
            $total = 0;
            foreach ($requerimientos as $requerimiento) {
                if ($requerimiento->guiasDespacho->count() > 0) {
                    $guias = $requerimiento->guiasDespacho()->orderBy("created_at", "asc")->get();
                    $totalGuias = 0;
                    foreach ($guias as $guiaDespacho) {
                        if ($guiaDespacho->productos->count() > 0) {
                            $liquidacion[] = [
                                $guiaDespacho->nombre_centro,
                                $guiaDespacho->requerimiento_id,
                                $guiaDespacho->folio,
                                $guiaDespacho->fecha,
                                number_format($guiaDespacho->neto, 0, ".", ""),
                                number_format($guiaDespacho->notaCredito, 0, ".", ""),
                                number_format($guiaDespacho->notaCreditoContenedor, 0, ".", ""),
                                number_format($guiaDespacho->sinNotaCredito, 0, ".", ""),
                                number_format($guiaDespacho->liquidacion, 0, ".", ""),
                            ];

                            $total += $guiaDespacho->liquidacion;
                            $totalGuias += $guiaDespacho->neto;

                            foreach ($guiaDespacho->productos as $producto) {
                                $cantidad = $producto->pivot->real;
                                $subtotal = $producto->pivot->precio * $cantidad;
                                $tipoObservacion = TipoObservacion::find($producto->pivot->tipo_observacion_id);
                                $recibido = $producto->pivot->cantidad_recibido;
                                switch ($producto->pivot->tipo_observacion_id) {
                                    case 1:
                                        $recibido = $cantidad;
                                        break;
                                    case 2:
                                        $recibido = "0";
                                        break;
                                    default:
                                        $recibido = $producto->pivot->cantidad_recibido;
                                }
                                $detViveres[] = [
                                    date("d/m/Y", strtotime($guiaDespacho->created_at)),
                                    'PTO MONTT',
                                    'VTA',
                                    $requerimiento->centro->nombre,
                                    $guiaDespacho->folio,
                                    $producto->detalle,
                                    $cantidad,
                                    $producto->pivot->precio,
                                    number_format($subtotal, 0, ".", ""),
                                    $tipoObservacion->nombre,
                                    ($producto->pivot->genera_nc) ? "SI" : '',
                                    $recibido
                                ];
                            }

                            $detGuia[] = [$requerimiento->centro->nombre, $guiaDespacho->fecha, $guiaDespacho->folio, $guiaDespacho->neto];
                        }
                    }
                    $detGuia[] = ["", "Total {$guiaDespacho->fecha}", "", number_format($totalGuias, 0, ".", "")];
                }
            }
            $estadoPago[] = ["VIVERES MES CENTRO LOGISTICO", number_format($total, 0, ".", '')];

            if ($request->has("closed") && boolval($request->input("closed"))) {
                Cierre::create([
                    "empresa_id" => $empresa->id,
                    "desde" => $inicio,
                    "hasta" => $fin,
                    "monto" => $total
                ]);
            }
        }


        $export = new CierreEstadoPago($estadoPago, $detViveres, $detGuia, $liquidacion);
        return Excel::download($export, "cierre-{$empresa->razon_social}.xlsx");
    }
}
