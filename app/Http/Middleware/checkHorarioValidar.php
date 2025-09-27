<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class checkHorarioValidar
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
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
