<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleTransportista
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('error', 'Debes iniciar sesión para acceder a esta página.');
        }

        $user = auth()->user();

        if (!$user->isTransportista() && !$user->isAdmin()) {
            return redirect()->route('home')
                ->with('error', 'Debes ser transportista para acceder a esta página.');
        }

        return $next($request);
    }
}
