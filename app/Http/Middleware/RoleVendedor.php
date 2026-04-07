<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleVendedor
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

        $user = auth()->user();

        // Permitir acceso a vendedores y administradores
        if (!$user->isVendedor() && !$user->isAdmin()) {
            return redirect()->route('home')
                ->with('error', 'Debes ser vendedor o administrador para acceder a esta página.');
        }

        return $next($request);
    }
}
