<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ganado;
use App\Models\UbicacionGanado;
use App\Models\UbicacionGeograficaGanado;
use Illuminate\Http\Request;

class GanadoApiController extends Controller
{
    // LISTADO COMPLETO
    public function index()
    {
        $ganados = Ganado::with($this->relacionesGanado())
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
        $ganado = Ganado::with($this->relacionesGanado())->find($id);

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
        $data = $request->all();
        $this->sincronizarUbicacionNormalizada($data);
        $datosNormalizados = $this->extraerDatosNormalizados($data, true);

        $ganado = Ganado::create($data);
        $this->sincronizarDatosNormalizados($ganado, $datosNormalizados);

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

        $data = $request->all();
        $this->sincronizarUbicacionNormalizada($data, $ganado);
        $datosNormalizados = $this->extraerDatosNormalizados($data);

        $ganado->update($data);
        $this->sincronizarDatosNormalizados($ganado, $datosNormalizados);

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

    private function sincronizarUbicacionNormalizada(array &$data, ?Ganado $ganado = null): void
    {
        $campos = ['ubicacion', 'latitud', 'longitud', 'departamento', 'municipio', 'provincia', 'ciudad'];

        $recibioCamposUbicacion = collect($campos)->contains(fn($campo) => array_key_exists($campo, $data));

        if (!$recibioCamposUbicacion) {
            return;
        }

        foreach ($campos as $campo) {
            if (array_key_exists($campo, $data) && $data[$campo] === '') {
                $data[$campo] = null;
            }
        }

        $tieneDatosUbicacion = collect($campos)->contains(fn($campo) => !empty($data[$campo]));

        if (!$tieneDatosUbicacion) {
            $data['ubicacion_ganado_id'] = null;
            $this->quitarCamposUbicacionAntiguos($data);
            return;
        }

        $ubicacionGeografica = UbicacionGeograficaGanado::firstOrCreate([
            'departamento' => $data['departamento'] ?? null,
            'municipio' => $data['municipio'] ?? null,
            'provincia' => $data['provincia'] ?? null,
            'ciudad' => $data['ciudad'] ?? null,
        ]);

        $ubicacionData = [
            'ubicacion' => $data['ubicacion'] ?? null,
            'latitud' => $data['latitud'] ?? null,
            'longitud' => $data['longitud'] ?? null,
            'ubicacion_geografica_ganado_id' => $ubicacionGeografica->id,
        ];

        if ($ganado && $ganado->ubicacion_ganado_id) {
            $ubicacion = UbicacionGanado::find($ganado->ubicacion_ganado_id);

            if ($ubicacion) {
                $ubicacion->update($ubicacionData);
                $data['ubicacion_ganado_id'] = $ubicacion->id;
                $this->quitarCamposUbicacionAntiguos($data);
                return;
            }
        }

        $data['ubicacion_ganado_id'] = UbicacionGanado::create($ubicacionData)->id;
        $this->quitarCamposUbicacionAntiguos($data);
    }

    private function quitarCamposUbicacionAntiguos(array &$data): void
    {
        foreach (['ubicacion', 'latitud', 'longitud', 'departamento', 'municipio', 'provincia', 'ciudad'] as $campo) {
            unset($data[$campo]);
        }
    }

    private function relacionesGanado(): array
    {
        return [
            'raza',
            'tipoPeso',
            'tipoAnimal',
            'datoSanitario',
            'datosSanitarios',
            'ubicacionGanado.ubicacionGeografica',
            'datoProductivo.tipoPeso',
            'datoComercial',
            'caracteristica',
            'genealogia.madre',
            'genealogia.padre',
        ];
    }

    private function extraerDatosNormalizados(array &$data, bool $esCreacion = false): array
    {
        $campos = [
            'edad',
            'tipo_peso_id',
            'peso_actual',
            'sexo',
            'cantidad_leche_dia',
            'precio',
            'stock',
            'descripcion',
            'fecha_publicacion',
            'madre_id',
            'padre_id',
        ];

        $datos = [];

        foreach ($campos as $campo) {
            if (array_key_exists($campo, $data)) {
                $datos[$campo] = $data[$campo];
                unset($data[$campo]);
            }
        }

        if ($esCreacion && !array_key_exists('fecha_publicacion', $datos)) {
            $datos['fecha_publicacion'] = now();
        }

        return $datos;
    }

    private function sincronizarDatosNormalizados(Ganado $ganado, array $data): void
    {
        if (array_intersect_key($data, array_flip(['edad', 'sexo', 'descripcion']))) {
            $ganado->caracteristica()->updateOrCreate(
                ['ganado_id' => $ganado->id],
                [
                    'edad' => $data['edad'] ?? $ganado->edad,
                    'sexo' => $data['sexo'] ?? $ganado->sexo,
                    'descripcion' => $data['descripcion'] ?? $ganado->descripcion,
                ]
            );
        }

        if (array_intersect_key($data, array_flip(['tipo_peso_id', 'peso_actual', 'cantidad_leche_dia']))) {
            $ganado->datoProductivo()->updateOrCreate(
                ['ganado_id' => $ganado->id],
                [
                    'tipo_peso_id' => $data['tipo_peso_id'] ?? $ganado->tipo_peso_id,
                    'peso_actual' => $data['peso_actual'] ?? $ganado->peso_actual,
                    'cantidad_leche_dia' => $data['cantidad_leche_dia'] ?? $ganado->cantidad_leche_dia,
                ]
            );
        }

        if (array_intersect_key($data, array_flip(['precio', 'stock', 'fecha_publicacion']))) {
            $ganado->datoComercial()->updateOrCreate(
                ['ganado_id' => $ganado->id],
                [
                    'precio' => $data['precio'] ?? $ganado->precio,
                    'stock' => $data['stock'] ?? $ganado->stock ?? 0,
                    'fecha_publicacion' => $data['fecha_publicacion'] ?? $ganado->fecha_publicacion ?? now(),
                ]
            );
        }

        if (array_intersect_key($data, array_flip(['madre_id', 'padre_id']))) {
            $ganado->genealogia()->updateOrCreate(
                ['ganado_id' => $ganado->id],
                [
                    'madre_id' => $data['madre_id'] ?? $ganado->madre_id,
                    'padre_id' => $data['padre_id'] ?? $ganado->padre_id,
                ]
            );
        }
    }
}
