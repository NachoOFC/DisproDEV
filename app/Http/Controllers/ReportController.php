<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

use App\Exports\ArrayExport;
use App\Requerimiento;

class ReportController extends Controller
{
    /**
     * Muestra el Reporte de Productos por Cantidad por fecha
     *
     * @return \Illuminate\Http\Response
     */
    public function productosPorCantidad($mes = null, $year = null)
    {
        return redirect()->back();
    }

    /**
     * Muestra la pantalla para generacion de Packs
     *
     * @return \Illuminate\Http\Response
     */
    public function packs()
    {
        $empresas = \App\Empresa::all();
        $centros = \App\Centro::all();
        $zonas = \App\Abastecimiento::all();

        return view('reporte.packs')
            ->with(compact('empresas', "centros", "zonas"));
    }

    /**
     * Genera el pack de esa semana para la Empresa seleccionada
     *
     * @return \Illuminate\Http\Response
     */
    public function generarPack(Request $request)
    {
        $inicio = $request->inicio;
        $fin = $request->fin;

        $requerimientos = collect([]);

        if (isset($request->empresas)) {
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

        $nroRequerimientos = $requerimientos->count();

        $totalGasto = $requerimientos->reduce(function ($carry, $requerimiento) {
            return $carry + $requerimiento->getTotal();
        });

        $excelData = [
            [
                'Periodo Inicio',
                'Periodo Fin',
                'Total Ventas ($)',
                'Numero de Pedidos'
            ],
            [
                $inicio,
                $fin,
                $totalGasto,
                $nroRequerimientos
            ],
            [""],
            [
                'Fecha',
                'Bodega Origen',
                'Tratamiento',
                'Zona',
                'Destino',
                'Empresa',
                'N° Guia',
                'SKU',
                'Producto',
                'Cantidad',
                'P. Unitario',
                'Total',
                'Observaciones'
            ]
        ];

        if ($requerimientos->count() > 0) {
            foreach ($requerimientos as $requerimiento) {
                if ($requerimiento->guiasDespacho->count() > 0) {
                    foreach ($requerimiento->guiasDespacho as $guiaDespacho) {
                        if ($guiaDespacho->productos->count() > 0) {
                            foreach ($guiaDespacho->productos as $producto) {
                                $excelData[] = [
                                    date("d-m-Y", strtotime($guiaDespacho->created_at)),
                                    'PTO MONTT',
                                    'VTA',
                                    $requerimiento->centro->zona,
                                    $requerimiento->centro->nombre,
                                    $requerimiento->centro->empresa->razon_social,
                                    $guiaDespacho->folio,
                                    $producto->sku,
                                    $producto->detalle,
                                    $producto->pivot->real,
                                    $producto->pivot->precio,
                                    $producto->pivot->precio * $producto->pivot->real,
                                    $requerimiento->observaciones
                                ];
                            }
                        }
                    }
                }
            }
        }

        $export = new ArrayExport($excelData);
        return Excel::download($export, "reporte-pack-$inicio-al-$fin.xlsx");
    }

    /**
     * Vista para generar validaciones
     *
     */
    public function validaciones()
    {
        return view("reporte.validaciones");
    }

    public function generarValidaciones(Request $request)
    {
        $inicio = $request->inicio;
        $fin = $request->fin;

        $centros = Auth::user()->userable->centros;

        $ids = $centros->map(function ($centro) {
            return $centro->id;
        })->toArray();

        $requerimientos = \App\Requerimiento::whereIn("centro_id", $ids)
            ->whereHas("guiasDespacho", function ($query) use ($inicio, $fin) {
                $query->whereDate("fecha", ">=", $inicio)
                    ->whereDate("fecha", "<=", $fin)
                    ->whereNotNull("febos_id");
            })
            ->orderBy("created_at")
            ->get();
        $requerimientos->load("guiasDespacho");


        $nroRequerimientos = $requerimientos->count();

        $totalGasto = $requerimientos->reduce(function ($carry, $requerimiento) {
            return $carry + $requerimiento->getTotal();
        });

        $excelData = [
            [
                'Periodo Inicio', 'Periodo Fin', 'Total Ventas ($)',
                'Numero de Pedidos'
            ],
            [
                $inicio, $fin, $totalGasto,
                $nroRequerimientos
            ],
            [""],
            [
                "Zona", "Centro", "# Pedido", "Fecha Guia",
                "Folio Guia", "Monto Guia", "Monto Rechazado",
                "Diferencia", "Fecha Despacho", "Fecha Recepcion"
            ],
        ];

        if ($requerimientos->count() > 0) {
            foreach ($requerimientos as $requerimiento) {
                if ($requerimiento->guiasDespacho->count() > 0) {
                    foreach ($requerimiento->guiasDespacho as $guiaDespacho) {
                        $recibido = $requerimiento->detalleEstado("RECIBIDO")->created_at;
                        $observacion = $requerimiento->detalleEstado("RECIBIDO CON OBSERVACIONES")->created_at;
                        $fechaRecepcion = empty($recibido) ? $observacion : $recibido;
                        $fechaRecepcion = empty($fechaRecepcion) ? "Sin Recibir" : $fechaRecepcion;
                        $excelData[] = [
                            $guiaDespacho->direccion_destino,
                            $guiaDespacho->nombre_centro,
                            $guiaDespacho->requerimiento_id,
                            $guiaDespacho->fecha,
                            $guiaDespacho->folio,
                            $guiaDespacho->neto,
                            $guiaDespacho->montoRechazado,
                            $guiaDespacho->neto - $guiaDespacho->montoRechazado,
                            $requerimiento->detalleEstado("DESPACHADO")->created_at,
                            $fechaRecepcion
                        ];

                        if ($guiaDespacho->rechazos->count() > 0) {
                            $excelData[] = [
                                "", "", "", "", "",
                                "Rechazado", "Precio", "Despachado",
                                "Subtotal", "Motivo Rechazo"
                            ];
                            foreach ($guiaDespacho->rechazos as $rechazo) {
                                $excelData[] = [
                                    $rechazo->producto->detalle,
                                    $rechazo->producto->venta,
                                    $rechazo->productoGuia->real,
                                    $rechazo->productoGuia->real
                                        * $rechazo->producto->venta,
                                    $rechazo->motivo
                                ];
                            }
                        }
                    }
                }
            }
        }


        $export = new ArrayExport($excelData);
        return Excel::download($export, "$inicio-$fin.xlsx");
    }

    /**
     * Muestra la pantalla para la generacion de rebajas de Productos
     *
     * @return \Illuminate\Http\Response
     */
    public function productos()
    {
        $productos = \App\Producto::all();

        return view('reporte.productos')->with(compact('productos'));
    }

    /**
     * Generar las rebajas de Producto segun producto y rango de tiempo
     *
     * @return \Illuminate\Http\Response
     */
    public function generarRebajas(Request $request)
    {
        $producto = \App\Producto::find($request->input('nuevoProducto'));

        return redirect()->back();
    }


    public function historialView()
    {
        $empresas = \App\Empresa::all();
        $centros = \App\Centro::all();
        $zonas = \App\Abastecimiento::all();

        return view("reporte/historial_guias")
            ->with(compact("empresas", "centros", "zonas"));
    }

    /**
     * Generar historial de guias emitidas segun un rango de fechas
     *
     * @return \Illuminate\Http\Response
     */
    public function historial(Request $request)
    {
        $inicio = $request->inicio;
        $fin = $request->fin;

        $requerimientos = collect([]);

        if (isset($request->empresas)) {
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
                $reqEmpresas->load("guiasDespacho", "guiasDespacho.productos");
                $requerimientos->push($reqEmpresas);
            }
            $requerimientos = $requerimientos->flatten();
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
            $requerimientos->load("guiasDespacho", "guiasDespacho.productos");
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
            $requerimientos->load("guiasDespacho", "guiasDespacho.productos");
        }

        $empresas = \App\Empresa::all();
        $centros = \App\Centro::all();
        $zonas = \App\Abastecimiento::all();
        return view("reporte/historial_guias")
            ->with(compact("empresas", "centros", "zonas", "requerimientos"));
    }

