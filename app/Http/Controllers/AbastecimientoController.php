<?php

namespace App\Http\Controllers;

use App\Abastecimiento;
use Illuminate\Http\Request;

class AbastecimientoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $abastecimientos = \App\Abastecimiento::all();

        return view('abastecimiento.index')->with(compact('abastecimientos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('abastecimiento.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $abastecimiento = new Abastecimiento;
        $abastecimiento->nombre = $request->input('nombre');
        $abastecimiento->comuna = $request->input('comuna');
        $abastecimiento->ciudad = $request->input('ciudad');

        if ($abastecimiento->saveOrFail()) {
            $msg = [
                "meta" => [
                    "title" => '¡Punto de Abastecimiento creado exitosamente!',
                    "msg" => 'Un nuevo punto de abastecimiento ha sido creado exitosamente'
                ]
            ];

            return redirect()->route('abastecimientos.index')->with(compact('msg'));
        } else {
            $msg = [
                "meta" => [
                    "title" => '¡Error creando Punto de Abastecimiento!',
                    "msg" => 'Ocurrio un error creando el punto de abastecimiento'
                ]
            ];

            return redirect()->route('abastecimientos.index')->with(compact('msg'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Abastecimiento  $abastecimiento
     * @return \Illuminate\Http\Response
     */
    public function edit(Abastecimiento $abastecimiento)
    {
        return view('abastecimiento.edit')->with(compact('abastecimiento'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Abastecimiento  $abastecimiento
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Abastecimiento $abastecimiento)
    {
        $abastecimiento->nombre = $request->input('nombre');
        $abastecimiento->comuna = $request->input('comuna');
        $abastecimiento->ciudad = $request->input('ciudad');

        if ($abastecimiento->saveOrFail()) {
            $msg = [
                "meta" => [
                    "title" => '¡Punto de Abastecimiento creado exitosamente!',
                    "msg" => 'Un nuevo punto de abastecimiento ha sido creado exitosamente'
                ]
            ];

            return redirect()->route('abastecimientos.index')->with(compact('msg'));
        } else {
            $msg = [
                "meta" => [
                    "title" => '¡Error creando Punto de Abastecimiento!',
                    "msg" => 'Ocurrio un error creando el punto de abastecimiento'
                ]
            ];

            return redirect()->route('abastecimientos.index')->with(compact('msg'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Abastecimiento  $abastecimiento
     * @return \Illuminate\Http\Response
     */
    public function destroy(Abastecimiento $abastecimiento)
    {
        try {
            $abastecimiento->delete();

            return response()->json([
                'data' => [
                    'abastecimiento' => [
                        'type' => 'Abastecimiento',
                        'id' => $abastecimiento->id,
                        'attributes' => $abastecimiento,
                    ],
                ],
                'meta' => [
                    'title' => '¡Punto de Abastecimiento eliminado exitosamente!',
                    'msg' => 'El punto de abastecimiento <b>'.$abastecimiento->nombre.'</b> ha sido eliminado<br />La pagina se recargara'
                ]
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'errors' => [
                    'status' => '500',
                    'title' => 'Error eliminando punto de abastecimiento',
                    'detail' => 'Ocurrio un error eliminando el punto de abastecimiento',
                    'source' => $e
                ]
            ], 500);
        }
    }
}
