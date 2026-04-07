<?php

namespace App\Http\Controllers;

use App\Models\TipoMaquinaria;
use Illuminate\Http\Request;

class TipoMaquinariaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $q = request('q');
        $items = TipoMaquinaria::when(
            $q,
            fn($qb) =>
            $qb->where('nombre', 'ilike', "%$q%")
        )
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('tipo_maquinarias.index', compact('items', 'q'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tipo_maquinarias.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:tipo_maquinarias,nombre',
            'descripcion' => 'nullable|string|max:5000',
        ]);

        TipoMaquinaria::create($request->only('nombre', 'descripcion'));

        return redirect()->route('admin.tipo_maquinarias.index')->with('ok', 'Tipo de maquinaria creado');
    }

    /**
     * Display the specified resource.
     */
    public function show(TipoMaquinaria $tipoMaquinaria)
    {
        return view('tipo_maquinarias.show', compact('tipoMaquinaria'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TipoMaquinaria $tipoMaquinaria)
    {
        return view('tipo_maquinarias.edit', compact('tipoMaquinaria'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TipoMaquinaria $tipoMaquinaria)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:tipo_maquinarias,nombre,' . $tipoMaquinaria->id,
            'descripcion' => 'nullable|string|max:5000',
        ]);

        $tipoMaquinaria->update($request->only('nombre', 'descripcion'));

        return redirect()->route('admin.tipo_maquinarias.index')->with('ok', 'Tipo de maquinaria actualizado');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TipoMaquinaria $tipoMaquinaria)
    {
        // Verificar si hay maquinarias usando este tipo
        if ($tipoMaquinaria->maquinarias()->count() > 0) {
            return redirect()->route('admin.tipo_maquinarias.index')
                ->with('error', 'No se puede eliminar este tipo de maquinaria porque tiene maquinarias asociadas.');
        }

        $tipoMaquinaria->delete();

        return redirect()->route('admin.tipo_maquinarias.index')->with('ok', 'Tipo de maquinaria eliminado');
    }
}
