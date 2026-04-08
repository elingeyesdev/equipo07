<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use Illuminate\Http\Request;

class AdminPedidoController extends Controller
{
    public function index(Request $request)
    {
        $query = Pedido::with('user')->orderBy('created_at', 'desc');

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('fecha_desde')) {
            $query->whereDate('created_at', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->whereDate('created_at', '<=', $request->fecha_hasta);
        }

        if ($request->filled('q')) {
            $q = $request->q;
            $query->whereHas('user', function ($sub) use ($q) {
                $sub->where('name', 'like', "%$q%")
                    ->orWhere('email', 'like', "%$q%");
            });
        }

        $pedidos = $query->paginate(15)->withQueryString();

        $estados = [
            'pendiente'   => 'Pendiente',
            'en_proceso'  => 'En proceso',
            'entregado'   => 'Entregado',
            'cancelado'   => 'Cancelado',
        ];

        return view('admin.pedidos.index', compact('pedidos', 'estados'));
    }

    public function show(Pedido $pedido)
    {
        $pedido->load('detalles', 'user');

        $estados = [
            'pendiente'   => 'Pendiente',
            'en_proceso'  => 'En proceso',
            'entregado'   => 'Entregado',
            'cancelado'   => 'Cancelado',
        ];

        return view('admin.pedidos.show', compact('pedido', 'estados'));
    }

    public function updateEstado(Request $request, Pedido $pedido)
    {
        $request->validate([
            'estado' => 'required|in:pendiente,en_proceso,entregado,cancelado',
        ]);

        $pedido->estado = $request->estado;
        $pedido->save();

        return back()->with('success', 'Estado del pedido actualizado correctamente.');
    }
}
