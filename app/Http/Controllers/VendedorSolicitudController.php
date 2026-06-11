<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\PedidoDetalle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\TransporteAccesoService;

class VendedorSolicitudController extends Controller
{
    public function index(Request $request)
    {
        $query = PedidoDetalle::with(['pedido.user', 'transporteAcceso'])
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

        $solicitud->load([
            'pedido.user',
            'ultimaUbicacion',
            'transporteAcceso',
            'transporteEventos' => fn ($query) => $query->latest('created_at')->limit(10),
            'resenaProducto.comprador',
            'reclamos.creador',
        ]);
        $estados = $this->estados();

        return view('vendedor.solicitudes.show', compact('solicitud', 'estados'));
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

                $solicitud->update([
                    'estado_solicitud' => 'aceptada',
                    'estado_alquiler' => $solicitud->product_type === 'maquinaria' ? 'aceptado' : null,
                    'estado_transporte' => $solicitud->product_type === 'organico' ? 'aceptado' : 'asignado',
                    'respondido_at' => now(),
                ]);

                if (in_array($solicitud->product_type, ['organico', 'maquinaria'], true)) {
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
            ->route('vendedor.solicitudes.show', $solicitud)
            ->with(
                'success',
                in_array($solicitud->product_type, ['organico', 'maquinaria'], true)
                    ? 'Solicitud aceptada. Se genero el codigo para el transportista externo.'
                    : 'Solicitud aceptada. Las demas solicitudes pendientes de este producto fueron canceladas.'
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

        return back()->with('success', 'Solicitud rechazada.');
    }

    public function finalizarPedido(PedidoDetalle $solicitud)
    {
        $this->authorizeSeller($solicitud);

        if ($solicitud->estado_solicitud !== 'aceptada') {
            return back()->with('error', 'Solo puedes finalizar pedidos con una solicitud aceptada.');
        }

        if (!$solicitud->puede_finalizar_desde_vendedor) {
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
