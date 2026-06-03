<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PedidoDetalle extends Model
{
    public const ALQUILER_ESTADOS = [
        'aceptado' => 'Aceptado',
        'en_camino_entrega' => 'En camino al comprador',
        'entregado' => 'Entregado',
        'en_uso' => 'En uso',
        'en_camino_retorno' => 'En retorno',
        'devuelto' => 'Devuelto',
        'finalizado' => 'Finalizado',
    ];

    protected $fillable = [
        'pedido_id',
        'vendedor_id',
        'estado_solicitud',
        'estado_alquiler',
        'respondido_at',
        'product_id',
        'product_type',
        'nombre_producto',
        'cantidad',
        'alquiler_unidad',
        'precio_unitario',
        'subtotal',
        'notas',
    ];

    protected $casts = [
        'precio_unitario' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'respondido_at' => 'datetime',
    ];

    public static function alquilerEstados(): array
    {
        return self::ALQUILER_ESTADOS;
    }

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

    public function getEsAlquilerMaquinariaAttribute(): bool
    {
        return $this->product_type === 'maquinaria';
    }

    public function getAlquilerUnidadNormalizadaAttribute(): ?string
    {
        if (!$this->es_alquiler_maquinaria) {
            return null;
        }

        return $this->alquiler_unidad
            ?: (str_contains(strtolower($this->notas ?? ''), 'día') ? 'dia' : 'hora');
    }

    public function getCantidadTiempoTextoAttribute(): string
    {
        if (!$this->es_alquiler_maquinaria) {
            return (string) $this->cantidad;
        }

        $unidad = $this->alquiler_unidad_normalizada;
        $singular = $unidad === 'dia' ? 'día' : 'hora';
        $plural = $unidad === 'dia' ? 'días' : 'horas';

        return $this->cantidad . ' ' . ($this->cantidad == 1 ? $singular : $plural);
    }

    public function getCantidadLabelAttribute(): string
    {
        if (!$this->es_alquiler_maquinaria) {
            return 'Cantidad';
        }

        return $this->alquiler_unidad_normalizada === 'dia'
            ? 'Días solicitados'
            : 'Horas solicitadas';
    }

    public function getPrecioLabelAttribute(): string
    {
        if (!$this->es_alquiler_maquinaria) {
            return 'Precio unitario';
        }

        return $this->alquiler_unidad_normalizada === 'dia'
            ? 'Precio por día'
            : 'Precio por hora';
    }

    public function getPrecioCortoLabelAttribute(): string
    {
        if (!$this->es_alquiler_maquinaria) {
            return 'unitario';
        }

        return $this->alquiler_unidad_normalizada === 'dia' ? 'por día' : 'por hora';
    }

    public function getEstadoAlquilerActualAttribute(): ?string
    {
        if (!$this->es_alquiler_maquinaria || $this->estado_solicitud !== 'aceptada') {
            return null;
        }

        return $this->estado_alquiler ?: 'aceptado';
    }

    public function getEstadoAlquilerLabelAttribute(): ?string
    {
        $estado = $this->estado_alquiler_actual;

        return $estado ? (self::ALQUILER_ESTADOS[$estado] ?? ucfirst(str_replace('_', ' ', $estado))) : null;
    }

    public function getSiguienteEstadoAlquilerAttribute(): ?string
    {
        $estado = $this->estado_alquiler_actual;

        return match ($estado) {
            'aceptado' => 'en_camino_entrega',
            'en_camino_entrega' => 'entregado',
            'entregado' => 'en_uso',
            'en_uso' => 'en_camino_retorno',
            'en_camino_retorno' => 'devuelto',
            default => null,
        };
    }

    public function getSiguienteEstadoAlquilerLabelAttribute(): ?string
    {
        $estado = $this->siguiente_estado_alquiler;

        return $estado ? self::ALQUILER_ESTADOS[$estado] : null;
    }

    public function getPuedeFinalizarDesdeVendedorAttribute(): bool
    {
        if ($this->estado_solicitud !== 'aceptada') {
            return false;
        }

        if (!$this->es_alquiler_maquinaria) {
            return true;
        }

        return $this->estado_alquiler_actual === 'devuelto';
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

    public function getVendedorTelefonoAttribute(): ?string
    {
        $telefonoProducto = $this->product?->telefono;

        if ($telefonoProducto) {
            return $telefonoProducto;
        }

        if (!$this->vendedor_id) {
            return null;
        }

        return SolicitudVendedor::where('user_id', $this->vendedor_id)
            ->where('estado', 'aprobada')
            ->latest('fecha_revision_admin')
            ->latest()
            ->value('telefono');
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
