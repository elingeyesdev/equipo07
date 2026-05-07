<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrganicoResource;
use App\Models\Organico;
use App\Models\OrganicoTrazabilidad;
use Illuminate\Http\Request;

class OrganicoApiController extends Controller
{
    // LISTAR TODOS (GET /api/organicos)
    public function index()
    {
        $data = Organico::with(['categoria', 'unidadOrganico', 'tipoCultivo', 'imagenes', 'user'])
            ->latest()
            ->get();

        return response()->json([
            'status' => 'ok',
            'data'   => OrganicoResource::collection($data),
        ]);
    }

    // VER UNO (GET /api/organicos/{id})
    public function show($id)
    {
        $organico = Organico::with(['categoria', 'unidadOrganico', 'tipoCultivo', 'imagenes', 'user'])->find($id);

        if (!$organico) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Orgánico no encontrado',
            ], 404);
        }

        return response()->json([
            'status' => 'ok',
            'data'   => new OrganicoResource($organico),
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
            'fecha_cosecha'    => 'required|date|after_or_equal:fecha_siembra',
            'descripcion'      => 'required|string',
            'user_id'          => 'required|integer',
            'unidad_id'        => 'required|integer',
            'tipo_cultivo_id'  => 'required|integer',
            'origen'           => 'required|string',
            'finca'            => 'required|string',
            'ubicacion'        => 'required|string',
            'fecha_siembra'    => 'required|date|before_or_equal:today',
            'tratamientos_utilizados' => 'required|string',
            'certificaciones'  => 'required|string',
            'observaciones'    => 'nullable|string',
            'latitud_origen'   => 'nullable|string',
            'longitud_origen'  => 'nullable|string',
        ]);

        $organico = Organico::create($validated);
        $organico->load(['categoria', 'unidadOrganico', 'tipoCultivo', 'imagenes', 'user']);

        return response()->json([
            'status'  => 'ok',
            'message' => 'Orgánico creado correctamente',
            'data'    => new OrganicoResource($organico),
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
            'fecha_cosecha'    => 'sometimes|required|date|after_or_equal:fecha_siembra',
            'descripcion'      => 'sometimes|required|string',
            'user_id'          => 'sometimes|required|integer',
            'unidad_id'        => 'sometimes|required|integer',
            'tipo_cultivo_id'  => 'sometimes|required|integer',

            'origen'           => 'sometimes|required|string',
            'finca'            => 'sometimes|required|string',
            'ubicacion'        => 'sometimes|required|string',
            'fecha_siembra'    => 'sometimes|required|date|before_or_equal:today',
            'tratamientos_utilizados' => 'sometimes|required|string',
            'certificaciones'  => 'sometimes|required|string',
            'observaciones'    => 'nullable|string',
            'latitud_origen'   => 'nullable|string',
            'longitud_origen'  => 'nullable|string',
        ]);

        $organico->update($validated);
        $organico->load(['categoria', 'unidadOrganico', 'tipoCultivo', 'imagenes', 'user']);

        return response()->json([
            'status'  => 'ok',
            'message' => 'Orgánico actualizado correctamente',
            'data'    => new OrganicoResource($organico),
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