    /**
     * Generar carta de requerimientos emitidas segun un rango de fechas
     *
     * @return \Illuminate\Http\Response
     */
    public function cartaRequerimientos(Request $request)
    {
        $inicio = $request->inicio;
        $fin = $request->fin;

        $requerimientos = collect([]);

        if (isset($request->empresas)) {
            $empresas = explode(",", $request->empresas);
            foreach ($empresas as $empresa) {
                $empresa = \App\Empresa::find($empresa);
                $reqEmpresas = $empresa->requerimientos()
                    ->whereDate("requerimientos.created_at", ">=", $inicio)
                    ->whereDate("requerimientos.created_at", "<=", $fin)
                    ->orderBy("created_at")
                    ->get();
                $requerimientos->push($reqEmpresas);
            }
            $requerimientos = $requerimientos->flatten();
        } elseif (isset($request->centros)) {
            $centros = explode(",", $request->centros);
            $requerimientos = \App\Requerimiento::whereIn("centro_id", $centros)
                ->whereDate("created_at", ">=", $inicio)
                ->whereDate("created_at", "<=", $fin)
                ->orderBy("created_at")
                ->get();
        } elseif (isset($request->zonas)) {
            $abastecimientos = explode(",", $request->zonas);
            $centros = \App\Centro::whereIn("zona", $abastecimientos)->pluck("id")->toArray();
            $requerimientos = \App\Requerimiento::whereIn("centro_id", $centros)
                ->whereDate("created_at", ">=", $inicio)
                ->whereDate("created_at", "<=", $fin)
                ->orderBy("created_at")
                ->get();
        }

        $empresas = \App\Empresa::all();
        $centros = \App\Centro::all();
        $zonas = \App\Abastecimiento::all();

        if ($request->input("generate")) {
            $excelData = [
                [
                    "Fecha Solicitud",
                    "Cliente",
                    "Centro",
                    "Area",
                    "SKU",
                    "Familia",
                    "Detalle",
                    "Marca",
                    "Formato",
                    "Cantidad",
                    "Precio Venta",
                    "Total",
                    "Observaciones"
                ]
            ];
            foreach ($requerimientos as $requerimiento) {
                foreach ($requerimiento->productos as $producto) {
                    $cantidad = $producto->pivot->real ?? $producto->pivot->cantidad;
                    $excelData[] = [
                        $requerimiento->created_at,
                        $requerimiento->centro->empresa->razon_social,
                        $requerimiento->centro->nombre,
                        $requerimiento->centro->zona,
                        $producto->sku,
                        $producto->familia,
                        $producto->detalle,
                        $producto->marca,
                        $producto->formato,
                        $cantidad,
                        $producto->venta,
                        ($cantidad * $producto->venta),
                        $producto->pivot->observacion,
                    ];
                }
            }

            $export = new ArrayExport($excelData);
            return Excel::download($export, "Consolidado de requerimientos.xlsx");
        }


        return view("reporte/historial_requerimientos")
            ->with(compact("empresas", "centros", "zonas", "requerimientos"));
    }

