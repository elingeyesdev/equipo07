<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Maquinaria;
use Illuminate\Http\Request;

class MaquinariaApiController extends Controller
{
    // LISTAR TODAS (GET /api/maquinarias)
    public function index()
    {
        $data = Maquinaria::latest()->get();

        return response()->json([
            'status' => 'ok',
            'data'   => $data,
        ]);
    }

    // VER UNA (GET /api/maquinarias/{id})
    public function show($id)
    {
        $maquinaria = Maquinaria::find($id);

        if (!$maquinaria) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Maquinaria no encontrada',
            ], 404);
        }

        return response()->json([
            'status' => 'ok',
            'data'   => $maquinaria,
        ]);
    }

    // CREAR (POST /api/maquinarias)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre'              => 'required|string|max:255',
            'modelo'              => 'required|string|max:255',
            'precio_dia'          => 'required|numeric',
            'estado'              => 'required|string|max:50',
            'descripcion'         => 'required|string',
            'categoria_id'        => 'required|integer',
            'user_id'             => 'required|integer',
            'tipo_maquinaria_id'  => 'required|integer',
            'marca_maquinaria_id' => 'required|integer',
            'telefono'            => 'required|string|max:50',
            'estado_maquinaria_id'=> 'required|integer',

            'ubicacion'   => 'nullable|string',
            'latitud'     => 'nullable|string',
            'longitud'    => 'nullable|string',
            'departamento'=> 'nullable|string',
            'municipio'   => 'nullable|string',
            'provincia'   => 'nullable|string',
            'ciudad'      => 'nullable|string',
        ]);

        $maquinaria = Maquinaria::create($validated);

        return response()->json([
            'status'  => 'ok',
            'message' => 'Maquinaria creada correctamente',
            'data'    => $maquinaria,
        ], 201);
    }

    // ACTUALIZAR (PUT /api/maquinarias/{id})
    public function update(Request $request, $id)
    {
        $maquinaria = Maquinaria::find($id);

        if (!$maquinaria) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Maquinaria no encontrada',
            ], 404);
        }

        $validated = $request->validate([
            'nombre'              => 'sometimes|required|string|max:255',
            'modelo'              => 'sometimes|required|string|max:255',
            'precio_dia'          => 'sometimes|required|numeric',
            'estado'              => 'sometimes|required|string|max:50',
            'descripcion'         => 'sometimes|required|string',
            'categoria_id'        => 'sometimes|required|integer',
            'user_id'             => 'sometimes|required|integer',
            'tipo_maquinaria_id'  => 'sometimes|required|integer',
            'marca_maquinaria_id' => 'sometimes|required|integer',
            'telefono'            => 'sometimes|required|string|max:50',
            'estado_maquinaria_id'=> 'sometimes|required|integer',

            'ubicacion'   => 'nullable|string',
            'latitud'     => 'nullable|string',
            'longitud'    => 'nullable|string',
            'departamento'=> 'nullable|string',
            'municipio'   => 'nullable|string',
            'provincia'   => 'nullable|string',
            'ciudad'      => 'nullable|string',
        ]);

        $maquinaria->update($validated);

        return response()->json([
            'status'  => 'ok',
            'message' => 'Maquinaria actualizada correctamente',
            'data'    => $maquinaria,
        ]);
    }

    // ELIMINAR (DELETE /api/maquinarias/{id})
    public function destroy($id)
    {
        $maquinaria = Maquinaria::find($id);

        if (!$maquinaria) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Maquinaria no encontrada',
            ], 404);
        }

        $maquinaria->delete();

        return response()->json([
            'status'  => 'ok',
            'message' => 'Maquinaria eliminada correctamente',
        ]);
    }
}
