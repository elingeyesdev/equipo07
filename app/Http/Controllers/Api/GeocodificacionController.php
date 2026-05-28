<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GeocodificacionController extends Controller
{
    public function search(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:3|max:120',
        ]);

        $response = Http::withHeaders([
            'User-Agent' => 'ProyectoAgricola/1.0',
            'Accept-Language' => 'es',
        ])->get('https://nominatim.openstreetmap.org/search', [
            'q' => $request->q,
            'format' => 'json',
            'addressdetails' => 1,
            'limit' => 5,
            'countrycodes' => 'bo',
        ]);

        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'Error al buscar ubicaciones',
            ], 500);
        }

        return response()->json([
            'success' => true,
            'data' => collect($response->json())->map(function ($item) {
                $address = $item['address'] ?? [];

                return [
                    'display_name' => $item['display_name'] ?? '',
                    'name' => $item['name'] ?? null,
                    'lat' => $item['lat'] ?? null,
                    'lon' => $item['lon'] ?? null,
                    'city' => $address['city']
                        ?? $address['town']
                        ?? $address['village']
                        ?? null,
                    'municipality' => $address['municipality']
                        ?? $address['city']
                        ?? $address['town']
                        ?? $address['village']
                        ?? null,
                    'county' => $address['county'] ?? null,
                    'state' => $address['state'] ?? null,
                    'address_line' => collect([
                        $address['road'] ?? null,
                        $address['suburb'] ?? null,
                        $address['city'] ?? $address['town'] ?? $address['village'] ?? null,
                        $address['county'] ?? null,
                        $address['state'] ?? null,
                        $address['country'] ?? null,
                    ])->filter()->unique()->implode(', '),
                ];
            })->filter(fn ($item) => $item['lat'] && $item['lon'])->values(),
        ]);
    }

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
