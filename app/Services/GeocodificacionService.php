<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeocodificacionService
{
    /**
     * Obtiene información geográfica (departamento, municipio, provincia, ciudad) desde coordenadas
     * usando la API de Nominatim (OpenStreetMap)
     * 
     * @param float $latitud
     * @param float $longitud
     * @return array|null
     */
    public function obtenerInformacionGeografica(float $latitud, float $longitud): ?array
    {
        try {
            $url = 'https://nominatim.openstreetmap.org/reverse';

            $response = Http::withoutVerifying()
                ->timeout(10)
                ->withHeaders([
                    'User-Agent'       => 'ProyectoAgricola/1.0',
                    'Accept-Language'  => 'es',
                ])->get($url, [
                    'format'         => 'json',
                    'lat'            => $latitud,
                    'lon'            => $longitud,
                    'addressdetails' => 1,
                    'zoom'           => 10,
                ]);

            if (! $response->successful()) {
                Log::warning('Geocodificación Nominatim no exitosa', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);
                return null;
            }

            $data = $response->json();

            if (! isset($data['address'])) {
                Log::warning('Geocodificación Nominatim sin address', [
                    'data' => $data,
                ]);
                return null;
            }

            $address = $data['address'];

            $departamento = $this->extraerDepartamento($address);
            $municipio    = $this->extraerMunicipio($address);
            $provincia    = $this->extraerProvincia($address);
            $ciudad       = $this->extraerCiudad($address);

            return [
                'departamento'       => $departamento,
                'municipio'          => $municipio,
                'provincia'          => $provincia,
                'ciudad'             => $ciudad,
                'direccion_completa' => $data['display_name'] ?? null,
            ];
        } catch (\Exception $e) {
            Log::error('Error en geocodificación inversa: ' . $e->getMessage(), [
                'latitud'  => $latitud,
                'longitud' => $longitud,
            ]);
            return null;
        }
    }

    /**
     * Extrae el departamento de la dirección
     */
    private function extraerDepartamento(array $address): ?string
    {
        return $address['state'] ?? $address['region'] ?? null;
    }

    /**
     * Extrae el municipio de la dirección
     */
    private function extraerMunicipio(array $address): ?string
    {
        return $address['municipality']
            ?? $address['city']
            ?? $address['town']
            ?? $address['village']
            ?? null;
    }

    /**
     * Extrae la provincia de la dirección
     */
    private function extraerProvincia(array $address): ?string
    {
        return $address['province'] ?? $address['county'] ?? null;
    }

    /**
     * Extrae la ciudad de la dirección
     */
    private function extraerCiudad(array $address): ?string
    {
        return $address['city']
            ?? $address['town']
            ?? $address['village']
            ?? $address['municipality']
            ?? null;
    }
}
