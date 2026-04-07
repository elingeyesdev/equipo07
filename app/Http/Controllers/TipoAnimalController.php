<?php

namespace App\Http\Controllers;

use App\Models\TipoAnimal;
use Illuminate\Http\Request;

class TipoAnimalController extends Controller
{
    public function index()
    {
        $q = request('q');
        $items = TipoAnimal::when(
            $q,
            fn($qb) =>
            $qb->where('nombre', 'ilike', "%$q%")
        )
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('tipo_animals.index', compact('items', 'q'));
    }

    public function create()
    {
        return view('tipo_animals.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:tipo_animals,nombre',
            'descripcion' => 'nullable|string|max:5000',
        ]);

        TipoAnimal::create($request->only('nombre', 'descripcion'));

        return redirect()->route('admin.tipo_animals.index')->with('ok', 'Tipo de animal creado');
    }

    public function edit(TipoAnimal $tipoAnimal)
    {
        return view('tipo_animals.edit', compact('tipoAnimal'));
    }

    public function update(Request $request, TipoAnimal $tipoAnimal)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:tipo_animals,nombre,' . $tipoAnimal->id,
            'descripcion' => 'nullable|string|max:5000',
        ]);

        $tipoAnimal->update($request->only('nombre', 'descripcion'));

        return redirect()->route('admin.tipo_animals.index')->with('ok', 'Tipo de animal actualizado');
    }

    public function destroy(TipoAnimal $tipoAnimal)
    {
        $tipoAnimal->delete();

        return redirect()->route('admin.tipo_animals.index')->with('ok', 'Tipo de animal eliminado');
    }
}
