<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ServicioClienteController extends Controller
{
    public function view()
    {
        return view("compass/servicio-cliente");
    }

    public function submit(Request $request)
    {
        $status = [
            "minuta" => false,
            "saludable" => false,
        ];

        if ($request->hasFile("minuta") && $request->file("minuta")->isValid()) {
            $request->file("minuta")->storeAs(
                'rrhh',
                "minuta.pdf",
                "real_public"
            );
            $status["minuta"] = true;
        }

        if ($request->hasFile("saludable") && $request->file("saludable")->isValid()) {
            $request->file("saludable")->storeAs(
                'rrhh',
                "saludable.pdf",
                "real_public"
            );
            $status["saludable"] = true;
        }

        return view("compass/servicio-cliente")->with(compact("status"));
    }
}
