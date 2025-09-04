<?php

namespace App\Http\Controllers;

use App\Ajuste;
use App\Http\Requests\AjusteForm;

class AjusteController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $items = Ajuste::all();
        $title = 'Ajuste';
        $createRoute = route('ajustes.create');

        return view('control/generic/index')->with(compact('title', 'items', 'createRoute'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $storeRoute = route('ajustes.store');
        $title = 'Ajuste';
        return view('control/ajuste/create')->with(compact('title', 'storeRoute'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AjusteForm $request)
    {
        $data = $request->validated();
        $item = Ajuste::create($data);

        if ($data['continue']) {
            return redirect()->back();
        } else {
            return redirect()->route('ajustes.index');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Ajuste  $ajuste
     * @return \Illuminate\Http\Response
     */
    public function show(Ajuste $ajuste)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Ajuste  $ajuste
     * @return \Illuminate\Http\Response
     */
    public function edit(Ajuste $ajuste)
    {
        $updateRoute = route('ajustes.update', $ajuste);
        $item = $ajuste;
        $title = 'Ajuste';
        return view('control/ajuste/edit')->with(compact('title', 'updateRoute', 'item'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Ajuste  $ajuste
     * @return \Illuminate\Http\Response
     */
    public function update(AjusteForm $request, Ajuste $ajuste)
    {
        $updated = $ajuste->update($request->validated());

        return redirect()->route('ajustes.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Ajuste  $ajuste
     * @return \Illuminate\Http\Response
     */
    public function destroy($ajusteId)
    {
        Ajuste::find($ajusteId)->delete();

        return response()->json("OK");
    }
}
