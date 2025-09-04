<?php

namespace App\Http\Controllers;

use App\NotaCredito;
use App\Http\Requests\GenericForm;
use Illuminate\Http\Request;

class NotaCreditoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $items = NotaCredito::all();
        $title = 'Nota Credito';
        $createRoute = route('nota-creditos.create');

        return view('control/generic/index')->with(compact('title', 'items', 'createRoute'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $storeRoute = route('nota-creditos.store');
        $title = 'Nota Credito';
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
        $item = NotaCredito::create($data);

        if ($data['continue']) {
            return redirect()->back();
        } else {
            return redirect()->route('nota-creditos.index');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\NotaCredito  $notaCredito
     * @return \Illuminate\Http\Response
     */
    public function show(NotaCredito $notaCredito)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\NotaCredito  $notaCredito
     * @return \Illuminate\Http\Response
     */
    public function edit(NotaCredito $notaCredito)
    {
        $updateRoute = route('nota-creditos.update', $notaCredito);
        $item = $notaCredito;
        $title = 'Nota Credito';
        return view('control/entrada/edit')->with(compact('title', 'updateRoute', 'item'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\NotaCredito  $notaCredito
     * @return \Illuminate\Http\Response
     */
    public function update(GenericForm $request, NotaCredito $notaCredito)
    {
        $updated = $notaCredito->update($request->validated());

        return redirect()->route('nota-creditos.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\NotaCredito  $notaCredito
     * @return \Illuminate\Http\Response
     */
    public function destroy($notaCreditoId)
    {
        NotaCredito::find($notaCreditoId)->delete();

        return response()->json("OK");
    }
}
