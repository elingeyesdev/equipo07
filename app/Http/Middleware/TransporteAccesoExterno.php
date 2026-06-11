<?php

namespace App\Http\Middleware;

use App\Models\TransporteAcceso;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TransporteAccesoExterno
{
    public function handle(Request $request, Closure $next): Response
    {
        $accesoId = $request->session()->get('transporte_acceso_id');
        $acceso = $accesoId
            ? TransporteAcceso::with([
                'detalle.pedido.user',
                'detalle.vendedor',
                'detalle.ganado',
                'detalle.maquinaria',
                'detalle.organico',
            ])
                ->find($accesoId)
            : null;

        if (!$acceso || !$acceso->estaActivo()
            || $acceso->detalle?->estado_solicitud !== 'aceptada') {
            $request->session()->forget('transporte_acceso_id');

            return redirect()
                ->route('transporte.index')
                ->with('error', 'El acceso de transporte vencio o ya no esta disponible.');
        }

        $request->attributes->set('transporteAcceso', $acceso);

        return $next($request);
    }
}
