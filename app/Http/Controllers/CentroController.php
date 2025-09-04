<?php

namespace App\Http\Controllers;

use App\Centro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CentroController extends Controller
{
    /**
     * Muestra el listado de Centros con los Requerimientos
     *
     * @return \Illuminate\Http\Response
     */
    public function indexRequerimientos($empresaId = null, $estadoId = null)
    {
        $centros = collect([]);
        if (Auth::user()->userable instanceof \App\Empresa) {
            $centrosUser = Auth::user()->userable->centros()->get();
            foreach ($centrosUser as $centro) {
                $centros->push($centro);
            }
        } elseif (Auth::user()->userable instanceof \App\CompassRole) {
            if ($empresaId !== null) {
                $centros = \App\Empresa::find($empresaId)->centros()->get();
            } else {
                $centros = \App\Centro::all();
            }
        }

        return view('requerimiento.index.centro')->with(compact('centros'));
    }
    
        
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->userable instanceof \App\Empresa) {
            $centros = Auth::user()->userable->centros()->get();
        } elseif (Auth::user()->userable instanceof \App\CompassRole) {
            $centros = Centro::all();
        } else {
            $centros = [];
        }

        return view('centro.index')->with(compact('centros'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $empresas = collect([]);
        if (Auth::user()->userable instanceof \App\Empresa) {
            $empresas->push(Auth::user()->userable);
        } elseif (Auth::user()->userable instanceof \App\CompassRole) {
            $empresas = \App\Empresa::all();
        }

        return view('centro.create')->with(compact('empresas'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $centro = new Centro;
        $centro->nombre = $request->input('nombre');
        $centro->direccion = $request->input('direccion');
        $centro->comuna = $request->input('comuna');
        $centro->ciudad = $request->input('ciudad');
        $centro->zona = $request->input('zona');
        $centro->dotacion = $request->dotacion;

        $centro->empresa()->associate(\App\Empresa::find($request->input('empresa')));

        if ($centro->saveOrFail()) {
            $msg = [
                'data' => [
                    'centro' => [
                        'type' => 'centro',
                        'id' => $centro->id,
                        'attributes' => $centro,
                    ],
                    'usuario' => [
                        'type' => 'Usuario',
                        'id' => Auth::id(),
                        'attributes' => Auth::user()
                    ]
                ],
                'meta' => [
                    'title' => '¡Centro guardado exitosamente!',
                    'msg' => 'Un nuevo Centro fue creado con los siguientes datos:<br /><b>Nombre:</b>'.$centro->nombre.'<br/><b>Empresa:</b>'.$centro->empresa()->get('razon_social')->first()->razon_social
                ]
            ];

            return redirect()->route('centros.index')->with(compact('msg'));
        } else {
            $msg = [
                'errors' => [
                    'status' => '500',
                    'title' => 'Error guardando Centro',
                    'detail' => 'Ocurrio un error guardando el centro:'.$e,
                    'source' => $e
                ]
            ];

            return redirect()->route('centros.index')->with(compact('msg'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Centro  $centro
     * @return \Illuminate\Http\Response
     */
    public function edit(Centro $centro)
    {
        $empresas = collect([]);
        if (Auth::user()->userable instanceof \App\Empresa) {
            $empresas->push(Auth::user()->userable);
        } elseif (Auth::user()->userable instanceof \App\CompassRole) {
            $empresas = \App\Empresa::all();
        }

        return view('centro.edit')->with(compact('centro', 'empresas'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Centro  $centro
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Centro $centro)
    {
        $centro->nombre = $request->input('nombre');
        $centro->direccion = $request->input('direccion');
        $centro->comuna = $request->input('comuna');
        $centro->ciudad = $request->input('ciudad');
        $centro->zona = $request->input('zona');
        $centro->dotacion = $request->dotacion;

        if ($request->has('empresa')) {
            $centro->empresa()->dissociate();
            $centro->empresa()->associate(\App\Empresa::find($request->input('empresa')));
        }

        if ($centro->saveOrFail()) {
            $msg = [
                'data' => [
                    'centro' => [
                        'type' => 'Centro',
                        'id' => $centro->id,
                        'attributes' => $centro,
                    ],
                    'usuario' => [
                        'type' => 'Usuario',
                        'id' => Auth::id(),
                        'attributes' => Auth::user()
                    ]
                ],
                'meta' => [
                    'title' => '¡Centro guardado exitosamente!',
                    'msg' => 'El Centro fue actualizado con los siguientes datos:<br /><b>Nombre:</b>'.$centro->nombre.'<br/><b>Empresa:</b>'.$centro->empresa()->get('razon_social')->first()->razon_social
                ]
            ];
            return redirect()->route('centros.index')->with(compact('msg'));
        } else {
            $msg = [
                'errors' => [
                    'status' => '500',
                    'title' => 'Error guardando Centro',
                    'detail' => 'Ocurrio un error guardando el Centro:'.$e,
                    'source' => $e
                ]
            ];

            return redirect()->route('centros.index')->with(compact('msg'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Centro  $centro
     * @return \Illuminate\Http\Response
     */
    public function destroy(Centro $centro)
    {
        try {
            $centro->delete();

            return response()->json([
                'data' => [
                    'centro' => [
                        'type' => 'centro',
                        'id' => $centro->id,
                        'attributes' => $centro,
                    ],
                ],
                'meta' => [
                    'title' => '¡Centro eliminado exitosamente!',
                    'msg' => 'El Centro <b>'.$centro->nombre.'</b> ha sido borrado.<br />La pagina se recargara'
                ]
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'errors' => [
                    'status' => '500',
                    'title' => 'Error eliminando Centro',
                    'msg' => 'Ocurrio un error eliminando el Centro',
                    'source' => $e
                ]
            ], 500);
        }
    }

    public function habilitarForm(\App\Centro $centro)
    {
        return view('centro.estado')->with(compact('centro'));
    }

    public function habilitar(\App\Centro $centro, Request $request)
    {
        if ("null" !== $request->input('estado')) {
            $estado = ($request->input('estado') == 'true') ? true : false;
        } else {
            $estado = null;
        }
        $centro->habilitado = $estado;
        if ($centro->saveOrFail()) {
            $msg = [
                'meta' => [
                    'title' => 'Accion realizada exitosamente',
                    'msg' => 'El estado del la centro fue actualizado exitosamente'
                ]
            ];
            return redirect()->route('centros.index')->with(compact('msg'));
        } else {
            $msg = [
                'meta' => [
                    'title' => 'Error actualizando centro',
                    'msg' => 'No se pudo modificar el estado del centro, intente nuevamente mas tarde'
                ]
            ];
            return redirect()->route('centros.index')->with(compact('msg'));
        }
    }
}
