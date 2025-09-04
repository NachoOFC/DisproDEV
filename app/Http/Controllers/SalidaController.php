<?php

namespace App\Http\Controllers;

use App\Salida;
use App\Http\Requests\GenericForm;
use Illuminate\Http\Request;

class SalidaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $items = Salida::all();
        $title = 'Salida';
        $createRoute = route('salidas.create');

        return view('control/generic/index')->with(compact('title', 'items', 'createRoute'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $storeRoute = route('salidas.store');
        $title = 'Salida';
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
        $item = Salida::create($data);

        if ($data['continue']) {
            return redirect()->back();
        } else {
            return redirect()->route('salidas.index');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Salida  $salida
     * @return \Illuminate\Http\Response
     */
    public function show(Salida $salida)
    {
        $salida->generarPDF();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Salida  $salida
     * @return \Illuminate\Http\Response
     */
    public function edit(Salida $salida)
    {
        $updateRoute = route('salidas.update', $salida);
        $item = $salida;
        $title = 'Salida';
        return view('control/generic/edit')->with(compact('title', 'updateRoute', 'item'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Salida  $salida
     * @return \Illuminate\Http\Response
     */
    public function update(GenericForm $request, Salida $salida)
    {
        $updated = $salida->update($request->validated());

        return redirect()->route('salidas.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Salida  $salida
     * @return \Illuminate\Http\Response
     */
    public function destroy($salidaId)
    {
        Salida::find($salidaId)->delete();

        return response()->json("OK");
    }
}
