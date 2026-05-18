<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Organico;
use App\Models\UbicacionGeograficaOrganico;
use App\Models\UbicacionOrganico;
use Illuminate\Http\Request;

class OrganicoApiController extends Controller
{
    // LISTAR TODOS (GET /api/organicos)
    public function index()
    {
        $data = Organico::with($this->relacionesOrganico())->latest()->get();

        return response()->json([
            'status' => 'ok',
            'data'   => $data,
        ]);
    }

    // VER UNO (GET /api/organicos/{id})
    public function show($id)
    {
        $organico = Organico::with($this->relacionesOrganico())->find($id);

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
            'departamento_origen' => 'nullable|string',
            'municipio_origen' => 'nullable|string',
            'provincia_origen' => 'nullable|string',
            'ciudad_origen'    => 'nullable|string',
        ]);

        $datosComerciales = $this->extraerDatosComerciales($validated);
        $this->sincronizarUbicacionNormalizada($validated);

        $organico = Organico::create($validated);
        $this->sincronizarDatosComerciales($organico, $datosComerciales);

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
            'departamento_origen' => 'nullable|string',
            'municipio_origen' => 'nullable|string',
            'provincia_origen' => 'nullable|string',
            'ciudad_origen'    => 'nullable|string',
        ]);

        $datosComerciales = $this->extraerDatosComerciales($validated);
        $this->sincronizarUbicacionNormalizada($validated, $organico);

        $organico->update($validated);
        $this->sincronizarDatosComerciales($organico, $datosComerciales);

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

    private function relacionesOrganico(): array
    {
        return [
            'unidad',
            'unidadOrganico',
            'datoComercial.unidad',
            'ubicacionOrganico.ubicacionGeografica',
        ];
    }

    private function extraerDatosComerciales(array &$data): array
    {
        $campos = ['unidad_id', 'precio', 'stock'];
        $datos = [];

        foreach ($campos as $campo) {
            if (array_key_exists($campo, $data)) {
                $datos[$campo] = $data[$campo];
                unset($data[$campo]);
            }
        }

        return $datos;
    }

    private function sincronizarDatosComerciales(Organico $organico, array $data): void
    {
        if (!$data) {
            return;
        }

        $organico->datoComercial()->updateOrCreate(
            ['organico_id' => $organico->id],
            [
                'unidad_id' => $data['unidad_id'] ?? $organico->unidad_id,
                'precio' => $data['precio'] ?? $organico->precio,
                'stock' => $data['stock'] ?? $organico->stock ?? 0,
            ]
        );
    }

    private function sincronizarUbicacionNormalizada(array &$data, ?Organico $organico = null): void
    {
        $campos = [
            'origen',
            'latitud_origen',
            'longitud_origen',
            'departamento_origen',
            'municipio_origen',
            'provincia_origen',
            'ciudad_origen',
        ];

        $recibioCamposUbicacion = collect($campos)->contains(fn ($campo) => array_key_exists($campo, $data));

        if (!$recibioCamposUbicacion) {
            return;
        }

        foreach ($campos as $campo) {
            if (array_key_exists($campo, $data) && $data[$campo] === '') {
                $data[$campo] = null;
            }
        }

        $tieneDatosUbicacion = collect($campos)->contains(fn ($campo) => !empty($data[$campo]));

        if (!$tieneDatosUbicacion) {
            $data['ubicacion_organico_id'] = null;
            $this->quitarCamposUbicacionAntiguos($data);
            return;
        }

        $ubicacionGeografica = UbicacionGeograficaOrganico::firstOrCreate([
            'departamento' => $data['departamento_origen'] ?? null,
            'municipio' => $data['municipio_origen'] ?? null,
            'provincia' => $data['provincia_origen'] ?? null,
            'ciudad' => $data['ciudad_origen'] ?? null,
        ]);

        $ubicacionData = [
            'ubicacion' => $data['origen'] ?? null,
            'latitud' => $data['latitud_origen'] ?? null,
            'longitud' => $data['longitud_origen'] ?? null,
            'ubicacion_geografica_organico_id' => $ubicacionGeografica->id,
        ];

        if ($organico && $organico->ubicacion_organico_id) {
            $ubicacion = UbicacionOrganico::find($organico->ubicacion_organico_id);

            if ($ubicacion) {
                $ubicacion->update($ubicacionData);
                $data['ubicacion_organico_id'] = $ubicacion->id;
                $this->quitarCamposUbicacionAntiguos($data);
                return;
            }
        }

        $data['ubicacion_organico_id'] = UbicacionOrganico::create($ubicacionData)->id;
        $this->quitarCamposUbicacionAntiguos($data);
    }

    private function quitarCamposUbicacionAntiguos(array &$data): void
    {
        foreach ([
            'origen',
            'latitud_origen',
            'longitud_origen',
            'departamento_origen',
            'municipio_origen',
            'provincia_origen',
            'ciudad_origen',
        ] as $campo) {
            unset($data[$campo]);
        }
    }
}
