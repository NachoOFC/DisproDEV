<?php

namespace App\Http\Controllers;

use App\Bodeguero;
use Illuminate\Http\Request;

class BodegueroController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bodegueros = Bodeguero::all();

        return view('bodeguero.index')->with(compact('bodegueros'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('bodeguero.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $bodeguero = new Bodeguero;
        $bodeguero->nombre = $request->input('nombre');
        $bodeguero->rut = $request->input('rut');
        if ($bodeguero->saveOrFail()) {
            $msg = [
                "meta" => [
                    "title" => "Nuevo Bodeguero creado exitosamente",
                    "msg" => "Un nuevo Bodeguero ". $bodeguero->nombre . " fue creado"
                ]
            ];

            return redirect()->route('bodegueros.index')->with(compact('msg'));
        } else {
            $msg = [
                "meta" => [
                    "title" => "Nuevo Bodeguero no pudo ser creado",
                    "msg" => "Ocurrio un error guardando el bodeguero"
                ]
            ];

            return redirect()->route('bodegueros.index')->with(compact('msg'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Bodeguero  $bodeguero
     * @return \Illuminate\Http\Response
     */
    public function edit(Bodeguero $bodeguero)
    {
        return view('bodeguero.edit')->with(compact('bodeguero'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Bodeguero  $bodeguero
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Bodeguero $bodeguero)
    {
        $bodeguero->nombre = $request->input('nombre');
        $bodeguero->rut = $request->input('rut');

        if ($bodeguero->saveOrFail()) {
            $msg = [
                "meta" => [
                    "title" => "Bodeguero actualizado exitosamente",
                    "msg" => "El Bodeguero ". $bodeguero->nombre . " fue actualizado"
                ]
            ];

            return redirect()->route('bodegueros.index')->with(compact('msg'));
        } else {
            $msg = [
                "meta" => [
                    "title" => "Bodeguero no pudo ser editado",
                    "msg" => "Ocurrio un error editando el bodeguero"
                ]
            ];

            return redirect()->route('bodegueros.index')->with(compact('msg'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Bodeguero  $bodeguero
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bodeguero $bodeguero)
    {
        $bodeguero->delete();

        $msg = [
            "meta" => [
                "title" => "Bodeguero eliminado",
                "msg" => "El Bodeguero ha sido eliminado exitosamente"
            ]
        ];

        return redirect()->route('bodegueros.index')->with(compact('msg'));
    }
}
