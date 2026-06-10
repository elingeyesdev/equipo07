<?php

namespace App\Http\Controllers;

use App\Models\Reclamo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReclamoController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $query = Reclamo::with(['creador', 'detalle.pedido.user', 'detalle.vendedor'])
            ->latest();

        if (!$user->isAdmin()) {
            $query->where(function ($sub) use ($user) {
                $sub->where('creador_id', $user->id)
                    ->orWhereHas('detalle', fn ($detalle) => $detalle->where('vendedor_id', $user->id));
            });
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        $reclamos = $query->paginate(15)->withQueryString();

        return view('reclamos.index', compact('reclamos'));
    }

    public function show(Reclamo $reclamo)
    {
        $reclamo->load(['creador', 'detalle.pedido.user', 'detalle.vendedor', 'detalle.organico']);
        $this->autorizar($reclamo);

        return view('reclamos.show', compact('reclamo'));
    }

    public function actualizarEstado(Request $request, Reclamo $reclamo)
    {
        abort_unless($request->user()?->isAdmin(), 403);

        $data = $request->validate([
            'estado' => ['required', 'in:' . implode(',', array_keys(Reclamo::ESTADOS))],
            'respuesta_admin' => ['nullable', 'string', 'max:2000'],
        ]);

        $reclamo->update($data);

        return back()->with('success', 'El reclamo fue actualizado.');
    }

    private function autorizar(Reclamo $reclamo): void
    {
        $user = Auth::user();

        abort_unless(
            $user?->isAdmin()
                || (int) $reclamo->creador_id === (int) $user?->id
                || (int) $reclamo->detalle->vendedor_id === (int) $user?->id
                || (int) $reclamo->detalle->pedido->user_id === (int) $user?->id,
            403
        );
    }
}
