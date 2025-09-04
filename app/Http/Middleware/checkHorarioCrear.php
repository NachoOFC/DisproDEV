<?php

namespace App\Http\Middleware;

use Closure;

class checkHorarioCrear
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $centro = $request->user()->userable;
        if (!$centro->empresa->puedeCrear($centro)) {
            $msg = [
                "meta" => [
                    "title" => "Fuera de Horario",
                    "msg" => "No se pueden crear pedidos fuera de horario"
                ]
            ];
            return redirect()->route('cliente.home')->with(compact('msg'));
        }
        return $next($request);
    }
}
