<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ganado;
use Illuminate\Http\Request;

class GanadoApiController extends Controller
{
    // LISTADO COMPLETO
    public function index()
    {
        $ganados = Ganado::with([
            'raza',
            'tipoPeso',
            'tipoAnimal'
        ])
        ->orderBy('id', 'desc')
        ->get();

        return response()->json([
            'status' => 'ok',
            'data' => $ganados
        ]);
    }

    // DETALLE POR ID
    public function show($id)
    {
        $ganado = Ganado::with([
            'raza',
            'tipoPeso',
            'tipoAnimal',
            'datoSanitario',
        ])->find($id);

        if (!$ganado) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ganado no encontrado'
            ], 404);
        }

        return response()->json([
            'status' => 'ok',
            'data' => $ganado
        ]);
    }

    // CREAR GANADO
    public function store(Request $request)
    {
        $ganado = Ganado::create($request->all());

        return response()->json([
            'status' => 'ok',
            'message' => 'Ganado creado correctamente',
            'data' => $ganado
        ]);
    }

    // ACTUALIZAR GANADO
    public function update(Request $request, $id)
    {
        $ganado = Ganado::find($id);

        if (!$ganado) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ganado no encontrado'
            ], 404);
        }

        $ganado->update($request->all());

        return response()->json([
            'status' => 'ok',
            'message' => 'Ganado actualizado correctamente',
            'data' => $ganado
        ]);
    }

    // ELIMINAR GANADO
    public function destroy($id)
    {
        $ganado = Ganado::find($id);

        if (!$ganado) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ganado no encontrado'
            ], 404);
        }

        $ganado->delete();

        return response()->json([
            'status' => 'ok',
            'message' => 'Ganado eliminado correctamente'
        ]);
    }
}
