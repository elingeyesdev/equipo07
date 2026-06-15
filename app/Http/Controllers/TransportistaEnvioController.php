<?php

namespace App\Http\Controllers;

use App\Models\PedidoDetalle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransportistaEnvioController extends Controller
{
    public function index(Request $request)
    {
        return $this->listado($request, false);
    }

    public function historial(Request $request)
    {
        return $this->listado($request, true);
    }

    private function listado(Request $request, bool $historial)
    {
        $query = PedidoDetalle::with(['pedido.user', 'vendedor'])
            ->where('transportista_id', Auth::id())
            ->where('estado_solicitud', 'aceptada');

        if ($historial) {
            $query->where(function ($sub) {
                $sub->whereHas('pedido', function ($pedido) {
                    $pedido->whereIn('estado', ['finalizado', 'cancelado']);
                })
                    ->orWhere('estado_alquiler', 'finalizado')
                    ->orWhere('estado_transporte', 'devuelto_vendedor');
            });
        } else {
            $query->where(function ($sub) {
                $sub->whereDoesntHave('pedido', function ($pedido) {
                    $pedido->whereIn('estado', ['finalizado', 'cancelado']);
                })
                    ->where(function ($active) {
                        $active->whereNull('estado_alquiler')
                            ->orWhere('estado_alquiler', '!=', 'finalizado');
                    })
                    ->where(function ($active) {
                        $active->whereNull('estado_transporte')
                            ->orWhere('estado_transporte', '!=', 'devuelto_vendedor');
                    });
            });
        }

        $query->orderByDesc('updated_at');

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
        $modoHistorial = $historial;

        return view('transportista.envios.index', compact('envios', 'estados', 'modoHistorial'));
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
