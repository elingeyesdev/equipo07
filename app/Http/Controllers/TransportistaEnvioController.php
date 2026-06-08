<?php

namespace App\Http\Controllers;

use App\Models\PedidoDetalle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransportistaEnvioController extends Controller
{
    public function index(Request $request)
    {
        $query = PedidoDetalle::with(['pedido.user', 'vendedor'])
            ->where('transportista_id', Auth::id())
            ->where('estado_solicitud', 'aceptada')
            ->orderByDesc('updated_at');

        if ($request->filled('estado')) {
            $query->where('estado_transporte', $request->estado);
        }

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('nombre_producto', 'like', "%{$q}%")
                    ->orWhereHas('pedido.user', function ($buyer) use ($q) {
                        $buyer->where('name', 'like', "%{$q}%")
                            ->orWhere('email', 'like', "%{$q}%");
                    });
            });
        }

        $envios = $query->paginate(12)->withQueryString();
        $estados = PedidoDetalle::transporteEstados();

        return view('transportista.envios.index', compact('envios', 'estados'));
    }

    public function show(PedidoDetalle $envio)
    {
        $this->authorizeTransportista($envio);

        $envio->load(['pedido.user', 'vendedor']);

        return view('transportista.envios.show', compact('envio'));
    }

    public function tracking(PedidoDetalle $envio)
    {
        $this->authorizeTransportista($envio);

        if ($envio->estado_solicitud !== 'aceptada') {
            return redirect()
                ->route('transportista.envios.show', $envio)
                ->with('error', 'Este envío todavía no está aceptado.');
        }

        $solicitud = $envio->load(['pedido.user', 'vendedor']);

        return view('vendedor.solicitudes.tracking', compact('solicitud'));
    }

    private function authorizeTransportista(PedidoDetalle $envio): void
    {
        if ((int) $envio->transportista_id !== (int) Auth::id() && !Auth::user()?->isAdmin()) {
            abort(403);
        }
    }
}
