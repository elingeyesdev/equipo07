<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\TipoCultivo;
use App\Models\UnidadOrganico;
use App\Models\TipoMaquinaria;
use App\Models\MarcaMaquinaria;
use App\Models\EstadoMaquinaria;

class CatalogosApiController extends Controller
{
    public function categorias()
    {
        return response()->json([
            'status' => 'ok',
            'data'   => Categoria::orderBy('nombre')->get(),
        ]);
    }

    public function tipoCultivos()
    {
        return response()->json([
            'status' => 'ok',
            'data'   => TipoCultivo::orderBy('nombre')->get(),
        ]);
    }

    public function unidadesOrganicos()
    {
        return response()->json([
            'status' => 'ok',
            'data'   => UnidadOrganico::orderBy('nombre')->get(),
        ]);
    }

    public function tiposMaquinaria()
    {
        return response()->json([
            'status' => 'ok',
            'data'   => TipoMaquinaria::orderBy('nombre')->get(),
        ]);
    }

    public function marcasMaquinaria()
    {
        return response()->json([
            'status' => 'ok',
            'data'   => MarcaMaquinaria::orderBy('nombre')->get(),
        ]);
    }

    public function estadosMaquinaria()
    {
        return response()->json([
            'status' => 'ok',
            'data'   => EstadoMaquinaria::orderBy('nombre')->get(),
        ]);
    }
}
