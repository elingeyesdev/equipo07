<?php

namespace App\Http\Controllers;

use App\Models\Raza;
use App\Models\TipoAnimal;
use Illuminate\Http\Request;

class RazaController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->q;
        // Traemos razas y tipos de animales activos para el select del Modal
        $razas = Raza::with('tipoAnimal')->withTrashed()
            ->when($q, function($query, $q) {
                return $query->where('nombre', 'like', "%$q%");
            })
            ->orderBy('id', 'desc')
            ->paginate(10);
            
        $tipos = TipoAnimal::all(); // Solo los activos para asignar nuevas razas
        
        return view('razas.index', compact('razas', 'q', 'tipos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo_animal_id' => 'required|exists:tipo_animals,id',
            'descripcion' => 'nullable|string|max:1000'
        ]);
        Raza::create($request->all());
        return back()->with('success', 'Raza creada correctamente.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo_animal_id' => 'required|exists:tipo_animals,id',
            'descripcion' => 'nullable|string|max:1000'
        ]);
        $raza = Raza::withTrashed()->findOrFail($id);
        $raza->update($request->all());
        return back()->with('success', 'Raza actualizada correctamente.');
    }

    public function destroy($id)
    {
        $raza = Raza::withTrashed()->findOrFail($id);
        if ($raza->trashed()) {
            $raza->restore();
            return back()->with('success', 'Raza restaurada (Visible).');
        } else {
            $raza->delete();
            return back()->with('success', 'Raza archivada (Oculta para usuarios).');
        }
    }
}