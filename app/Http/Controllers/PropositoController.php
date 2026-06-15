<?php

namespace App\Http\Controllers;

use App\Models\Proposito;
use Illuminate\Http\Request;

class PropositoController extends Controller
{
    public function index()
    {
        $items = Proposito::orderBy('id', 'desc')->paginate(10);
        return view('propositos.index', compact('items'));
    }

    public function create()
    {
        return view('propositos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
        ]);

        Proposito::create($request->all());

        return redirect()->route('admin.propositos.index')
            ->with('success', 'Propósito creado correctamente.');
    }

    public function edit(Proposito $proposito)
    {
        return view('propositos.edit', compact('proposito'));
    }

    public function update(Request $request, Proposito $proposito)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
        ]);

        $proposito->update($request->all());

        return redirect()->route('admin.propositos.index')
            ->with('success', 'Propósito actualizado correctamente.');
    }

    public function destroy(Proposito $proposito)
    {
        $proposito->delete();

        return redirect()->route('admin.propositos.index')
            ->with('success', 'Propósito eliminado correctamente.');
    }
}
