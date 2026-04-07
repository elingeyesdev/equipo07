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
        ]);

        $productType = $request->product_type;
        $productId   = $request->product_id;

        if ($productType === 'maquinaria') {
            $cantidad = $request->dias_alquiler ?? 1;
        } else {
            $cantidad = $request->cantidad;
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
                $precioUnitario = $product->precio_dia ?? 0;
                $notas = "Alquiler por {$cantidad} día(s)";
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
            $existingItem->cantidad += $cantidad;
            $existingItem->subtotal = $existingItem->precio_unitario * $existingItem->cantidad;
            $existingItem->save();
        } else {
            CartItem::create([
                'user_id'        => Auth::id(),
                'product_type'   => $productType,
                'product_id'     => $productId,
                'cantidad'       => $cantidad,
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
        $cartItem->subtotal = $cartItem->precio_unitario * $cantidad;
        $cartItem->save();

        return back()->with('success', 'Cantidad actualizada correctamente.');
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
