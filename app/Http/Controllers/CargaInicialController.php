<?php

namespace App\Http\Controllers;

use App\CargaInicial;
use App\Http\Requests\GenericForm;
use Illuminate\Http\Request;

class CargaInicialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $items = CargaInicial::all();
        $title = 'Carga Inicial';
        $createRoute = route('carga-iniciales.create');

        return view('control/generic/index')->with(compact('title', 'items', 'createRoute'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $storeRoute = route('carga-iniciales.store');
        $title = 'Carga Inicial';
        return view('control/generic/create')->with(compact('title', 'storeRoute'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GenericForm $request)
    {
        $data = $request->validated();
        $item = CargaInicial::create($data);

        if ($data['continue']) {
            return redirect()->back();
        } else {
            return redirect()->route('carga-iniciales.index');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CargaInicial  $cargaInicial
     * @return \Illuminate\Http\Response
     */
    public function show(CargaInicial $cargaInicial)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CargaInicial  $cargaInicial
     * @return \Illuminate\Http\Response
     */
    public function edit($cargaInicialId)
    {
        $cargaInicial = CargaInicial::find($cargaInicialId);
        $updateRoute = route('carga-iniciales.update', $cargaInicial);
        $item = $cargaInicial;
        $title = 'Carga Inicial';
        return view('control/generic/edit')->with(compact('title', 'updateRoute', 'item'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CargaInicial  $cargaInicial
     * @return \Illuminate\Http\Response
     */
    public function update(GenericForm $request, CargaInicial $cargaInicial)
    {
        $updated = $cargaInicial->update($request->validated());

        return redirect()->route('carga-iniciales.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CargaInicial  $cargaInicial
     * @return \Illuminate\Http\Response
     */
    public function destroy($cargaInicialId)
    {
        CargaInicial::find($cargaInicialId)->delete();

        return response()->json("OK");
    }
}
