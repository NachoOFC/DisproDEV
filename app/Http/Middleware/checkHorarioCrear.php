<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class checkHorarioCrear
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
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
