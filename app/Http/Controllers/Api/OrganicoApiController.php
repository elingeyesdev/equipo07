<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Organico;
use App\Models\OrganicoTrazabilidad;
use Illuminate\Http\Request;

class OrganicoApiController extends Controller
{
    // LISTAR TODOS (GET /api/organicos)
    public function index()
    {
        $data = Organico::with(['unidadOrganico', 'trazabilidad'])->latest()->get();

        return response()->json([
            'status' => 'ok',
            'data'   => $data,
        ]);
    }

    // VER UNO (GET /api/organicos/{id})
    public function show($id)
    {
        $organico = Organico::with(['unidadOrganico', 'trazabilidad'])->find($id);

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

        $organicoPayload = $validated;
        unset(
            $organicoPayload['finca'],
            $organicoPayload['ubicacion'],
            $organicoPayload['fecha_siembra'],
            $organicoPayload['tratamientos_utilizados'],
            $organicoPayload['certificaciones'],
            $organicoPayload['observaciones']
        );
        $organico = Organico::create($organicoPayload);
        $organico->trazabilidad()->create([
            'origen' => $validated['origen'],
            'finca' => $validated['finca'],
            'ubicacion' => $validated['ubicacion'],
            'fecha_siembra' => $validated['fecha_siembra'],
            'fecha_cosecha' => $validated['fecha_cosecha'],
            'tratamientos_utilizados' => $validated['tratamientos_utilizados'],
            'certificaciones' => $validated['certificaciones'],
            'observaciones' => $validated['observaciones'] ?? null,
        ]);

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

        $organicoPayload = $validated;
        unset(
            $organicoPayload['finca'],
            $organicoPayload['ubicacion'],
            $organicoPayload['fecha_siembra'],
            $organicoPayload['tratamientos_utilizados'],
            $organicoPayload['certificaciones'],
            $organicoPayload['observaciones']
        );
        $organico->update($organicoPayload);

        if (
            array_key_exists('origen', $validated) ||
            array_key_exists('finca', $validated) ||
            array_key_exists('ubicacion', $validated) ||
            array_key_exists('fecha_siembra', $validated) ||
            array_key_exists('fecha_cosecha', $validated) ||
            array_key_exists('tratamientos_utilizados', $validated) ||
            array_key_exists('certificaciones', $validated) ||
            array_key_exists('observaciones', $validated)
        ) {
            $existing = $organico->trazabilidad ?? new OrganicoTrazabilidad(['organico_id' => $organico->id]);
            $existing->fill([
                'origen' => $validated['origen'] ?? $existing->origen,
                'finca' => $validated['finca'] ?? $existing->finca,
                'ubicacion' => $validated['ubicacion'] ?? $existing->ubicacion,
                'fecha_siembra' => $validated['fecha_siembra'] ?? $existing->fecha_siembra,
                'fecha_cosecha' => $validated['fecha_cosecha'] ?? $existing->fecha_cosecha,
                'tratamientos_utilizados' => $validated['tratamientos_utilizados'] ?? $existing->tratamientos_utilizados,
                'certificaciones' => $validated['certificaciones'] ?? $existing->certificaciones,
                'observaciones' => $validated['observaciones'] ?? $existing->observaciones,
            ]);
            $existing->organico_id = $organico->id;
            $existing->save();
        }

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
