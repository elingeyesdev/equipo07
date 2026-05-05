<?php

namespace App\Http\Controllers;

use App\Models\TipoAnimal;
use Illuminate\Http\Request;

class TipoAnimalController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->q;
        // withTrashed() trae activos y archivados
        $items = TipoAnimal::withTrashed()
            ->when($q, function($query, $q) {
                return $query->where('nombre', 'like', "%$q%")
                             ->orWhere('descripcion', 'like', "%$q%");
            })
            ->orderBy('id', 'desc')
            ->paginate(10);
            
        return view('admin.tipo_animals.index', compact('items', 'q'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:1000'
        ]);
        TipoAnimal::create($request->all());
        return back()->with('ok', 'Especie creada correctamente.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:1000'
        ]);
        $tipo = TipoAnimal::withTrashed()->findOrFail($id);
        $tipo->update($request->all());
        return back()->with('ok', 'Especie actualizada correctamente.');
    }

    public function destroy($id)
    {
        $tipo = TipoAnimal::withTrashed()->findOrFail($id);
        if ($tipo->trashed()) {
            $tipo->restore(); // Si estaba archivado, lo restaura
            return back()->with('ok', 'Especie restaurada (Visible nuevamente).');
        } else {
            $tipo->delete(); // Si estaba activo, lo archiva (SoftDelete)
            return back()->with('ok', 'Especie archivada correctamente.');
        }
    }
}