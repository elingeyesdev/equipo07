<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Ganado;
use App\Models\Maquinaria;
use App\Models\Organico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Mostrar el carrito de compras
     */
    public function index()
    {
        $cartItems = CartItem::where('user_id', Auth::id())
            ->with([
                'ganado.imagenes',
                'maquinaria.imagenes',
                'organico.imagenes'
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        $total = $cartItems->sum('subtotal');
        $itemsCount = $cartItems->sum('cantidad');

        return view('cart.index', compact('cartItems', 'total', 'itemsCount'));
    }

    /**
     * Agregar producto al carrito
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_type'  => 'required|in:ganado,maquinaria,organico',
            'product_id'    => 'required|integer',
            'cantidad'      => 'nullable|integer|min:1',
            'dias_alquiler' => 'nullable|integer|min:1',
            'alquiler_unidad' => 'nullable|in:hora,dia',
        ]);

        $productType = $request->product_type;
        $productId   = $request->product_id;

        if ($productType === 'maquinaria') {
            $cantidad = $request->cantidad ?? $request->dias_alquiler ?? 1;
            $alquilerUnidad = $request->alquiler_unidad === 'dia' ? 'dia' : 'hora';
        } else {
            $cantidad = $request->cantidad;
            $alquilerUnidad = null;
        }

        if (!$cantidad || $cantidad < 1) {
            return back()->with('error', 'Debes indicar una cantidad válida.');
        }

        switch ($productType) {
            case 'ganado':
                $product = Ganado::findOrFail($productId);
                $precioUnitario = $product->precio ?? 0;
                $notas = null;
                break;

            case 'maquinaria':
                $product = Maquinaria::findOrFail($productId);
                $precioBase = $product->precio_dia ?? 0;
                if (($product->tarifa_unidad ?? 'hora') === 'dia') {
                    $precioUnitario = $alquilerUnidad === 'dia' ? $precioBase : $precioBase / 8;
                } else {
                    $precioUnitario = $alquilerUnidad === 'dia' ? $precioBase * 8 : $precioBase;
                }
                $notas = $alquilerUnidad === 'dia'
                    ? "Alquiler por {$cantidad} día(s)"
                    : "Alquiler por {$cantidad} hora(s)";
                break;

            case 'organico':
                $product = Organico::findOrFail($productId);
                $precioUnitario = $product->precio ?? 0;
                $notas = $product->unidad ? "Unidad: {$product->unidad->nombre}" : null;
                break;

            default:
                return back()->with('error', 'Tipo de producto no válido.');
        }

        if (!$precioUnitario || $precioUnitario <= 0) {
            return back()->with('error', 'Este producto no tiene precio disponible.');
        }

        if ((int) ($product->user_id ?? 0) === (int) Auth::id()) {
            return back()->with('error', 'No puedes agregar al carrito una publicación propia.');
        }

        if (in_array($productType, ['ganado', 'organico'])) {
            $stock = $product->stock ?? 0;
            if ($stock < $cantidad) {
                return back()->with('error', "Stock insuficiente. Disponible: {$stock}");
            }
        }

        $existingItem = CartItem::where('user_id', Auth::id())
            ->where('product_type', $productType)
            ->where('product_id', $productId)
            ->first();

        if ($existingItem) {
            $shouldReplaceQuantity = $productType === 'maquinaria'
                && $existingItem->alquiler_unidad
                && $existingItem->alquiler_unidad !== $alquilerUnidad;

            $existingItem->alquiler_unidad = $alquilerUnidad;
            $existingItem->precio_unitario = $precioUnitario;
            $existingItem->cantidad = $shouldReplaceQuantity ? $cantidad : $existingItem->cantidad + $cantidad;
            $existingItem->notas = $alquilerUnidad === 'dia'
                ? "Alquiler por {$existingItem->cantidad} día(s)"
                : "Alquiler por {$existingItem->cantidad} hora(s)";
            $existingItem->subtotal = $existingItem->precio_unitario * $existingItem->cantidad;
            $existingItem->save();
        } else {
            CartItem::create([
                'user_id'        => Auth::id(),
                'product_type'   => $productType,
                'product_id'     => $productId,
                'cantidad'       => $cantidad,
                'alquiler_unidad' => $alquilerUnidad,
                'precio_unitario' => $precioUnitario,
                'subtotal'       => $precioUnitario * $cantidad,
                'notas'          => $notas,
            ]);
        }

        return back()->with('success', 'Producto agregado al carrito correctamente.');
    }


    /**
     * Actualizar cantidad de un item
     */
    public function update(Request $request, CartItem $cartItem)
    {
        if ($cartItem->user_id !== Auth::id()) {
            return back()->with('error', 'No tienes permisos para modificar este item.');
        }

        $request->validate([
            'cantidad' => 'required|integer|min:1',
        ]);

        $cantidad = $request->cantidad;

        $product = $cartItem->product;
        if ($product && in_array($cartItem->product_type, ['ganado', 'organico'])) {
            $stock = $product->stock ?? 0;
            if ($stock < $cantidad) {
                return back()->with('error', "Stock insuficiente. Disponible: {$stock}");
            }
        }

        $cartItem->cantidad = $cantidad;
        if ($cartItem->product_type === 'maquinaria') {
            $cartItem->notas = ($cartItem->alquiler_unidad ?? 'hora') === 'dia'
                ? "Alquiler por {$cantidad} día(s)"
                : "Alquiler por {$cantidad} hora(s)";
        }
        $cartItem->subtotal = $cartItem->precio_unitario * $cantidad;
        $cartItem->save();

        return back()->with('success', 'Carrito actualizado correctamente.');
    }

    /**
     * Eliminar item del carrito
     */
    public function remove(CartItem $cartItem)
    {
        if ($cartItem->user_id !== Auth::id()) {
            return back()->with('error', 'No tienes permisos para eliminar este item.');
        }

        $cartItem->delete();

        return back()->with('success', 'Producto eliminado del carrito.');
    }

    /**
     * Vaciar el carrito
     */
    public function clear()
    {
        CartItem::where('user_id', Auth::id())->delete();

        return redirect()->route('cart.index')->with('success', 'Carrito vaciado correctamente.');
    }

    /**
     * Obtener el conteo de items en el carrito (para AJAX)
     */
    public function getCount()
    {
        $count = CartItem::where('user_id', Auth::id())->sum('cantidad');
        return response()->json(['count' => $count]);
    }
}
