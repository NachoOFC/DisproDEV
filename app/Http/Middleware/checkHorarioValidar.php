<?php

namespace App\Http\Middleware;

use Closure;

class checkHorarioValidar
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
        $empresa = $request->user()->userable;
        if (!$empresa->puedeValidar()) {
            $msg = [
                "meta" => [
                    "title" => "Fuera de Horario",
                    "msg" => "No se pueden validar pedidos fuera de horario"
                ]
            ];
            return redirect()->route('cliente.home')->with(compact('msg'));
        }
        return $next($request);
    }
}
