<?php

namespace App\Http\Controllers;

use App\Entrada;
use App\Http\Requests\GenericForm;
use Illuminate\Http\Request;

class EntradaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $items = Entrada::all();
        $title = 'Entrada';
        $createRoute = route('entradas.create');

        return view('control/generic/index')->with(compact('title', 'items', 'createRoute'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $storeRoute = route('entradas.store');
        $title = 'Entrada';
        return view('control/entrada/create')->with(compact('title', 'storeRoute'));
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
        $item = Entrada::create($data);

        if ($data['continue']) {
            return redirect()->back();
        } else {
            return redirect()->route('entradas.index');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Entrada  $entrada
     * @return \Illuminate\Http\Response
     */
    public function show(Entrada $entrada)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Entrada  $entrada
     * @return \Illuminate\Http\Response
     */
    public function edit(Entrada $entrada)
    {
        $updateRoute = route('entradas.update', $entrada);
        $item = $entrada;
        $title = 'Entrada';
        return view('control/entrada/edit')->with(compact('title', 'updateRoute', 'item'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Entrada  $entrada
     * @return \Illuminate\Http\Response
     */
    public function update(GenericForm $request, Entrada $entrada)
    {
        $updated = $entrada->update($request->validated());

        return redirect()->route('entradas.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Entrada  $entrada
     * @return \Illuminate\Http\Response
     */
    public function destroy($entradaId)
    {
        Entrada::find($entradaId)->delete();

        return response()->json("OK");
    }
}