    public function cartasRequerimientoGenerate(Requerimiento $requerimiento)
    {
        $cliente = "{$requerimiento->centro->empresa->razon_social}: {$requerimiento->centro->nombre}";
        $excelData = [
            ["CLIENTE", $cliente],
            ["AREA", $requerimiento->centro->zona],
            ["FECHA DE SOLICITUD", $requerimiento->created_at],
            ["Centro", "SKU", "Familia", "Detalle", "Marca", "Formato", "Cantidad", "Precio Venta", "Total", "Observaciones"]
        ];

        $productos = $requerimiento->productos()->get();

        foreach ($productos as $producto) {
            $cantidad = $producto->pivot->real ?? $producto->pivot->cantidad;
            $excelData[] = [
                $requerimiento->centro->nombre,
                $producto->sku,
                $producto->familia,
                $producto->detalle,
                $producto->marca,
                $producto->formato,
                $cantidad,
                $producto->venta,
                ($cantidad * $producto->venta),
                $producto->pivot->observacion,
            ];
        }

        $export = new ArrayExport($excelData);
        return Excel::download($export, "{$requerimiento->nombre}.xlsx");
    }


    public function rebajaView()
    {
        $productos = \DB::table("productos")
            ->select("sku", "detalle")
            ->groupBy("sku")
            ->get();

        $empresas = \App\Empresa::all();
        $centros = \App\Centro::all();
        $zonas = \App\Abastecimiento::all();

        return view("reporte/rebaja")->with(compact("productos", "empresas", "centros", "zonas"));
    }

