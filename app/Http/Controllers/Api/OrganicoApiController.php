<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Organico;
use Illuminate\Http\Request;

class OrganicoApiController extends Controller
{
    // LISTAR TODOS (GET /api/organicos)
    public function index()
    {
        $data = Organico::with('unidadOrganico')->latest()->get();

        return response()->json([
            'status' => 'ok',
            'data'   => $data,
        ]);
    }

    // VER UNO (GET /api/organicos/{id})
    public function show($id)
    {
        $organico = Organico::with('unidadOrganico')->find($id);

        if (!$organico) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Orgánico no encontrado',
            ], 404);
        }

        return response()->json([
            'status' => 'ok',
            'data'   => $organico,
        ]);
    }

    // CREAR (POST /api/organicos)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre'           => 'required|string|max:255',
            'categoria_id'     => 'required|integer',
            'precio'           => 'required|numeric',
            'stock'            => 'required|integer',
            'fecha_cosecha'    => 'required|date',
            'descripcion'      => 'required|string',
            'user_id'          => 'required|integer',
            'unidad_id'        => 'required|integer',
            'tipo_cultivo_id'  => 'required|integer',

            'origen'           => 'nullable|string',
            'latitud_origen'   => 'nullable|string',
            'longitud_origen'  => 'nullable|string',
        ]);

        $organico = Organico::create($validated);

        return response()->json([
            'status'  => 'ok',
            'message' => 'Orgánico creado correctamente',
            'data'    => $organico,
        ], 201);
    }

    // ACTUALIZAR (PUT /api/organicos/{id})
    public function update(Request $request, $id)
    {
        $organico = Organico::find($id);

        if (!$organico) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Orgánico no encontrado',
            ], 404);
        }

        $validated = $request->validate([
            'nombre'           => 'sometimes|required|string|max:255',
            'categoria_id'     => 'sometimes|required|integer',
            'precio'           => 'sometimes|required|numeric',
            'stock'            => 'sometimes|required|integer',
            'fecha_cosecha'    => 'sometimes|required|date',
            'descripcion'      => 'sometimes|required|string',
            'user_id'          => 'sometimes|required|integer',
            'unidad_id'        => 'sometimes|required|integer',
            'tipo_cultivo_id'  => 'sometimes|required|integer',

            'origen'           => 'nullable|string',
            'latitud_origen'   => 'nullable|string',
            'longitud_origen'  => 'nullable|string',
        ]);

        $organico->update($validated);

        return response()->json([
            'status'  => 'ok',
            'message' => 'Orgánico actualizado correctamente',
            'data'    => $organico,
        ]);
    }

    // ELIMINAR (DELETE /api/organicos/{id})
    public function destroy($id)
    {
        $organico = Organico::find($id);

        if (!$organico) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Orgánico no encontrado',
            ], 404);
        }

        $organico->delete();

        return response()->json([
            'status'  => 'ok',
            'message' => 'Orgánico eliminado correctamente',
        ]);
    }
}
