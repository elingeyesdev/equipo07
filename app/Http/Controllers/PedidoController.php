<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\PedidoDetalle;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\TransporteAcceso;
use App\Models\TransporteEvento;

class PedidoController extends Controller
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
        $query = Pedido::with('detalles')->where('user_id', Auth::id());

        if ($historial) {
            $query->where('estado', 'finalizado');
        } else {
            $query->where('estado', '!=', 'finalizado');
        }

        if ($request->filled('pedido_id')) {
            $busqueda = $request->pedido_id;
            $query->where(function ($sub) use ($busqueda) {
                if (ctype_digit((string) $busqueda)) {
                    $sub->where('id', $busqueda);
                }

                $sub->orWhereHas('detalles', function ($detalle) use ($busqueda) {
                    $detalle->where('nombre_producto', 'like', "%{$busqueda}%")
                        ->orWhere('product_type', 'like', "%{$busqueda}%");
                });
            });
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('desde')) {
            $query->whereDate('created_at', '>=', $request->desde);
        }

        if ($request->filled('hasta')) {
            $query->whereDate('created_at', '<=', $request->hasta);
        }

        $pedidos = $query->orderBy('created_at', 'desc')
            ->paginate(10)
            ->appends($request->query());

        $modoHistorial = $historial;

        return view('pedidos.index', compact('pedidos', 'modoHistorial'));
    }


    public function show(Pedido $pedido)
    {
        if ($pedido->user_id !== Auth::id()) {
            abort(403);
        }

        $pedido->load([
            'detalles.organico',
            'detalles.ganado',
            'detalles.maquinaria',
            'detalles.vendedor',
            'detalles.transporteAcceso',
            'detalles.transporteEventos' => fn ($query) => $query->latest('created_at')->limit(8),
            'detalles.resenaProducto.comprador',
            'detalles.reclamos.creador',
        ]);

        return view('pedidos.show', compact('pedido'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'destino_entrega' => 'required|string|min:5|max:500',
            'telefono_contacto' => ['required', 'string', 'max:25', 'regex:/^[0-9+\-\s()]{7,25}$/'],
            'destino_latitud' => 'required|numeric|between:-90,90',
            'destino_longitud' => 'required|numeric|between:-180,180',
        ], [
            'destino_entrega.required' => 'Debes agregar detalles extra para el vendedor.',
            'destino_entrega.min' => 'Los detalles deben tener al menos 5 caracteres.',
            'destino_entrega.max' => 'Los detalles no deben superar los 500 caracteres.',
            'telefono_contacto.required' => 'Debes ingresar un numero de telefono para que el vendedor pueda contactarte.',
            'telefono_contacto.regex' => 'Ingresa un numero de telefono valido.',
            'telefono_contacto.max' => 'El numero de telefono no debe superar los 25 caracteres.',
            'destino_latitud.required' => 'Debes marcar el destino en el mapa.',
            'destino_longitud.required' => 'Debes marcar el destino en el mapa.',
        ]);

        $userId = Auth::id();

        $cartItems = CartItem::where('user_id', $userId)
            ->with('ganado', 'maquinaria', 'organico')
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Tu carrito está vacío.');
        }

        DB::beginTransaction();

        try {
            $total = $cartItems->sum('subtotal');

            $pedido = Pedido::create([
                'user_id'         => $userId,
                'total'           => $total,
                'estado'          => 'pendiente',
                'destino_entrega' => $request->destino_entrega,
                'telefono_contacto' => $request->telefono_contacto,
                'destino_latitud' => $request->destino_latitud,
                'destino_longitud' => $request->destino_longitud,
            ]);

            foreach ($cartItems as $item) {
                $product = $item->product;

                PedidoDetalle::create([
                    'pedido_id'       => $pedido->id,
                    'vendedor_id'     => $product?->user_id,
                    'estado_solicitud' => 'pendiente',
                    'product_id'      => $item->product_id,
                    'product_type'    => $item->product_type,
                    'nombre_producto' => $product ? $product->nombre : 'Producto eliminado',
                    'cantidad'        => $item->cantidad,
                    'alquiler_unidad' => $item->product_type === 'maquinaria' ? $item->alquiler_unidad : null,
                    'precio_unitario' => $item->precio_unitario,
                    'subtotal'        => $item->subtotal,
                    'notas'           => $item->notas,
                ]);

                // (Opcional) descontar stock si quieres
                // if ($product && in_array($item->product_type, ['ganado', 'organico'])) {
                //     $product->stock = max(0, ($product->stock ?? 0) - $item->cantidad);
                //     $product->save();
                // }
            }

            CartItem::where('user_id', $userId)->delete();

            DB::commit();

            return redirect()
                ->route('pedidos.show', $pedido)
                ->with('success', 'Pedido creado correctamente.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->route('cart.index')->with('error', 'Ocurrió un error al crear el pedido.');
        }
    }

    public function confirmarRecepcion(PedidoDetalle $detalle)
    {
        $detalle->load('pedido');

        if ((int) $detalle->pedido->user_id !== (int) Auth::id()) {
            abort(403);
        }

        if ($detalle->estado_solicitud !== 'aceptada') {
            return back()->with('error', 'Solo puedes confirmar productos aceptados por el vendedor.');
        }

        if ($detalle->estado_transporte_actual !== 'esperando_confirmacion') {
            return back()->with('error', 'El transportista aun no marco la llegada al destino.');
        }

        $detalle->update([
            'estado_transporte' => 'entregado',
            'recepcion_confirmada_at' => now(),
            'estado_alquiler' => $detalle->es_alquiler_maquinaria ? 'en_uso' : $detalle->estado_alquiler,
        ]);

        if (in_array($detalle->product_type, ['organico', 'maquinaria'], true)) {
            TransporteEvento::create([
                'pedido_detalle_id' => $detalle->id,
                'transporte_acceso_id' => $detalle->transporteAcceso?->id,
                'user_id' => Auth::id(),
                'actor' => 'comprador',
                'estado_anterior' => 'esperando_confirmacion',
                'estado_nuevo' => 'entregado',
            ]);
        }

        if ($detalle->product_type === 'organico') {
            $detalle->transporteAcceso?->update([
                'estado' => TransporteAcceso::ESTADO_REVOCADO,
            ]);
        }

        if ($detalle->es_alquiler_maquinaria) {
            $detalle->pedido()->update(['estado' => 'en_uso']);
            return back()->with('success', 'Recepcion confirmada. La maquinaria queda en uso hasta la devolucion.');
        }

        $aceptadosPendientes = $detalle->pedido->detalles()
            ->where('estado_solicitud', 'aceptada')
            ->where(function ($query) {
                $query->whereNull('estado_transporte')
                    ->orWhere('estado_transporte', '!=', 'entregado');
            })
            ->exists();

        $detalle->pedido()->update([
            'estado' => $aceptadosPendientes ? 'entregado' : 'finalizado',
        ]);

        return back()->with('success', 'Recepcion confirmada. La venta fue finalizada correctamente.');
    }
}
