<?php

namespace App\Http\Controllers;

use App\Models\UnidadOrganico;
use Illuminate\Http\Request;

class UnidadOrganicoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $q = request('q');
        $items = UnidadOrganico::when(
            $q,
            fn($qb) =>
            $qb->where('nombre', 'ilike', "%$q%")
        )
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('unidades_organicos.index', compact('items', 'q'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('unidades_organicos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:unidades_organicos,nombre',
            'descripcion' => 'nullable|string|max:5000',
        ]);

        UnidadOrganico::create($request->only('nombre', 'descripcion'));

        return redirect()->route('admin.unidades_organicos.index')->with('ok', 'Unidad de orgánico creada');
    }

    /**
     * Display the specified resource.
     */
    public function show(UnidadOrganico $unidadesOrganico)
    {
        return view('unidades_organicos.show', compact('unidadesOrganico'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UnidadOrganico $unidadesOrganico)
    {
        return view('unidades_organicos.edit', compact('unidadesOrganico'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UnidadOrganico $unidadesOrganico)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:unidades_organicos,nombre,' . $unidadesOrganico->id,
            'descripcion' => 'nullable|string|max:5000',
        ]);

        $unidadesOrganico->update($request->only('nombre', 'descripcion'));

        return redirect()->route('admin.unidades_organicos.index')->with('ok', 'Unidad de orgánico actualizada');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UnidadOrganico $unidadesOrganico)
    {
        // Verificar si hay orgánicos usando esta unidad
        if ($unidadesOrganico->organicos()->count() > 0) {
            return redirect()->route('admin.unidades_organicos.index')
                ->with('error', 'No se puede eliminar esta unidad porque tiene orgánicos asociados.');
        }

        $unidadesOrganico->delete();

        return redirect()->route('admin.unidades_organicos.index')->with('ok', 'Unidad de orgánico eliminada');
    }
}
