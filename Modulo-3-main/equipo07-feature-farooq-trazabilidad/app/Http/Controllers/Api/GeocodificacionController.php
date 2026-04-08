<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GeocodificacionController extends Controller
{
    public function reverse(Request $request)
    {
        $request->validate([
            'latitud'  => 'required|numeric',
            'longitud' => 'required|numeric',
        ]);

        $lat = $request->latitud;
        $lng = $request->longitud;

        $response = Http::withHeaders([
            'User-Agent' => 'ProyectoAgricola/1.0'
        ])->get('https://nominatim.openstreetmap.org/reverse', [
            'lat'            => $lat,
            'lon'            => $lng,
            'format'         => 'json',
            'addressdetails' => 1,
        ]);

        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener datos'
            ], 500);
        }

        $json = $response->json();
        $address = $json['address'] ?? [];

        return response()->json([
            'success' => true,
            'data' => [
                'departamento' => $address['state'] ?? null,
                'provincia'    => $address['county'] ?? null,
                'municipio'    => $address['municipality']
                                ?? $address['town']
                                ?? $address['village']
                                ?? null,
                'ciudad'       => $address['city']
                                ?? $address['town']
                                ?? $address['village']
                                ?? null,
            ],
        ]);
    }
}
