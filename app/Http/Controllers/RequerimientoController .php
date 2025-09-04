<?php

namespace App\Http\Controllers;

use App\Notifications\EstadoUpdated;
use App\Requerimiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Imports\RequerimientosImport;
use App\Producto;
use App\TipoObservacion;
use Maatwebsite\Excel\Facades\Excel;

class RequerimientoController extends Controller
{

    /**
     * Muestra las ordenes de pedidos para un Centro
     *
     * @param  \App\centro  $centro
     * @param Int Estado
     * @return \Illuminate\Http\Response
     */
    public function showCentro($centroId, $estadoId = null)
    {
        $centro = \App\Centro::findOrFail($centroId);
        $requerimientos = $centro->getRequerimientosByEstado($estadoId);


        return view('requerimiento.index.show')->with(compact('centro', 'requerimientos'));
    }

    /**
     * Muestra el control para las ordenes de pedido para un Centro
     *
     * @param \App\Centro $centroId
     * @param Int year
     * @return \Illuminate\Http\Response
     */
    public function centroIndex($centroId, $year = null)
    {
        $centro = \App\Centro::findOrFail($centroId);
        $requerimientos = $centro->requerimientos();
        if (!is_null($year)) {
            $requerimientos = $requerimientos
                ->whereYear('created_at', $year)
                ->get();

            $estados = $requerimientos
                ->groupBy([function ($query) {
                    return \Carbon\Carbon::parse($query->created_at)->format('m');
                }, 'estado']);

            $requerimientos = $requerimientos
                ->groupBy(function ($query) {
                    return \Carbon\Carbon::parse($query->created_at)->format('m');
                });
        } else {
            $requerimientos = $requerimientos
                ->whereYear('created_at', date("Y"))
                ->get();

            $estados = $requerimientos
                ->groupBy([function ($query) {
                    return \Carbon\Carbon::parse($query->created_at)->format('m');
                }, 'estado']);

            $requerimientos = $requerimientos
                ->groupBy(function ($query) {
                    return \Carbon\Carbon::parse($query->created_at)->format('m');
                });
        }

        $counts = collect([]);
        $products = collect([]);
        $totals = collect([]);
        for ($i = 1; $i < 13; $i++) {
            $key = str_pad($i, 2, "0", STR_PAD_LEFT);
            if ($requerimientos->has($key)) {
                $counts->push(count($requerimientos[$key]));
                $products->push($requerimientos[$key]->reduce(function ($carry, $requerimiento) {
                    return $carry + $requerimiento->productos->count();
                }));
                $totals->push($requerimientos[$key]->reduce(function ($carry, $requerimiento) {
                    return $carry + $requerimiento->getTotal();
                }));
            } else {
                $counts->push(0);
                $products->push(0);
                $totals->push(0);
            }
        }

        $counts->push($counts->reduce(function ($carry, $item) {
            return $carry + $item;
        }));
        $products->push($counts->reduce(function ($carry, $item) {
            return $carry + $item;
        }));
        $totals->push($totals->reduce(function ($carry, $item) {
            return $carry + $item;
        }));

        return view('requerimiento.index.index_mes')->with(compact('centro', 'counts', 'products', 'totals', 'estados'));
    }


    /**
     * Muestra las ordenes de pedidos pendiente y permite validarlas
     *
     * @return void
     */
    public function validarPedidos()
    {
        $empresa = Auth::user()->userable;
        $centros = $empresa->centros()->whereHas('requerimientos', function ($query) {
            $query->where('estado', 'ESPERANDO VALIDACION');
        })->get();

        return view('requerimiento.validar_pedidos')->with(compact('centros'));
    }

    /**
     * Cambia el estado del requerimiento a "VALIDADO".
     *
     * @return void
     */
    public function aceptar(Request $request)
    {
        $requerimientoId = $request->input('requerimiento');

        $requerimiento = Requerimiento::find($requerimientoId);
        $requerimiento->estado = "VALIDADO";
        $requerimiento->save();

        $users = $requerimiento->getUserByRequerimiento();

        foreach ($users as $user) {
            $user->notify((new EstadoUpdated($requerimiento))->delay(\Carbon\Carbon::now()->addSeconds(60)));
        }

        return response()->json(['title' => '¡Orden aceptada exitosamente!', 'msg' => 'La Orden de Pedido fue aceptada']);
    }