    public function rebaja(Request $request)
    {
        $inicio = $request->inicio;
        $fin = $request->fin;
        $skus = strlen($request->productos) ? explode(",", $request->productos) : null;

        $ids = null;
        $type = null;
        if (isset($request->empresas)) {
            $ids = collect(explode(",", $request->empresas));
            $type = "EMPRESA";
        } elseif (isset($request->centros)) {
            $ids = collect(explode(",", $request->centros));
            $type = "CENTRO";
        } elseif (isset($request->zonas)) {
            $ids = collect(explode(",", $request->zonas));
            $type = "ZONA";
        }

        $excelData = [
            ['Periodo Inicio', 'Periodo Fin'],
            [$inicio, $fin],
            [""],
        ];

        $productos = \App\Producto::whereHas("guiasDespacho", function ($query) use ($inicio, $fin) {
            $query->where("fecha", ">=", $inicio)
                ->where("fecha", "<=", $fin);
        });

        if (!empty($skus)) {
            $productos = $productos->whereIn("sku", $skus);
        }

        $productos = $productos->groupBy("sku")->get();

        if ($productos->count() > 0) {
            $excelData[] = [
                'Total por Productos:'
            ];
            $excelData[] = ['SKU', 'DETALLE', 'SOLICITADO', 'DESPACHADO', 'TOTAL'];
            foreach ($productos as $producto) {
                $cantidad = $producto->getDetalleGuia($inicio, $fin, $ids, $type);
                $excelData[] = [
                    $producto->sku,
                    $producto->detalle,
                    $cantidad["CANTIDAD"],
                    $cantidad["REAL"],
                    $cantidad["SUBTOTAL"]
                ];
            }

            $excelData[] = [""];
            $excelData[] = [
                'FECHA',
                'ZONA',
                'DESTINO',
                'EMPRESA',
                'N° GUIA',
                'PRODUCTO',
                'CANTIDAD',
                'P. UNITARIO',
                'TOTAL'
            ];


            foreach ($productos as $producto) {

                $guiasDespacho = $producto->guiasDespacho()->where([
                    ["fecha", ">=", $inicio],
                    ["fecha", "<=", $fin],
                ])->get()->load(
                    "requerimiento",
                    "requerimiento.centro",
                    "requerimiento.centro.empresa"
                );

                switch ($type) {
                    case "EMPRESA":
                        $guiasDespacho = $guiasDespacho->filter(function ($guia) use ($ids) {
                            return $ids->contains($guia->empresaId);
                        });
                        break;
                    case "CENTRO":
                        $guiasDespacho = $guiasDespacho->filter(function ($guia) use ($ids) {
                            return $ids->contains($guia->centroId);
                        });
                        break;
                    case "ZONA":
                        $guiasDespacho = $guiasDespacho->filter(function ($guia) use ($ids) {
                            return $ids->contains($guia->zonaId);
                        });
                        break;
                }

                if ($guiasDespacho->count() > 0) {
                    foreach ($guiasDespacho as $guia) {
                        $excelData[] = [
                            date("d-m-Y", strtotime($guia->created_at)),
                            $guia->requerimiento->centro->zona ?? "No Encontrado",
                            $guia->requerimiento->centro->nombre ?? "No Encontrado",
                            $guia->requerimiento->centro->empresa->razon_social ?? "No Encontrado",
                            $guia->folio,
                            $producto->detalle,
                            $guia->pivot->real,
                            $guia->pivot->precio,
                            $guia->pivot->precio * $guia->pivot->real,
                        ];
                    }
                }
            }
        }


        $export = new ArrayExport($excelData);
        return Excel::download($export, "rebaja-$inicio-$fin.xlsx");
    }

    public function estadoPagoView()
    {
        $empresas = \App\Empresa::all();
        $centros = \App\Centro::all();
        $zonas = \App\Abastecimiento::all();

        return view("reporte/estado_pago")
            ->with(compact("empresas", "centros", "zonas"));
    }

