<?php

namespace App\Http\Controllers;

use App\Models\TipoCultivo;
use Illuminate\Http\Request;

class TipoCultivoController extends Controller
{
    public function index()
    {
        $q = request('q');
        $items = TipoCultivo::when(
            $q,
            fn($qb) => $qb->where('nombre', 'ilike', "%$q%")
        )
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('tipo_cultivos.index', compact('items', 'q'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255|unique:tipo_cultivos,nombre',
            'descripcion' => 'nullable|string|max:5000',
        ]);

        TipoCultivo::create($data);

        return redirect()->route('admin.tipo_cultivos.index')->with('ok', 'Tipo de cultivo creado.');
    }

    public function update(Request $request, TipoCultivo $tipoCultivo)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255|unique:tipo_cultivos,nombre,' . $tipoCultivo->id,
            'descripcion' => 'nullable|string|max:5000',
        ]);

        $tipoCultivo->update($data);

        return redirect()->route('admin.tipo_cultivos.index')->with('ok', 'Tipo de cultivo actualizado.');
    }

    public function destroy(TipoCultivo $tipoCultivo)
    {
        $tipoCultivo->delete();

        return redirect()->route('admin.tipo_cultivos.index')->with('ok', 'Tipo de cultivo eliminado.');
    }
}
