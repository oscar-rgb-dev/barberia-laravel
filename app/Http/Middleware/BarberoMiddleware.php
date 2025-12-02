<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class BarberoMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (!auth()->user()->isBarbero()) {
            abort(403, 'Acceso no autorizado. Se requiere rol de barbero.');
        }

        return $next($request);
    }
}