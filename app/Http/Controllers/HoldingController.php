<?php

namespace App\Http\Controllers;

use App\Holding;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HoldingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $holdings = Holding::all();

        return view('holding.index')->with(compact('holdings'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('holding.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $holding = new Holding;
        $holding->nombre = $request->input('nombre');
        if ($holding->saveOrFail()) {
            return response()->json([
                'data' => [
                    'holding' => [
                        'type' => 'Holding',
                        'id' => $holding->id,
                        'attributes' => $holding,
                    ],
                    'usuario' => [
                        'type' => 'Usuario',
                        'id' => Auth::id(),
                        'attributes' => Auth::user()
                    ]
                ],
                'meta' => [
                    'title' => '¡Holding guardado exitosamente!',
                    'msg' => 'Un nuevo Holding fue creado con los siguientes datos:<br /> <b>Nombre:</b>'.$holding->nombre
                ]
            ], 201);
        } else {
            return response()->json([
                'errors' => [
                    'status' => '500',
                    'title' => 'Error guardando Holding',
                    'detail' => 'Ocurrio un error guardando el Holding:'.$e,
                    'source' => $e
                ]
            ], 500);
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Holding  $holding
     * @return \Illuminate\Http\Response
     */
    public function edit(Holding $holding)
    {
        return view('holding.edit')->with(compact('holding'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Holding  $holding
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Holding $holding)
    {
        $holding->nombre = $request->input('nombre');
        if ($holding->saveOrFail()) {
            $msg = [
                'data' => [
                    'holding' => [
                        'type' => 'Holding',
                        'id' => $holding->id,
                        'attributes' => $holding,
                    ],
                    'usuario' => [
                        'type' => 'Usuario',
                        'id' => Auth::id(),
                        'attributes' => Auth::user()
                    ]
                ],
                'meta' => [
                    'title' => '¡Holding guardado exitosamente!',
                    'msg' => 'El Holding fue actualizado con los siguientes datos:<br /> <b>Nombre:</b>'.$holding->nombre
                ]
            ];
            return redirect()->route('holdings.index')->with(compact('msg'));
        } else {
            $msg = [
                'errors' => [
                    'status' => '500',
                    'title' => 'Error guardando Holding',
                    'detail' => 'Ocurrio un error guardando el Holding:'.$e,
                    'source' => $e
                ]
            ];

            return redirect()->route('holdings.index')->with(compact('msg'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Holding  $holding
     * @return \Illuminate\Http\Response
     */
    public function destroy(Holding $holding)
    {
        try {
            $holding->delete();

            return response()->json([
                'data' => [
                    'holding' => [
                        'type' => 'holding',
                        'id' => $holding->id,
                        'attributes' => $holding,
                    ],
                ],
                'meta' => [
                    'title' => '¡Holding eliminado exitosamente!',
                    'msg' => 'El Holding <b>'.$holding->nombre.'</b> ha sido borrado.<br />La pagina se recargara'
                ]
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'errors' => [
                    'status' => '500',
                    'title' => 'Error eliminando Holding',
                    'detail' => 'Ocurrio un error eliminando el Holding',
                    'source' => $e
                ]
            ], 500);
        }
    }
}
