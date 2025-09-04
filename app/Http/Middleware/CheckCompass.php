<?php

namespace App\Http\Middleware;

use Closure;

class CheckCompass
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
        if (!($request->user()->userable instanceof \App\CompassRole)) {
            return redirect()->to('/cliente/');
        }
        return $next($request);
    }
}
