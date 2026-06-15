<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class EnvioAgrupacionService
{
    public const RADIO_AGRUPACION_METROS = 500;

    public function agrupar(Collection $items): array
    {
        $grupos = [];
        $resultado = [];

        foreach ($items as $item) {
            $product = $item->product;
            $origen = $this->origenPara($item->product_type, $product);
            $vendedorId = (int) ($product?->user_id ?? 0);
            $tipoCarga = $this->tipoCarga($item->product_type);
            $grupoId = null;

            if ($tipoCarga !== 'maquinaria') {
                foreach ($grupos as $grupo) {
                    if (
                        $grupo['vendedor_id'] === $vendedorId
                        && $grupo['tipo_carga'] === $tipoCarga
                        && $this->origenesCompatibles($grupo['origen'], $origen)
                    ) {
                        $grupoId = $grupo['id'];
                        break;
                    }
                }
            }

            if (! $grupoId) {
                $grupoId = (string) Str::uuid();
                $grupos[] = [
                    'id' => $grupoId,
                    'vendedor_id' => $vendedorId,
                    'tipo_carga' => $tipoCarga,
                    'origen' => $origen,
                ];
            }

            $resultado[$item->id] = [
                'grupo_envio' => $grupoId,
                'origen_direccion' => $origen['direccion'],
                'origen_latitud' => $origen['latitud'],
                'origen_longitud' => $origen['longitud'],
            ];
        }

        return $resultado;
    }

    private function tipoCarga(string $productType): string
    {
        return match ($productType) {
            'organico' => 'organico',
            'ganado' => 'ganado',
            default => 'maquinaria',
        };
    }

    private function origenPara(string $productType, $product): array
    {
        return match ($productType) {
            'organico' => [
                'direccion' => $product?->origen,
                'latitud' => $this->numeroONull($product?->latitud_origen),
                'longitud' => $this->numeroONull($product?->longitud_origen),
            ],
            'ganado', 'maquinaria' => [
                'direccion' => $product?->ubicacion,
                'latitud' => $this->numeroONull($product?->latitud),
                'longitud' => $this->numeroONull($product?->longitud),
            ],
            default => [
                'direccion' => null,
                'latitud' => null,
                'longitud' => null,
            ],
        };
    }

    private function origenesCompatibles(array $a, array $b): bool
    {
        if (
            $a['latitud'] !== null
            && $a['longitud'] !== null
            && $b['latitud'] !== null
            && $b['longitud'] !== null
        ) {
            return $this->distanciaMetros(
                $a['latitud'],
                $a['longitud'],
                $b['latitud'],
                $b['longitud']
            ) <= self::RADIO_AGRUPACION_METROS;
        }

        $direccionA = $this->normalizarDireccion($a['direccion']);
        $direccionB = $this->normalizarDireccion($b['direccion']);

        return $direccionA !== '' && $direccionA === $direccionB;
    }

    public function distanciaMetros(float $latA, float $lngA, float $latB, float $lngB): float
    {
        $radioTierraMetros = 6371000;
        $latDelta = deg2rad($latB - $latA);
        $lngDelta = deg2rad($lngB - $lngA);
        $origenLat = deg2rad($latA);
        $destinoLat = deg2rad($latB);

        $a = sin($latDelta / 2) ** 2
            + cos($origenLat) * cos($destinoLat) * sin($lngDelta / 2) ** 2;

        return $radioTierraMetros * 2 * atan2(sqrt($a), sqrt(1 - $a));
    }

    private function normalizarDireccion(?string $direccion): string
    {
        return Str::of((string) $direccion)
            ->lower()
            ->ascii()
            ->replaceMatches('/[^a-z0-9]+/', ' ')
            ->squish()
            ->toString();
    }

    private function numeroONull($value): ?float
    {
        return is_numeric($value) ? (float) $value : null;
    }
}
