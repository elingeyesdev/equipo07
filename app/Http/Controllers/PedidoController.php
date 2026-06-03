<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\PedidoDetalle;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PedidoController extends Controller
{
    public function index(Request $request)
    {
        $query = Pedido::where('user_id', Auth::id());

        if ($request->filled('pedido_id')) {
            $query->where('id', $request->pedido_id);
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

        return view('pedidos.index', compact('pedidos'));
    }


    public function show(Pedido $pedido)
    {
        if ($pedido->user_id !== Auth::id()) {
            abort(403);
        }

        $pedido->load('detalles');

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
}