    public function estadoPago(Request $request)
    {
        $inicio = $request->inicio;
        $fin = $request->fin;

        $requerimientos = collect([]);

        if (isset($request->empresas)) {
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
                $reqEmpresas->load("guiasDespacho", "guiasDespacho.productos");
                $requerimientos->push($reqEmpresas);
            }
            $requerimientos = $requerimientos->flatten();
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
            $requerimientos->load("guiasDespacho", "guiasDespacho.productos");
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
            $requerimientos->load("guiasDespacho", "guiasDespacho.productos");
        }

        $excelData = [
            ['Periodo Inicio', 'Periodo Fin'],
            [$inicio, $fin],
            [""],
            [
                'Fecha', 'Folio', 'Empresa', 'Centro',
                'Zona', 'Cant. Productos', 'Total'
            ],
        ];
        if ($requerimientos->count() > 0) {
            foreach ($requerimientos as $requerimiento) {
                if ($requerimiento->guiasDespacho->count() > 0) {
                    foreach ($requerimiento->guiasDespacho as $guiaDespacho) {
                        $excelData[] = [
                            date("d-m-Y", strtotime($guiaDespacho->created_at)),
                            $guiaDespacho->folio,
                            $guiaDespacho->requerimiento->centro->empresa->razon_social,
                            $guiaDespacho->requerimiento->centro->nombre,
                            $guiaDespacho->requerimiento->centro->zona,
                            $guiaDespacho->productos->count(),
                            $guiaDespacho->neto
                        ];
                    }
                }
            }
        }



