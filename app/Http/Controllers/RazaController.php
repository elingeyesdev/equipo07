<?php

namespace App\Http\Controllers;

use App\Models\Raza;
use App\Models\TipoAnimal;
use Illuminate\Http\Request;

class RazaController extends Controller
{
    public function index()
    {
        $razas = Raza::with('tipoAnimal')->orderBy('id', 'desc')->paginate(10);
        return view('razas.index', compact('razas'));
    }

    public function create()
    {
        $tipos = TipoAnimal::orderBy('nombre')->get();
        return view('razas.create', compact('tipos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:razas,nombre',
            'tipo_animal_id' => 'required|exists:tipo_animals,id',
            'descripcion' => 'nullable|string',
        ]);

        Raza::create($request->all());

        return redirect()->route('admin.razas.index')
            ->with('success', 'Raza registrada correctamente.');
    }

    public function edit(Raza $raza)
    {
        $tipos = TipoAnimal::orderBy('nombre')->get();
        return view('razas.edit', compact('raza', 'tipos'));
    }

    public function update(Request $request, Raza $raza)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:razas,nombre,' . $raza->id,
            'tipo_animal_id' => 'required|exists:tipo_animals,id',
            'descripcion' => 'nullable|string',
        ]);

        $raza->update($request->all());

        return redirect()->route('admin.razas.index')
            ->with('success', 'Raza actualizada correctamente.');
    }

    public function destroy(Raza $raza)
    {
        $raza->delete();

        return redirect()->route('admin.razas.index')
            ->with('success', 'Raza eliminada correctamente.');
    }
}
