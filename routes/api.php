<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\GeocodificacionController;
use App\Http\Controllers\Api\OrganicoApiController;
use App\Http\Controllers\Api\MaquinariaApiController;
use App\Http\Controllers\Api\GanadoApiController;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\CatalogosApiController;

Route::get('/health', function () {
    return response()->json([
        'ok' => true,
        'service' => 'api',
        'time' => now()->toDateTimeString(),
    ]);
});

// GEOCODIFICACIÓN
Route::get('/geocodificacion', [GeocodificacionController::class, 'reverse']);

// CATÁLOGOS
// Orgánicos
Route::get('/categorias', [CatalogosApiController::class, 'categorias']);
Route::get('/tipo-cultivos', [CatalogosApiController::class, 'tipoCultivos']);
Route::get('/unidades-organicos', [CatalogosApiController::class, 'unidadesOrganicos']);

// Maquinaria
Route::get('/tipos-maquinaria', [CatalogosApiController::class, 'tiposMaquinaria']);
Route::get('/marcas-maquinaria', [CatalogosApiController::class, 'marcasMaquinaria']);
Route::get('/estados-maquinaria', [CatalogosApiController::class, 'estadosMaquinaria']);

// ORGÁNICOS (CRUD API)
Route::get('/organicos', [OrganicoApiController::class, 'index']);
Route::get('/organicos/{id}', [OrganicoApiController::class, 'show']);
Route::post('/organicos', [OrganicoApiController::class, 'store']);
Route::put('/organicos/{id}', [OrganicoApiController::class, 'update']);
Route::delete('/organicos/{id}', [OrganicoApiController::class, 'destroy']);

// MAQUINARIAS
Route::get('/maquinarias', [MaquinariaApiController::class, 'index']);
Route::get('/maquinarias/{id}', [MaquinariaApiController::class, 'show']);
Route::post('/maquinarias', [MaquinariaApiController::class, 'store']);
Route::put('/maquinarias/{id}', [MaquinariaApiController::class, 'update']);
Route::delete('/maquinarias/{id}', [MaquinariaApiController::class, 'destroy']);

// GANADOS
Route::get('/ganados', [GanadoApiController::class, 'index']);
Route::get('/ganados/{id}', [GanadoApiController::class, 'show']);
Route::post('/ganados', [GanadoApiController::class, 'store']);
Route::put('/ganados/{id}', [GanadoApiController::class, 'update']);
Route::delete('/ganados/{id}', [GanadoApiController::class, 'destroy']);

// AUTH
Route::post('/login', [AuthApiController::class, 'login']);
