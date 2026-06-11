<?php

namespace App\Http\Controllers;

use App\Models\PedidoDetalle;
use App\Models\Reclamo;
use App\Models\ResenaProducto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InteraccionOrganicoController extends Controller
{
    public function guardarResena(Request $request, PedidoDetalle $detalle)
    {
        $this->autorizarComprador($detalle);

        if (!$this->puedeCrearResena($detalle)) {
            return back()->with('error', 'La reseña se habilita cuando la entrega fue confirmada o finalizada.');
        }

        if ($detalle->resenaProducto()->exists()) {
            return back()->with('error', 'Ya registraste una reseña para este producto.');
        }

        $data = $request->validate([
            'estrellas' => ['required', 'integer', 'between:1,5'],
            'comentario' => ['required', 'string', 'min:10', 'max:1200'],
        ]);

        ResenaProducto::create([
            'pedido_detalle_id' => $detalle->id,
            'comprador_id' => Auth::id(),
            'vendedor_id' => $detalle->vendedor_id,
            'product_type' => $detalle->product_type,
            'product_id' => $detalle->product_id,
            'organico_id' => $detalle->product_type === 'organico' ? $detalle->product_id : null,
            ...$data,
        ]);

        return back()->with('success', 'Gracias. Tu calificacion fue registrada.');
    }

    public function guardarReclamo(Request $request, PedidoDetalle $detalle)
    {
        $rol = $this->autorizarParticipante($detalle);

        if (!$this->puedeCrearReclamo($detalle)) {
            return back()->with('error', 'El reclamo se habilita después de una entrega o cancelación.');
        }

        if ($detalle->reclamos()->where('creador_id', Auth::id())->exists()) {
            return back()->with('error', 'Ya registraste un reclamo para este producto.');
        }

        $data = $request->validate([
            'tipo' => ['required', 'in:' . implode(',', array_keys(Reclamo::TIPOS))],
            'descripcion' => ['required', 'string', 'min:15', 'max:2000'],
        ]);

        Reclamo::create([
            'pedido_detalle_id' => $detalle->id,
            'creador_id' => Auth::id(),
            'creador_rol' => $rol,
            'tipo' => $data['tipo'],
            'descripcion' => $data['descripcion'],
        ]);

        return back()->with('success', 'Tu reclamo fue recibido y quedó registrado para revisión.');
    }

    private function autorizarComprador(PedidoDetalle $detalle): void
    {
        $detalle->loadMissing('pedido');

        abort_unless(
            in_array($detalle->product_type, ['ganado', 'maquinaria', 'organico'], true)
                && (int) $detalle->pedido->user_id === (int) Auth::id(),
            403
        );
    }

    private function autorizarParticipante(PedidoDetalle $detalle): string
    {
        $detalle->loadMissing('pedido');
        abort_unless(
            in_array($detalle->product_type, ['ganado', 'maquinaria', 'organico'], true),
            404
        );

        if ((int) $detalle->pedido->user_id === (int) Auth::id()) {
            return 'comprador';
        }

        if ((int) $detalle->vendedor_id === (int) Auth::id()) {
            return 'vendedor';
        }

        abort(403);
    }

    private function puedeCrearResena(PedidoDetalle $detalle): bool
    {
        return $detalle->estado_solicitud === 'aceptada'
            && $detalle->estado_transporte_actual === 'entregado'
            && in_array($detalle->pedido->estado, ['entregado', 'finalizado'], true);
    }

    private function puedeCrearReclamo(PedidoDetalle $detalle): bool
    {
        return $detalle->estado_solicitud === 'aceptada'
            && (
                in_array($detalle->estado_transporte_actual, ['entregado', 'cancelado'], true)
                || $detalle->pedido->estado === 'finalizado'
            );
    }
}
