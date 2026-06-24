<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\PedidoDetalle;
use Illuminate\Support\Facades\Auth;

class ComprobantePedidoController extends Controller
{
    public function reserva(Pedido $pedido)
    {
        $this->authorizePedido($pedido);

        $pedido->load(['user']);

        $detallesAceptados = $pedido->detalles()
            ->where('estado_solicitud', 'aceptada')
            ->with('vendedor')
            ->get();

        if ($detallesAceptados->isEmpty()) {
            return back()->with('error', 'El comprobante de reserva se habilita cuando el vendedor acepta el producto.');
        }

        return view('comprobantes.reserva', compact('pedido', 'detallesAceptados'));
    }

    public function final(PedidoDetalle $detalle)
    {
        $detalle->load([
            'pedido.user',
            'vendedor',
            'transportista',
            'transporteEventos',
            'ubicaciones',
        ]);

        $this->authorizeDetalle($detalle);

        if (! $detalle->recepcion_confirmada_at) {
            return back()->with('error', 'El comprobante final se habilita cuando el comprador confirma la recepcion.');
        }

        $detallesEnvio = $detalle->detallesEnvio()
            ->where('pedido_id', $detalle->pedido_id)
            ->where('estado_solicitud', 'aceptada')
            ->with(['vendedor', 'transportista'])
            ->get();

        return view('comprobantes.final', compact('detalle', 'detallesEnvio'));
    }

    private function authorizePedido(Pedido $pedido): void
    {
        $user = Auth::user();

        if ((int) $pedido->user_id === (int) Auth::id() || $user?->isAdmin()) {
            return;
        }

        $autorizado = $pedido->detalles()
            ->where(function ($query) {
                $query->where('vendedor_id', Auth::id())
                    ->orWhere('transportista_id', Auth::id());
            })
            ->exists();

        if (! $autorizado) {
            abort(403);
        }
    }

    private function authorizeDetalle(PedidoDetalle $detalle): void
    {
        $user = Auth::user();

        $autorizado = (int) $detalle->pedido->user_id === (int) Auth::id()
            || (int) $detalle->vendedor_id === (int) Auth::id()
            || (int) $detalle->transportista_id === (int) Auth::id()
            || $user?->isAdmin();

        if (! $autorizado) {
            abort(403);
        }
    }
}
