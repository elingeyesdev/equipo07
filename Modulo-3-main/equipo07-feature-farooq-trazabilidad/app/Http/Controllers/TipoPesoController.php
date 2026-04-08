<?php

namespace App\Http\Controllers;

use App\Models\TipoPeso;
use Illuminate\Http\Request;

class TipoPesoController extends Controller
{
    public function index()
    {
        $items = TipoPeso::orderBy('id', 'desc')->get();
        return view('tipo_pesos.index', compact('items'));
    }

    public function create()
    {
        return view('tipo_pesos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
        ]);

        TipoPeso::create($request->all());

        return redirect()->route('admin.tipo-pesos.index')
            ->with('success', 'Tipo de Peso creado correctamente.');
    }

    public function edit(TipoPeso $tipoPeso)
    {
        return view('tipo_pesos.edit', compact('tipoPeso'));
    }

    public function update(Request $request, TipoPeso $tipoPeso)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
        ]);

        $tipoPeso->update($request->all());

        return redirect()->route('admin.tipo-pesos.index')
            ->with('success', 'Tipo de Peso actualizado correctamente.');
    }

    public function destroy(TipoPeso $tipoPeso)
    {
        $tipoPeso->delete();

        return redirect()->route('admin.tipo-pesos.index')
            ->with('success', 'Tipo de Peso eliminado correctamente.');
    }
}
