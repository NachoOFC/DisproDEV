<?php

namespace App\Http\Controllers;

use App\Cierre;

class DeleteCierre extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Cierre $cierre)
    {
        $cierre->delete();

        return back();
    }
}