    /**
     * Cambia el estado del requerimiento a "RECHAZADO"
     *
     * @return void
     */
    public function rechazar(Request $request)
    {
        $requerimientoId = $request->input("requerimiento");
        if ($request->has('observaciones')) {
            $observaciones = $request->input('observaciones');
        } else {
            $observaciones = null;
        }

        $requerimiento = Requerimiento::find($requerimientoId);
        $requerimiento->estado = "RECHAZADO";
        $requerimiento->observaciones = $observaciones;
        $requerimiento->save();

        return response()->json(['title' => '¡Orden rechazada exitosamente!', 'msg' => 'La Orden de Pedido fue rechazada']);;
    }

    /**
     * Cambia el estado de todos los requerimientos a "VALIDADO".
     *
     * @return void
     */
    public function aceptarTodos(Request $request)
    {
        $empresa = Auth::user()->userable;
        $centros = $empresa->centros()->whereHas('requerimientos', function ($query) {
            $query->where('estado', 'ESPERANDO VALIDACION');
        })->get();

        foreach ($centros as $centro) {
            $requerimientos = $centro->requerimientos()->where('estado', 'ESPERANDO VALIDACION')->get();
            foreach ($requerimientos as $requerimiento) {
                $requerimiento->estado = 'VALIDADO';
                $requerimiento->save();

                $users = $requerimiento->getUserByRequerimiento();

                foreach ($users as $user) {
                    $user->notify((new EstadoUpdated($requerimiento))->delay(\Carbon\Carbon::now()->addSeconds(60)));
                }
            }
        }

        $msg = ['title' => '¡Ordenes aceptadas exitosamente!', 'text' => 'Todas las Ordenes fueron aceptadas'];
        return response()->json($msg);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $centro = Auth::user()->userable;
        $empresa = $centro->empresa;

        $productos = $empresa->productosVigentes->values();
        $monto = $centro->getTotalPresupuestoByDate(date("m"), date("Y"));
        $presupuesto = (isset($monto->monto) ? $monto->monto : 0);

        $ultimoRequerimiento = Requerimiento::latest()->first();
        $numeroRequerimiento = is_null($ultimoRequerimiento) ? 0 : $ultimoRequerimiento->id;
        $nombre = date("Y-m-d") . ' '
            . $empresa->razon_social . ': '
            . $centro->nombre . ':  '
            . $numeroRequerimiento;

        return view('requerimiento.create')
            ->with(compact(
                'empresa',
                'presupuesto',
                'centro',
                'productos',
                'nombre',
                'numeroRequerimiento'
            ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $productos = collect($request->productos);
        $centro = Auth::user()->userable;
        $empresa = $centro->empresa;
        $ultimoRequerimiento = Requerimiento::latest()->first();
        $numeroRequerimiento = is_null($ultimoRequerimiento) ? 0 : $ultimoRequerimiento->id;
        $numeroRequerimiento = $numeroRequerimiento + 1;
        $nombre = date("Y-m-d") . ' '
            . $empresa->razon_social . ': '
            . $centro->nombre . ':  '
            . $numeroRequerimiento;

        $productosVigentes = $empresa->productosVigentes->values();
        $skus = $productos->pluck("sku");

        $lista = $productosVigentes->filter(function ($producto) use ($skus) {
            return $skus->contains($producto->sku);
        });


        if ($lista->count() > 0) {

            $requerimiento = $centro->requerimientos()->create([
                "nombre" => $nombre,
                "dotacion" => $centro->dotacion ?? 0
            ]);

            $requerimiento->cambiarEstado("ESPERANDO VALIDACION", Auth::id());

            foreach ($lista as $producto) {
                $cantidad = $productos
                    ->firstWhere("sku", $producto->sku)["cantidad"];

                $requerimiento->productos()->attach($producto, [
                    "cantidad" => $cantidad,
                    "precio" => $producto->venta,
                ]);
            }


            return response(route("cliente.home"), 201);
        } else {
            return response(route("cliente.home"), 500);
        }
    }

    /**
     * Lista de Requerimientos segun su estado y productos
     *
     * @return \Illuminate\Http\Response
     */
    public function verificar()
    {
        $requerimientos = Requerimiento::where('estado', 'VALIDADO')->get();

        if ($requerimientos->count() > 0) {
            $requerimientoId = $requerimientos->map(function ($requerimiento) {
                return $requerimiento->id;
            });
            $productos = DB::table('producto_requerimiento')
                ->join('productos', 'producto_requerimiento.producto_id', '=', 'productos.id')
                ->select(DB::raw('productos.sku, productos.detalle, SUM(producto_requerimiento.cantidad) as cantidad'))
                ->whereIn('producto_requerimiento.requerimiento_id', $requerimientoId)
                ->groupBy('productos.sku', 'productos.detalle')
                ->get();
        } else {
            $productos = collect([]);
        }


        return view('compass.verificar_index')->with(compact('productos', 'requerimientos'));
    }

    /**
     * Cambia los Estado del Requerimiento a En Bodega
     *
     * @return \Illuminate\Http\Response
     */
    public function doVerificar()
    {
        $requerimientos = Requerimiento::where('estado', 'VALIDADO')->get();
        $requerimientos->map(function ($requerimiento) {
            $requerimiento->estado = "EN BODEGA";
            $requerimiento->save();
        });
        $msg = [
            'meta' => [
                'title' => '¡Ordenes de Pedido enviados a Bodega!',
                'msg' => 'Las Ordenes de pedido fueron enviadas a los Usuarios de Bodega'
            ]
        ];

        return redirect()->route('pedidos.indexEmpresa')->with(compact('msg'));
    }


    /**
     * Lista de Ordenes de Pedidos verificadas por Centros
     *
     * @return \Illuminate\Http\Response
     */
    public function indexCajas()
    {
        $centros = \App\Centro::whereHas('requerimientos', function ($query) {
            $query->where('estado', 'EN BODEGA')
                ->where('folio', null)
                ->where('transporte_id', null);
        })->get();

        return view('compass.cajas_index')->with(compact('centros'));
    }

    /**
     * Muesta informacion para un requerimiento por su Id
     *
     * @return \Illuminate\Http\Response
     */
    public function showCaja($requerimientoId)
    {
        $requerimiento = Requerimiento::findOrFail($requerimientoId);
        $productos = $requerimiento
            ->centro
            ->empresa
            ->productosVigentes
            ->values();
        $bodegueros = \App\Bodeguero::all();

        return view('compass.cajas_show')
            ->with(compact('requerimiento', 'bodegueros', 'productos'));
    }

    /**
     * Muestra el formulario para reutilizar una orden de pedido
     *
     * @param Int $requerimientoId
     * @return \Illuminate\Http\Response
     */
    public function edit(Requerimiento $requerimiento)
    {
        $empresa = $requerimiento->centro->empresa;
        $centro = $requerimiento->centro;
        $presupuesto = $requerimiento->centro->getTotalPresupuestoByDate();
        $productos = $empresa->productosVigentes->values();
        $productosLibreria = collect([]);
        foreach ($requerimiento->productos as $producto) {
            $item = $productos->where("sku", $producto["sku"])->first()->toArray();
            $item["cantidad"] = floatval($producto->pivot->cantidad);
            $item["precio"] = $item["venta"];
            $item["subtotal"] = $item["cantidad"] * $item["precio"];
            $productosLibreria->push($item);
        }
        $nombre = $requerimiento->nombre;

        return view('requerimiento.edit')->with(compact('empresa', 'presupuesto', 'centro', 'productos', 'productosLibreria', 'requerimiento'));
    }

    public function update(Requerimiento $requerimiento, Request $request)
    {
        $productos = $request->pedido;
        $vigentes = $requerimiento->centro->empresa->productosVigentes;
        $requerimiento->productos()->sync([]);
        foreach ($productos as $producto) {
            $item = $vigentes->where("sku", $producto["sku"])->first()->toArray();
            $item["cantidad"] = floatval($producto["cantidad"]);
            $requerimiento->productos()->attach($item["id"], [
                "cantidad" => floatval($producto["cantidad"]),
                "precio" => $item["venta"]
            ]);
        }

        $msg = [
            'meta' => [
                'title' => 'Requerimiento Actualizado',
                'msg' => ''
            ]
        ];

        return response()->json($msg);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Requerimiento $requerimiento
     * @return \Illuminate\Http\Response
     */
    public function armarCaja(Request $request, \App\Requerimiento $requerimiento)
    {
        if ($request->has("delete") && $request->input("remove")) {
            $requerimiento->productos()->detach($request->input("remove"));

            $msg = [
                'meta' => [
                    'title' => 'Eliminados',
                ]
            ];

            return redirect()
                ->back()
                ->with(compact('msg'));
        }

        $requerimiento = \App\Requerimiento::find($requerimiento->id);
        $productos = collect($request->input('productos'));
        $reales = collect($request->input('real'));
        $vencimientos = collect($request->input('vencimiento'));
        $observaciones = collect($request->input('observaciones'));

        $saving = $request->input("save");

        $productos
            ->map(
                function ($item, $index)
                use ($requerimiento, $reales, $observaciones, $vencimientos) {
                    $producto = json_decode($item, true);
                    $requerimiento->productos()->updateExistingPivot(
                        $producto['id'],
                        [
                            'real' => $reales[$index],
                            'fecha_vencimiento' => $vencimientos[$index],
                            'observacion' => $observaciones[$index]
                        ]
                    );
                }
            );

        if (!$saving) {

            $folios = \App\Folio::getLastFolio(ceil($productos->count() / 29));

            if (isset($folios["meta"])) {
                $msg = $folios;
                return redirect()->route("cargar-folios")->with(compact($msg));
            }

            $requerimiento->folio = $folios->toJson();

            if ($requerimiento->save()) {

                $msg = [
                    'meta' => [
                        'title' => '¡Orden de Pedido Armada!',
                        'msg' => 'La Orden de Pedido fue armada sin problemas'
                    ]
                ];

                return redirect()
                    ->route('compass.pedidos.cajasIndex')
                    ->with(compact('msg'));
            } else {

                $msg = [
                    'meta' => [
                        'title' => 'Ocurrio un problema',
                        'msg' => 'La orden de pedido no pudo ser guardar,
                                    intente nuevamente mas tarde'
                    ]
                ];

                return redirect()
                    ->route('compass.pedidos.cajasIndex')
                    ->with(compact('msg'));
            }
        }

        $msg = [
            'meta' => [
                'title' => 'Guardado',
            ]
        ];

        return redirect()
            ->back()
            ->with(compact('msg'));
    }

    /**
     * Detalles de un Requerimiento en especifico
     *
     * @param \App\Requerimiento $requerimiento
     * @return \Illuminate\Http\Response
     */
    public function show(\App\Requerimiento $requerimiento)
    {
        $centro = $requerimiento->centro;
        $empresa = $centro->empresa;
        $productos = $requerimiento->productos;
        $guias = $requerimiento->guiasDespacho;
        $guias->load('productos');

        return view('requerimiento.show')->with(compact('requerimiento', 'centro', 'empresa', 'productos', 'guias'));
    }

    /**
     * Muestra el formulario para reemplzar un producto por otro
     *
     * @return Illuminate\Http\Response
     */
    public function cambiarProducto(\App\Requerimiento $requerimiento, \App\Producto $producto)
    {
        $empresa = \App\Empresa::findOrFail($requerimiento->centro->empresa->id);
        $productos = $empresa->productosVigentes->values();

        return view('compass.cajas_reemplazar')->with(compact('productos', 'requerimiento', 'producto'));
    }

    /**
     * Reemplazar un productos por otro en el Requerimiento
     *
     * @return \Illuminate\Http\Response
     */
    public function reemplazar(\App\Requerimiento $requerimiento, Request $request)
    {

        $productoReemplazado = $requerimiento->productos->where('id', $request->input('productoReemplazadoId'))->first();
        $nuevoProducto = $requerimiento->centro->empresa->productos->where('id', $request->input('nuevoProducto'))->first();

        if (!is_null($productoReemplazado) && !is_null($nuevoProducto)) {
            $requerimiento->productos()->detach($productoReemplazado);
            $requerimiento->productos()->attach($nuevoProducto, [
                'cantidad' => $productoReemplazado->pivot->cantidad,
                'precio' => $nuevoProducto->venta,
                'observacion' => 'En reemplazo de ' . $productoReemplazado->detalle
            ]);

            $requerimiento->save();
            return redirect()->route('compass.pedidos.show', $requerimiento);
        } else {
            return redirect()->route('compass.pedidos.show', $requerimiento);
        }
    }


    /**
     * Muestra la pantalla para Despachar
     *
     * @return \Illuminate\Http\Response
     */
    public function despacharView()
    {
        $despachos = \App\Transporte::where('despachado', false)->get();

        return view('compass.despachar')->with(compact('despachos'));
    }

    /**
     * Elimina un despacho programado
     *
     * @return \Illuminate\Http\Response
     */
    public function eliminarDespacho($id)
    {
        $despacho = \App\Transporte::find($id);
        $despacho->requerimientos()->get()->map(function ($requerimiento) {
            $requerimiento->transporte()->dissociate();
            $requerimiento->save();
        });

        $despacho->delete();

        $msg = [
            "meta" => [
                "title" => "Despacho programado eliminado",
                "msg" => "El despacho fue eliminado exitosamente"
            ]
        ];

        return response()->json($msg);
    }


    /**
     * Realiza un despacho
     *
     * @return \Illuminate\Http\Response
     */
    public function despachar($id)
    {
        $despacho = \App\Transporte::find($id);

        $despacho->requerimientos->map(function ($requerimiento) {
            $requerimiento->cambiarEstado("DESPACHADO", \Auth::id());
        });

        $despacho->despachado = true;
        $despacho->save();

        $msg = [
            "meta" => [
                "title" => "Requerimientos despachados",
                "msg" => "Los Requerimientos fueron despachados exitosamente"
            ]
        ];

        return response()->json($msg);
    }


    public function cargaMasiva()
    {
        $centros = \App\Centro::all();

        return view('requerimiento.carga_masiva')->with(compact('centros'));
    }

    public function importar(Request $request)
    {
        $centro = \App\Centro::find($request->input('centro'));

        Excel::import(new RequerimientosImport($centro), $request->file('archivo'));

        $msg = [
            "meta" => [
                "title" => "Carga Masiva Exitosa",
                "msg" => "La carga masiva se realizo correctamente"
            ]
        ];

        return back()->with(compact('msg'));
    }

    public function descargaFormato()
    {
        $empresa = Auth::user()->userable->empresa;

        $productos = $empresa->productosVigentes;

        $csv = fopen(storage_path("formato.csv"), "w");
        $count = $productos->count();
        fputcsv($csv, [
            'SKU', 'DETALLE', 'P. UNIT', 'CANTIDAD', 'SUBTOTAL',
            'TOTAL=', "=SUM(E1:E$count)"
        ]);

        foreach ($productos as $index => $producto) {
            $row = ($index == 0) ? ($index + 2) : $index + 1;
            fputcsv($csv, [
                $producto->sku, $producto->detalle,
                $producto->venta, "", "=C{$row}*D{$row}"
            ]);
        }

        return response()->download(storage_path("formato.csv"))->deleteFileAfterSend();
    }



    public function agregarProductos(Requerimiento $requerimiento, Request $request)
    {
        foreach ($request->productos as $producto) {
            $item = Producto::find($producto["producto_id"]);
            if ($item != null) {
                $requerimiento->productos()->attach($item->id, [
                    "cantidad" => $producto["cantidad"],
                    "precio" => $item->venta,
                ]);
            }
        }

        $msg = [
            "meta" => [
                "title" => "Carga Masiva Exitosa",
                "msg" => "La carga masiva se realizo correctamente"
            ]
        ];

        return response()->json([
            "status" => "OK"
        ]);
    }

    public function recepcionView(Requerimiento $requerimiento)
    {
        $guiasDespacho = $requerimiento->guiasDespacho;
        $guiasDespacho->load("productos");

        $observaciones = TipoObservacion::all();

        return view("requerimiento.recepcion")
            ->with(compact('guiasDespacho', 'requerimiento', "observaciones"));
    }

    public function recepcion(Requerimiento $requerimiento, Request $request)
    {
        foreach ($request->rechazados as $rechazado) {
            $guiaDespacho = null;

            if (isset($rechazado["guia"])) {
                $guiaDespacho = \App\GuiaDespacho::find($rechazado["guia"]["id"]);
            }

            if (isset($rechazado["product"])) {
                $guiaDespacho->productos()->updateExistingPivot($rechazado["product"]["id"], $rechazado["product"]["pivot"]);
            }
        }

        if (count($request->rechazados) > 0) {
            $requerimiento
                ->cambiarEstado("RECIBIDO CON OBSERVACIONES", Auth::id());
        } else {
            $requerimiento
                ->cambiarEstado("RECIBIDO", Auth::id());
        }


        return response(route("cliente.home"), 201);
    }

    public function listaLogistica()
    {
        $requerimientos = \Auth::user()->userable->requerimientos->sortBy(function ($requerimiento) {
            return $requerimiento->estado == "EN BODEGA" ? 0 : 1;
        });
        return view('requerimiento.index.logistica')->with(compact('requerimientos'));
    }

    public function borrarProducto(\App\Requerimiento $requerimiento, \App\Producto $producto)
    {
        $requerimiento->productos()->detach($producto);

        return back();
    }

    public function eliminar(Requerimiento $requerimiento)
    {
        $requerimiento->estado = "ELIMINADO";
        $requerimiento->save();

        return back();
    }
}
