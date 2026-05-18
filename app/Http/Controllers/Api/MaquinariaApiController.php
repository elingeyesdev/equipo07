<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Maquinaria;
use App\Models\UbicacionGeograficaMaquinaria;
use App\Models\UbicacionMaquinaria;
use Illuminate\Http\Request;

class MaquinariaApiController extends Controller
{
    // LISTAR TODAS (GET /api/maquinarias)
    public function index()
    {
        $data = Maquinaria::with([
            'tipoMaquinaria',
            'marcaMaquinaria',
            'estadoMaquinaria',
            'ubicacionMaquinaria.ubicacionGeografica',
        ])->latest()->get();

        return response()->json([
            'status' => 'ok',
            'data'   => $data,
        ]);
    }

    // VER UNA (GET /api/maquinarias/{id})
    public function show($id)
    {
        $maquinaria = Maquinaria::with([
            'tipoMaquinaria',
            'marcaMaquinaria',
            'estadoMaquinaria',
            'ubicacionMaquinaria.ubicacionGeografica',
        ])->find($id);

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
            'descripcion'         => 'required|string',
            'categoria_id'        => 'required|integer',
            'user_id'             => 'required|integer',
            'tipo_maquinaria_id'  => 'required|integer',
            'marca_maquinaria_id' => 'required|integer',
            'telefono'            => 'required|string|max:50',
            'estado_maquinaria_id'=> 'required|integer',

            'ubicacion'   => 'nullable|string',
            'latitud'     => 'nullable|numeric|between:-90,90',
            'longitud'    => 'nullable|numeric|between:-180,180',
            'departamento'=> 'nullable|string',
            'municipio'   => 'nullable|string',
            'provincia'   => 'nullable|string',
            'ciudad'      => 'nullable|string',
        ]);

        $this->sincronizarUbicacionNormalizada($validated);

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
            'descripcion'         => 'sometimes|required|string',
            'categoria_id'        => 'sometimes|required|integer',
            'user_id'             => 'sometimes|required|integer',
            'tipo_maquinaria_id'  => 'sometimes|required|integer',
            'marca_maquinaria_id' => 'sometimes|required|integer',
            'telefono'            => 'sometimes|required|string|max:50',
            'estado_maquinaria_id'=> 'sometimes|required|integer',

            'ubicacion'   => 'nullable|string',
            'latitud'     => 'nullable|numeric|between:-90,90',
            'longitud'    => 'nullable|numeric|between:-180,180',
            'departamento'=> 'nullable|string',
            'municipio'   => 'nullable|string',
            'provincia'   => 'nullable|string',
            'ciudad'      => 'nullable|string',
        ]);

        $this->sincronizarUbicacionNormalizada($validated, $maquinaria);

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

    private function sincronizarUbicacionNormalizada(array &$data, ?Maquinaria $maquinaria = null): void
    {
        $campos = ['ubicacion', 'latitud', 'longitud', 'departamento', 'municipio', 'provincia', 'ciudad'];

        foreach ($campos as $campo) {
            if (array_key_exists($campo, $data) && $data[$campo] === '') {
                $data[$campo] = null;
            }
        }

        $tieneDatosUbicacion = collect($campos)->contains(fn($campo) => !empty($data[$campo]));

        if (!$tieneDatosUbicacion) {
            $this->quitarCamposUbicacionAntiguos($data);
            return;
        }

        $ubicacionGeografica = UbicacionGeograficaMaquinaria::firstOrCreate([
            'departamento' => $data['departamento'] ?? null,
            'municipio' => $data['municipio'] ?? null,
            'provincia' => $data['provincia'] ?? null,
            'ciudad' => $data['ciudad'] ?? null,
        ]);

        $ubicacionData = [
            'ubicacion' => $data['ubicacion'] ?? null,
            'latitud' => $data['latitud'] ?? null,
            'longitud' => $data['longitud'] ?? null,
            'ubicacion_geografica_maquinaria_id' => $ubicacionGeografica->id,
        ];

        if ($maquinaria && $maquinaria->ubicacion_maquinaria_id) {
            $ubicacion = UbicacionMaquinaria::find($maquinaria->ubicacion_maquinaria_id);

            if ($ubicacion) {
                $ubicacion->update($ubicacionData);
                $data['ubicacion_maquinaria_id'] = $ubicacion->id;
                $this->quitarCamposUbicacionAntiguos($data);
                return;
            }
        }

        $data['ubicacion_maquinaria_id'] = UbicacionMaquinaria::create($ubicacionData)->id;
        $this->quitarCamposUbicacionAntiguos($data);
    }

    private function quitarCamposUbicacionAntiguos(array &$data): void
    {
        foreach (['ubicacion', 'latitud', 'longitud', 'departamento', 'municipio', 'provincia', 'ciudad'] as $campo) {
            unset($data[$campo]);
        }
    }
}
