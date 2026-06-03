<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\PedidoDetalle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VendedorSolicitudController extends Controller
{
    public function index(Request $request)
    {
        $query = PedidoDetalle::with(['pedido.user'])
            ->where('vendedor_id', Auth::id())
            ->where('estado_solicitud', '!=', 'cancelada_producto_vendido')
            ->orderByDesc('created_at');

        if ($request->filled('estado')) {
            $query->where('estado_solicitud', $request->estado);
        }

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('nombre_producto', 'like', "%{$q}%")
                    ->orWhereHas('pedido.user', function ($buyer) use ($q) {
                        $buyer->where('name', 'like', "%{$q}%");
                    });
            });
        }

        $solicitudes = $query->paginate(12)->withQueryString();
        $estados = $this->estados();

        return view('vendedor.solicitudes.index', compact('solicitudes', 'estados'));
    }

    public function show(PedidoDetalle $solicitud)
    {
        $this->authorizeSeller($solicitud);

        $solicitud->load(['pedido.user']);
        $estados = $this->estados();

        return view('vendedor.solicitudes.show', compact('solicitud', 'estados'));
    }

    public function aceptar(PedidoDetalle $solicitud)
    {
        $this->authorizeSeller($solicitud);

        if ($solicitud->estado_solicitud !== 'pendiente') {
            return back()->with('error', 'Esta solicitud ya fue respondida.');
        }

        try {
            DB::transaction(function () use ($solicitud) {
                $this->descontarStockDisponible($solicitud);

                $solicitud->update([
                    'estado_solicitud' => 'aceptada',
                    'estado_alquiler' => $solicitud->product_type === 'maquinaria' ? 'aceptado' : null,
                    'respondido_at' => now(),
                ]);

                PedidoDetalle::where('product_type', $solicitud->product_type)
                    ->where('product_id', $solicitud->product_id)
                    ->where('id', '!=', $solicitud->id)
                    ->where('estado_solicitud', 'pendiente')
                    ->update([
                        'estado_solicitud' => 'cancelada_producto_vendido',
                        'respondido_at' => now(),
                    ]);

                Pedido::where('id', $solicitud->pedido_id)->update([
                    'estado' => 'en_proceso',
                ]);
            });
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()
            ->route('vendedor.solicitudes.show', $solicitud)
            ->with('success', 'Solicitud aceptada. Las demas solicitudes pendientes de este producto fueron canceladas.');
    }

    public function cancelar(PedidoDetalle $solicitud)
    {
        $this->authorizeSeller($solicitud);

        if ($solicitud->estado_solicitud !== 'pendiente') {
            return back()->with('error', 'Esta solicitud ya fue respondida.');
        }

        $solicitud->update([
            'estado_solicitud' => 'rechazada',
            'respondido_at' => now(),
        ]);

        return back()->with('success', 'Solicitud rechazada.');
    }

    public function finalizarPedido(PedidoDetalle $solicitud)
    {
        $this->authorizeSeller($solicitud);

        if ($solicitud->estado_solicitud !== 'aceptada') {
            return back()->with('error', 'Solo puedes finalizar pedidos con una solicitud aceptada.');
        }

        if ($solicitud->product_type === 'maquinaria' && $solicitud->estado_alquiler_actual !== 'devuelto') {
            return back()->with('error', 'Primero debes marcar la maquinaria como devuelta antes de finalizar el alquiler.');
        }

        if ($solicitud->product_type === 'maquinaria') {
            $solicitud->update([
                'estado_alquiler' => 'finalizado',
            ]);
        }

        $solicitud->pedido()->update([
            'estado' => 'finalizado',
        ]);

        return back()->with('success', 'Pedido finalizado correctamente.');
    }

    public function avanzarAlquiler(PedidoDetalle $solicitud)
    {
        $this->authorizeSeller($solicitud);

        if ($solicitud->product_type !== 'maquinaria') {
            return back()->with('error', 'Este seguimiento solo aplica a alquileres de maquinaria.');
        }

        if ($solicitud->estado_solicitud !== 'aceptada') {
            return back()->with('error', 'Primero debes aceptar la solicitud para iniciar el seguimiento.');
        }

        $siguienteEstado = $solicitud->siguiente_estado_alquiler;

        if (!$siguienteEstado) {
            return back()->with('error', 'No hay un siguiente estado disponible para este alquiler.');
        }

        $solicitud->update([
            'estado_alquiler' => $siguienteEstado,
        ]);

        $solicitud->pedido()->update([
            'estado' => $siguienteEstado === 'devuelto' ? 'en_proceso' : $siguienteEstado,
        ]);

        return back()->with('success', 'Estado del alquiler actualizado a: ' . PedidoDetalle::alquilerEstados()[$siguienteEstado] . '.');
    }

    private function authorizeSeller(PedidoDetalle $solicitud): void
    {
        if ((int) $solicitud->vendedor_id !== (int) Auth::id()) {
            abort(403);
        }
    }

    private function estados(): array
    {
        return [
            'pendiente' => 'Pendiente',
            'aceptada' => 'Aceptada',
            'rechazada' => 'Rechazada',
            'cancelada_producto_vendido' => 'Producto vendido',
        ];
    }

    private function descontarStockDisponible(PedidoDetalle $solicitud): void
    {
        if (!in_array($solicitud->product_type, ['ganado', 'organico'], true)) {
            return;
        }

        $product = $solicitud->product;
        $stock = (int) ($product?->stock ?? 0);

        if (!$product || $stock < $solicitud->cantidad) {
            throw new \RuntimeException('El producto ya no tiene stock suficiente para aceptar esta solicitud.');
        }

        $nuevoStock = max(0, $stock - $solicitud->cantidad);

        if ($solicitud->product_type === 'ganado') {
            $product->datoComercial()->update(['stock' => $nuevoStock]);
            return;
        }

        $product->datoComercial()->update(['stock' => $nuevoStock]);
    }
}
