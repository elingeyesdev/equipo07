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
                'user_id' => $userId,
                'total'   => $total,
                'estado'  => 'pendiente',
            ]);

            foreach ($cartItems as $item) {
                $product = $item->product;

                PedidoDetalle::create([
                    'pedido_id'       => $pedido->id,
                    'product_id'      => $item->product_id,
                    'product_type'    => $item->product_type,
                    'nombre_producto' => $product ? $product->nombre : 'Producto eliminado',
                    'cantidad'        => $item->cantidad,
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
