<?php

namespace App\Http\Controllers;

use App\Models\MarcaMaquinaria;
use Illuminate\Http\Request;

class MarcaMaquinariaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $q = request('q');
        $items = MarcaMaquinaria::when(
            $q,
            fn($qb) =>
            $qb->where('nombre', 'ilike', "%$q%")
        )
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('marcas_maquinarias.index', compact('items', 'q'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('marcas_maquinarias.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:marcas_maquinarias,nombre',
            'descripcion' => 'nullable|string|max:5000',
        ]);

        MarcaMaquinaria::create($request->only('nombre', 'descripcion'));

        return redirect()->route('admin.marcas_maquinarias.index')->with('ok', 'Marca de maquinaria creada');
    }

    /**
     * Display the specified resource.
     */
    public function show(MarcaMaquinaria $marcaMaquinaria)
    {
        return view('marcas_maquinarias.show', compact('marcaMaquinaria'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MarcaMaquinaria $marcaMaquinaria)
    {
        return view('marcas_maquinarias.edit', compact('marcaMaquinaria'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MarcaMaquinaria $marcaMaquinaria)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:marcas_maquinarias,nombre,' . $marcaMaquinaria->id,
            'descripcion' => 'nullable|string|max:5000',
        ]);

        $marcaMaquinaria->update($request->only('nombre', 'descripcion'));

        return redirect()->route('admin.marcas_maquinarias.index')->with('ok', 'Marca de maquinaria actualizada');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MarcaMaquinaria $marcaMaquinaria)
    {
        // Verificar si hay maquinarias usando esta marca
        if ($marcaMaquinaria->maquinarias()->count() > 0) {
            return redirect()->route('admin.marcas_maquinarias.index')
                ->with('error', 'No se puede eliminar esta marca de maquinaria porque tiene maquinarias asociadas.');
        }

        $marcaMaquinaria->delete();

        return redirect()->route('admin.marcas_maquinarias.index')->with('ok', 'Marca de maquinaria eliminada');
    }
}
