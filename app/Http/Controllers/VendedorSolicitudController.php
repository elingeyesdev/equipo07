<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\PedidoDetalle;
use App\Services\TransporteAccesoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VendedorSolicitudController extends Controller
{
    public function index(Request $request)
    {
        $query = PedidoDetalle::query()
            ->where('vendedor_id', Auth::id())
            ->where('estado_solicitud', '!=', 'cancelada_producto_vendido');

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

        $solicitudes = $query
            ->selectRaw('MIN(id) as id, grupo_envio, MAX(created_at) as ultima_solicitud_at')
            ->groupBy('grupo_envio')
            ->orderByDesc('ultima_solicitud_at')
            ->paginate(12)
            ->withQueryString();

        $representantes = PedidoDetalle::with([
            'pedido.user',
            'transporteAcceso',
            'detallesEnvio' => fn ($detalle) => $detalle
                ->where('vendedor_id', Auth::id())
                ->where('estado_solicitud', '!=', 'cancelada_producto_vendido')
                ->orderBy('id'),
        ])->whereIn('id', $solicitudes->getCollection()->pluck('id'))
            ->get()
            ->keyBy('id');

        $solicitudes->setCollection(
            $solicitudes->getCollection()
                ->map(fn ($fila) => $representantes->get($fila->id))
                ->filter()
                ->values()
        );
        $estados = $this->estados();

        return view('vendedor.solicitudes.index', compact('solicitudes', 'estados'));
    }

    public function show(PedidoDetalle $solicitud)
    {
        $this->authorizeSeller($solicitud);

        $detallesGrupo = PedidoDetalle::with([
            'pedido.user',
            'ultimaUbicacion',
            'transporteAcceso',
            'transporteEventos' => fn ($query) => $query->latest('created_at')->limit(10),
            'resenaProducto.comprador',
            'reclamos.creador',
        ])
            ->where('grupo_envio', $solicitud->grupo_envio)
            ->where('vendedor_id', Auth::id())
            ->where('estado_solicitud', '!=', 'cancelada_producto_vendido')
            ->orderBy('id')
            ->get();

        $solicitud = $detallesGrupo->firstWhere('estado_solicitud', 'aceptada')
            ?? $detallesGrupo->firstOrFail();
        $solicitud->setRelation('detallesEnvio', $detallesGrupo);
        $estados = $this->estados();

        return view('vendedor.solicitudes.show', compact('solicitud', 'detallesGrupo', 'estados'));
    }

    public function aceptar(PedidoDetalle $solicitud, TransporteAccesoService $transporteService)
    {
        $this->authorizeSeller($solicitud);

        if ($solicitud->estado_solicitud !== 'pendiente') {
            return back()->with('error', 'Esta solicitud ya fue respondida.');
        }

        try {
            DB::transaction(function () use ($solicitud, $transporteService) {
                $this->descontarStockDisponible($solicitud);

                $accesoGrupo = \App\Models\TransporteAcceso::where(
                    'grupo_envio',
                    $solicitud->grupo_envio
                )->first();
                $estadoGrupo = $accesoGrupo?->detalle?->estado_transporte_actual;

                $solicitud->update([
                    'estado_solicitud' => 'aceptada',
                    'estado_alquiler' => $solicitud->product_type === 'maquinaria' ? 'aceptado' : null,
                    'estado_transporte' => $estadoGrupo
                        ?: ($solicitud->product_type === 'maquinaria' ? 'asignado' : 'aceptado'),
                    'respondido_at' => now(),
                ]);

                if (in_array($solicitud->product_type, ['organico', 'ganado', 'maquinaria'], true)) {
                    $transporteService->generar($solicitud->fresh(), Auth::id());
                }

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
            ->route('vendedor.solicitudes.show', $this->representanteGrupo($solicitud))
            ->with(
                'success',
                'Producto aceptado. El inventario y el estado del pedido fueron actualizados.'
            );
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

        $this->actualizarEstadoPedidoTrasRespuesta($solicitud);

        return redirect()
            ->route('vendedor.solicitudes.show', $this->representanteGrupo($solicitud))
            ->with('success', 'Producto rechazado. Los demas productos del pedido mantienen su estado.');
    }

    public function finalizarPedido(PedidoDetalle $solicitud)
    {
        $this->authorizeSeller($solicitud);

        if ($solicitud->estado_solicitud !== 'aceptada') {
            return back()->with('error', 'Solo puedes finalizar pedidos con una solicitud aceptada.');
        }

        if (! $solicitud->puede_finalizar_desde_vendedor) {
            if ($solicitud->product_type === 'maquinaria') {
                return back()->with('error', 'Primero el transportista debe devolver la maquinaria antes de finalizar el alquiler.');
            }

            return back()->with('error', 'Primero el comprador debe confirmar la recepcion del producto.');
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

        return back()->with('error', 'El seguimiento del alquiler lo maneja el acceso de transporte por codigo o QR.');
    }

    public function regenerarCodigo(
        PedidoDetalle $solicitud,
        TransporteAccesoService $transporteService
    ) {
        $this->authorizeSeller($solicitud);
        $transporteService->generar($solicitud, Auth::id(), true);

        return back()->with('success', 'Se genero un nuevo codigo. El codigo anterior dejo de funcionar.');
    }

    public function revocarCodigo(PedidoDetalle $solicitud, TransporteAccesoService $transporteService)
    {
        $this->authorizeSeller($solicitud);

        if ($solicitud->transporteAcceso) {
            $transporteService->revocar($solicitud->transporteAcceso);
        }

        return back()->with('success', 'El acceso externo de transporte fue revocado.');
    }

    public function marcarPreparado(
        PedidoDetalle $solicitud,
        TransporteAccesoService $transporteService
    ) {
        $this->authorizeSeller($solicitud);
        $transporteService->prepararPorVendedor($solicitud, Auth::id());

        return back()->with('success', 'Transporte habilitado. La persona con el codigo o QR ya puede iniciar el recorrido y compartir GPS.');
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
        if (! in_array($solicitud->product_type, ['ganado', 'organico'], true)) {
            return;
        }

        $product = $solicitud->product;
        $stock = (int) ($product?->stock ?? 0);

        if (! $product || $stock < $solicitud->cantidad) {
            throw new \RuntimeException('El producto ya no tiene stock suficiente para aceptar esta solicitud.');
        }

        $nuevoStock = max(0, $stock - $solicitud->cantidad);

        if ($solicitud->product_type === 'ganado') {
            $product->datoComercial()->update(['stock' => $nuevoStock]);

            return;
        }

        $product->datoComercial()->update(['stock' => $nuevoStock]);
    }

    private function representanteGrupo(PedidoDetalle $solicitud): PedidoDetalle
    {
        return PedidoDetalle::where('grupo_envio', $solicitud->grupo_envio)
            ->where('vendedor_id', Auth::id())
            ->where('estado_solicitud', '!=', 'cancelada_producto_vendido')
            ->orderBy('id')
            ->firstOrFail();
    }

    private function actualizarEstadoPedidoTrasRespuesta(PedidoDetalle $solicitud): void
    {
        $detalles = PedidoDetalle::where('pedido_id', $solicitud->pedido_id)
            ->where('estado_solicitud', '!=', 'cancelada_producto_vendido');

        if ((clone $detalles)->where('estado_solicitud', 'pendiente')->exists()) {
            return;
        }

        $estado = (clone $detalles)->where('estado_solicitud', 'aceptada')->exists()
            ? 'en_proceso'
            : 'rechazado';

        Pedido::whereKey($solicitud->pedido_id)->update(['estado' => $estado]);
    }
}