        $export = new ArrayExport($excelData);
        return Excel::download($export, "reporte-pagos.xlsx");
    }

    public function enviadosView()
    {
        return view("reporte.enviado");
    }

    public function enviados(Request $request)
    {
        $inicio = $request->inicio;
        $fin = $request->fin;

        $centros = Auth::user()->userable->centros;

        $ids = $centros->map(function ($centro) {
            return $centro->id;
        })->toArray();

        $requerimientos = \App\Requerimiento::whereIn("centro_id", $ids)
            ->whereHas("guiasDespacho", function ($query) use ($inicio, $fin) {
                $query->whereDate("fecha", ">=", $inicio)
                    ->whereDate("fecha", "<=", $fin)
                    ->whereNotNull("febos_id");
            })
            ->orderBy("created_at")
            ->get();
        $requerimientos->load("guiasDespacho");

        $nroRequerimientos = $requerimientos->count();

        $totalGasto = $requerimientos->reduce(function ($carry, $requerimiento) {
            return $carry + $requerimiento->getTotal();
        });

        $excelData = [
            [
                'Periodo Inicio', 'Periodo Fin', 'Total Ventas ($)',
                'Numero de Pedidos'
            ],
            [
                $inicio, $fin, $totalGasto,
                $nroRequerimientos
            ],
            [""],
            [
                'Fecha', 'Zona', 'Destino', 'N° Guia',
                'SKU', 'Producto', 'Cantidad', 'P. Unitario',
                'Total', 'Observaciones'
            ],
        ];
        if ($requerimientos->count() > 0) {
            foreach ($requerimientos as $requerimiento) {
                if ($requerimiento->guiasDespacho->count() > 0) {
                    foreach ($requerimiento->guiasDespacho as $guiaDespacho) {
                        if ($guiaDespacho->productos->count() > 0) {
                            foreach ($guiaDespacho->productos as $producto) {
                                $excelData[] = [
                                    date("d-m-Y", strtotime($guiaDespacho->created_at)),
                                    $requerimiento->centro->zona,
                                    $requerimiento->centro->nombre,
                                    $guiaDespacho->folio,
                                    $producto->sku,
                                    $producto->detalle,
                                    $producto->pivot->real,
                                    $producto->pivot->precio,
                                    $producto->pivot->precio * $producto->pivot->real,
                                    $requerimiento->observaciones
                                ];
                            }
                        }
                    }
                }
            }
        }

        $export = new ArrayExport($excelData);
        return Excel::download($export, "$inicio-$fin.xlsx");
    }

    public function recibidosView()
    {
        return view("reporte.recibido");
    }

    public function recibidos(Request $request)
    {
        $inicio = $request->inicio;
        $fin = $request->fin;

        $empresa = Auth::user()->userable;


        $requerimientos = $empresa->requerimientos()
            ->whereHas("guiasDespacho", function ($query) use ($inicio, $fin) {
                $query->whereDate("fecha", ">=", $inicio)
                    ->whereDate("fecha", "<=", $fin)
                    ->whereNotNull("febos_id");
            })
            ->orderBy("created_at")
            ->get();
        $requerimientos->load("guiasDespacho");

        $excelData = [
            [
                "Zona", "Centro", "# Pedido", "Fecha Guia",
                "Folio Guia", "Detalle", "Precio Unt", "Cantidad",
                "Subtotal", "Motivo Rechazo", "Observacion Compass"
            ],
        ];

        if ($requerimientos->count() > 0) {
            foreach ($requerimientos as $requerimiento) {
                if ($requerimiento->guiasDespacho->count() > 0) {
                    foreach ($requerimiento->guiasDespacho as $guiaDespacho) {
                        if ($guiaDespacho->rechazos->count() > 0) {
                            foreach ($guiaDespacho->rechazos as $rechazo) {
                                $excelData[] = [
                                    $requerimiento->centro->zona,
                                    $requerimiento->centro->nombre,
                                    $requerimiento->id,
                                    $guiaDespacho->fecha,
                                    $guiaDespacho->folio,
                                    $rechazo->producto->detalle,
                                    $rechazo->producto->venta,
                                    $rechazo->productoGuia->pivot->real,
                                    $rechazo->producto->venta
                                        * $rechazo->productoGuia->pivot->real,
                                    $rechazo->motivo,
                                    $rechazo->productoGuia->observacion
                                ];
                            }
                        }
                    }
                }
            }
        }


        $export = new ArrayExport($excelData);
        return Excel::download($export, "$inicio-$fin.xlsx");
    }

    public function cierresView()
    {
        return view("reporte.cierre");
    }

    public function cierres(Request $request)
    {
        $inicio = $request->inicio;
        $fin = $request->fin;

        $empresa = Auth::user()->userable;

        $requerimientos = $empresa->requerimientos()
            ->whereHas("guiasDespacho", function ($query) use ($inicio, $fin) {
                $query->whereDate("fecha", ">=", $inicio)
                    ->whereDate("fecha", "<=", $fin)
                    ->whereNotNull("febos_id");
            })
            ->orderBy("created_at")
            ->get();
        $requerimientos->load("guiasDespacho");

        $excelData = [
            [
                "Zona", "Centro", "# Pedido", "Fecha Guia",
                "Folio Guia", "Monto Guia", "Monto Rechazado",
                "Diferencia", "Fecha Despacho", "Fecha Recepcion",
                "Observaciones"
            ],
        ];

        if ($requerimientos->count() > 0) {
            foreach ($requerimientos as $requerimiento) {
                if ($requerimiento->guiasDespacho->count() > 0) {
                    foreach ($requerimiento->guiasDespacho as $guiaDespacho) {
                        $recibido = null;
                        if (!empty($requerimiento->detalleEstado("RECIBIDO"))) {
                            $recibido = $requerimiento
                                ->detalleEstado("RECIBIDO")
                                ->created_at;
                        }
                        $observacion = null;
                        if (!empty($requerimiento->detalleEstado("RECIBIDO CON OBSERVACIONES"))) {
                            $observacion = $requerimiento
                                ->detalleEstado("RECIBIDO CON OBSERVACIONES")
                                ->created_at;
                        }
                        $fechaRecepcion = empty($recibido) ? $observacion : $recibido;
                        $fechaRecepcion = empty($fechaRecepcion) ? "SIN RECIBIR" : $fechaRecepcion;
                        $hasObservacion = ($guiaDespacho->rechazos->count() > 0) ? "SI" : "NO";

                        $excelData[] = [
                            $guiaDespacho->direccion_destino,
                            $guiaDespacho->nombre_centro,
                            $guiaDespacho->requerimiento_id,
                            $guiaDespacho->fecha,
                            $guiaDespacho->folio,
                            $guiaDespacho->neto,
                            $guiaDespacho->montoRechazos,
                            $guiaDespacho->neto - $guiaDespacho->montoRechazos,
                            $guiaDespacho->fecha,
                            $fechaRecepcion,
                            $hasObservacion
                        ];
                    }
                }
            }
        }

        $export = new ArrayExport($excelData);
        return Excel::download($export, "$inicio-$fin.xlsx");
    }

    public function notaCreditoView()
    {
        return view("reporte.nota-credito");
    }

    public function notaCredito(Request $request)
    {
        $inicio = $request->inicio;
        $fin = $request->fin;

        $empresa = Auth::user()->userable;

        $requerimientos = $empresa->requerimientos()
            ->whereHas("guiasDespacho", function ($query) use ($inicio, $fin) {
                $query->whereDate("fecha", ">=", $inicio)
                    ->whereDate("fecha", "<=", $fin)
                    ->whereNotNull("febos_id");
            })
            ->orderBy("created_at")
            ->get();
        $requerimientos->load("productosRechazados");

        $rechazos = $requerimientos
            ->pluck("productosRechazados")
            ->flatten()
            ->filter(function ($rechazo) {
                return $rechazo->estado_pago && $rechazo->cierre;
            });

        $excelData = [
            [
                "Zona", "Centro", "# Pedido", "Fecha Guia",
                "Folio Guia", "Detalle", "Precio Unt", "Cantidad",
                "Subtotal", "Motivo Rechazo", "Observacion Compass"
            ]
        ];

        if ($rechazos->count() > 0) {
            foreach ($rechazos as $rechazo) {
                $excelData[] = [
                    $rechazo->guiaDespacho->direccion_destino,
                    $rechazo->guiaDespacho->nombre_centro,
                    $rechazo->guiaDespacho->requerimiento->id,
                    $rechazo->guiaDespacho->fecha,
                    $rechazo->guiaDespacho->folio,
                    $rechazo->producto->detalle,
                    $rechazo->producto->venta,
                    $rechazo->productoGuia->pivot->real,
                    $rechazo->producto->venta *
                        $rechazo->productoGuia->pivot->real,
                    $rechazo->motivo,
                    $rechazo->productoGuia->pivot->observacion
                ];
                f;
            }
        }

        $export = new ArrayExport($excelData);
        return Excel::download($export, "$inicio-$fin.xlsx");
    }

    public function cargaEmpresaView()
    {
        return view("reporte.carga-empresa");
    }

    public function cargaEmpresa(Request $request)
    {
        $inicio = $request->inicio;
        $fin = $request->fin;

        $empresa = Auth::user()->userable;

        $requerimientos = $empresa->requerimientos()
            ->whereHas("guiasDespacho", function ($query) use ($inicio, $fin) {
                $query->whereDate("fecha", ">=", $inicio)
                    ->whereDate("fecha", "<=", $fin);
                //->whereNotNull("febos_id");
            })
            ->orderBy("created_at")
            ->get();
        $requerimientos->load("productosRechazados");

        $rechazos = $requerimientos
            ->pluck("productosRechazados")
            ->flatten()
            ->filter(function ($rechazo) {
                return !$rechazo->estado_pago && $rechazo->cierre;
            });

        $excelData = [
            [
                "Zona", "Centro", "# Pedido", "Fecha Guia",
                "Folio Guia", "Detalle", "Precio Unt", "Cantidad",
                "Subtotal", "Motivo Rechazo", "Observacion Compass"
            ]
        ];

        if ($rechazos->count() > 0) {
            foreach ($rechazos as $rechazo) {
                $excelData[] = [
                    $rechazo->guiaDespacho->direccion_destino,
                    $rechazo->guiaDespacho->nombre_centro,
                    $rechazo->guiaDespacho->requerimiento->id,
                    $rechazo->guiaDespacho->fecha,
                    $rechazo->guiaDespacho->folio,
                    $rechazo->producto->detalle,
                    $rechazo->producto->venta,
                    $rechazo->productoGuia->pivot->real,
                    $rechazo->producto->venta *
                        $rechazo->productoGuia->pivot->real,
                    $rechazo->motivo,
                    $rechazo->productoGuia->pivot->observacion
                ];
            }
        }

        $export = new ArrayExport($excelData);
        return Excel::download($export, "$inicio-$fin.xlsx");
    }
}
