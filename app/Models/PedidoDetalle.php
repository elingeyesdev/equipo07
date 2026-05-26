<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PedidoDetalle extends Model
{
    protected $fillable = [
        'pedido_id',
        'vendedor_id',
        'estado_solicitud',
        'respondido_at',
        'product_id',
        'product_type',
        'nombre_producto',
        'cantidad',
        'precio_unitario',
        'subtotal',
        'notas',
    ];

    protected $casts = [
        'precio_unitario' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'respondido_at' => 'datetime',
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }

    public function vendedor()
    {
        return $this->belongsTo(User::class, 'vendedor_id');
    }

    public function ganado()
    {
        return $this->belongsTo(Ganado::class, 'product_id');
    }

    public function maquinaria()
    {
        return $this->belongsTo(Maquinaria::class, 'product_id');
    }

    public function organico()
    {
        return $this->belongsTo(Organico::class, 'product_id');
    }

    public function getProductAttribute()
    {
        return match ($this->product_type) {
            'ganado' => $this->ganado,
            'maquinaria' => $this->maquinaria,
            'organico' => $this->organico,
            default => null,
        };
    }

    public function getProductLatitudAttribute()
    {
        return match ($this->product_type) {
            'ganado', 'maquinaria' => $this->product?->latitud,
            'organico' => $this->product?->latitud_origen,
            default => null,
        };
    }

    public function getProductLongitudAttribute()
    {
        return match ($this->product_type) {
            'ganado', 'maquinaria' => $this->product?->longitud,
            'organico' => $this->product?->longitud_origen,
            default => null,
        };
    }

    public function getDistanciaDestinoKmAttribute(): ?float
    {
        $pedido = $this->pedido;
        $productLat = $this->product_latitud;
        $productLng = $this->product_longitud;
        $destinoLat = $pedido?->destino_latitud;
        $destinoLng = $pedido?->destino_longitud;

        if (!$productLat || !$productLng || !$destinoLat || !$destinoLng) {
            return null;
        }

        $earthRadiusKm = 6371;
        $latDelta = deg2rad((float) $destinoLat - (float) $productLat);
        $lngDelta = deg2rad((float) $destinoLng - (float) $productLng);
        $originLat = deg2rad((float) $productLat);
        $targetLat = deg2rad((float) $destinoLat);

        $a = sin($latDelta / 2) ** 2
            + cos($originLat) * cos($targetLat) * sin($lngDelta / 2) ** 2;

        return $earthRadiusKm * 2 * atan2(sqrt($a), sqrt(1 - $a));
    }
}
