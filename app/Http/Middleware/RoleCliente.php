<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleCliente
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('error', 'Debes iniciar sesión para acceder a esta página.');
        }

        // Solo clientes pueden acceder (no vendedores ni admins)
        if (auth()->user()->isVendedor() || auth()->user()->isAdmin()) {
            return redirect()->route('home')
                ->with('info', 'Esta sección es exclusiva para clientes.');
        }

        return $next($request);
    }
}
