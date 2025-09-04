<?php

namespace App\Http\Controllers;

use App\Http\Requests\BidonForm;
use App\Bidon;

class BidonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bidones = Bidon::all();

        return view('control/bidon/index')->with(compact('bidones'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $proveedores = \App\Proveedor::all();
        return view('control/bidon/create')->with(compact('proveedores'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BidonForm $request)
    {
        $bidon = Bidon::create($request->validated());
        $continue = $request->validated()['continue'];

        if ($continue) {
            return redirect()->back()->with(compact('bidon'));
        } else {
            return redirect()->route('bidones.index')->with(compact('bidon'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Bidon  $bidon
     * @return \Illuminate\Http\Response
     */
    public function show(Bidon $bidon)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Bidon  $bidon
     * @return \Illuminate\Http\Response
     */
    public function edit($bidonId)
    {
        $bidon = Bidon::find($bidonId);
        $proveedores = \App\Proveedor::all();

        return view('control/bidon/edit')->with(compact('proveedores', 'bidon'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Bidon  $bidon
     * @return \Illuminate\Http\Response
     */
    public function update(BidonForm $request, $bidonId)
    {
        $bidon = Bidon::find($bidonId)->update($request->validated());

        return redirect()->route('bidones.index')->with(compact('bidon'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Bidon  $bidon
     * @return \Illuminate\Http\Response
     */
    public function destroy($bidonId)
    {
        Bidon::find($bidonId)->delete();

        return response()->json("OK");
    }
}
