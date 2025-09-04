<?php

namespace App\Http\Controllers;

use App\Folio;
use Illuminate\Http\Request;

class FolioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $folio = new Folio;
        $folio = \App\Folio::where('activo', true)->latest()->first();

        return view('compass.cargar_folios')->with(compact("folio"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $desde = $request->input('desde');
        $hasta = $request->input('hasta');
        $ultimo = $desde;

        $ultimoFolio = \App\Folio::latest()->first();
	if(!is_null($ultimoFolio)) {
		$ultimoFolio->activo = false;
		$ultimoFolio->save();
	}

        $folio = new Folio;
        $folio->desde = $desde;
        $folio->hasta = $hasta;
        $folio->ultimo = $ultimo;
        $folio->activo = true;
        
        if ($folio->saveOrFail()) {
                $msg = [
                    'meta' => [
                        'title' => '¡Folios cargados exitosamente!',
                        'msg' => 'Ya se pueden utilizar los folios:' . $desde . ' - ' . $hasta
                    ]
                ];

                return back()->with(compact('msg'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Folio  $folio
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Folio $folio)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Folio  $folio
     * @return \Illuminate\Http\Response
     */
    public function edit(Folio $folio)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Folio  $folio
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Folio $folio)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Folio  $folio
     * @return \Illuminate\Http\Response
     */
    public function destroy(Folio $folio)
    {
        //
    }
}
