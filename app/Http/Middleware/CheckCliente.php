<?php

namespace App\Http\Middleware;

use Closure;

class CheckCliente
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
        if ($request->user()->userable instanceof \App\CompassRole) {
            return redirect()->to('/alogis/');
        }
        return $next($request);
    }
}
