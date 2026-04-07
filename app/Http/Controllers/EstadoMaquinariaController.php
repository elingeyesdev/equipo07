<?php

namespace App\Http\Controllers;

use App\Models\EstadoMaquinaria;
use Illuminate\Http\Request;

class EstadoMaquinariaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $q = request('q');
        $items = EstadoMaquinaria::when(
            $q,
            fn($qb) =>
            $qb->where('nombre', 'ilike', "%$q%")
        )
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('estado_maquinarias.index', compact('items', 'q'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('estado_maquinarias.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:estado_maquinarias,nombre',
            'descripcion' => 'nullable|string|max:5000',
        ]);

        EstadoMaquinaria::create($request->only('nombre', 'descripcion'));

        return redirect()->route('admin.estado_maquinarias.index')->with('ok', 'Estado de maquinaria creado');
    }

    /**
     * Display the specified resource.
     */
    public function show(EstadoMaquinaria $estadoMaquinaria)
    {
        return view('estado_maquinarias.show', compact('estadoMaquinaria'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EstadoMaquinaria $estadoMaquinaria)
    {
        return view('estado_maquinarias.edit', compact('estadoMaquinaria'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EstadoMaquinaria $estadoMaquinaria)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:estado_maquinarias,nombre,' . $estadoMaquinaria->id,
            'descripcion' => 'nullable|string|max:5000',
        ]);

        $estadoMaquinaria->update($request->only('nombre', 'descripcion'));

        return redirect()->route('admin.estado_maquinarias.index')->with('ok', 'Estado de maquinaria actualizado');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EstadoMaquinaria $estadoMaquinaria)
    {
        // Verificar si hay maquinarias usando este estado
        if ($estadoMaquinaria->maquinarias()->count() > 0) {
            return redirect()->route('admin.estado_maquinarias.index')
                ->with('error', 'No se puede eliminar este estado de maquinaria porque tiene maquinarias asociadas.');
        }

        $estadoMaquinaria->delete();

        return redirect()->route('admin.estado_maquinarias.index')->with('ok', 'Estado de maquinaria eliminado');
    }
}
