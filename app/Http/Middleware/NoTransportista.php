<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NoTransportista
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()?->isTransportista()) {
            return redirect()->route('transportista.envios.index');
        }

        return $next($request);
    }
}
