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

    public const TRANSPORTE_ESTADOS = [
        'asignado' => 'Transportista asignado',
        'en_camino_recogida' => 'En camino a recoger',
        'llego_recogida' => 'Llego al punto de recogida',
        'producto_recogido' => 'Producto recogido',
        'en_camino_entrega' => 'En camino al comprador',
        'llego_destino' => 'Llego al destino',
        'esperando_confirmacion' => 'Esperando confirmacion del comprador',
        'entregado' => 'Entregado al comprador',
        'devolucion_solicitada' => 'Devolucion solicitada',
        'en_camino_recoger_devolucion' => 'En camino a recoger devolucion',
        'llego_recoger_devolucion' => 'Llego a recoger devolucion',
        'maquinaria_recogida_retorno' => 'Maquinaria recogida para retorno',
        'en_camino_retorno' => 'En camino al punto de retorno',
        'llego_retorno' => 'Llego al punto de retorno',
        'devuelto_vendedor' => 'Devuelto al vendedor',
    ];

    public const TRANSPORTE_FASES = [
        'asignado' => 'Asignado',
        'en_camino_recogida' => 'En camino a recoger',
        'recogido' => 'Recogido',
        'en_camino_entrega' => 'En camino a entregar',
        'llego_destino' => 'Llego a la ubicacion',
        'confirmacion' => 'Confirmacion',
        'en_uso' => 'En uso',
        'devolucion' => 'Devolucion',
        'devuelto' => 'Devuelto',
    ];

    public const TRANSPORTE_ESTADO_FASES = [
        'asignado' => 'asignado',
        'en_camino_recogida' => 'en_camino_recogida',
        'llego_recogida' => 'en_camino_recogida',
        'producto_recogido' => 'recogido',
        'en_camino_entrega' => 'en_camino_entrega',
        'llego_destino' => 'llego_destino',
        'esperando_confirmacion' => 'confirmacion',
        'entregado' => 'en_uso',
        'devolucion_solicitada' => 'en_uso',
        'en_camino_recoger_devolucion' => 'devolucion',
        'llego_recoger_devolucion' => 'devolucion',
        'maquinaria_recogida_retorno' => 'devolucion',
        'en_camino_retorno' => 'devolucion',
        'llego_retorno' => 'devolucion',
        'devuelto_vendedor' => 'devuelto',
    ];

    protected $fillable = [
        'pedido_id',
        'grupo_envio',
        'origen_direccion',
        'origen_latitud',
        'origen_longitud',
        'vendedor_id',
        'transportista_id',
        'estado_solicitud',
        'estado_alquiler',
        'estado_transporte',
        'respondido_at',
        'recepcion_confirmada_at',
        'firma_transportista',
        'firma_transportista_at',
        'firma_comprador',
        'firma_comprador_at',
        'cancelacion_motivo',
        'cancelado_at',
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
        'recepcion_confirmada_at' => 'datetime',
        'firma_transportista_at' => 'datetime',
        'firma_comprador_at' => 'datetime',
        'cancelado_at' => 'datetime',
        'origen_latitud' => 'decimal:8',
        'origen_longitud' => 'decimal:8',
    ];

    public static function alquilerEstados(): array
    {
        return self::ALQUILER_ESTADOS;
    }

    public static function transporteEstados(): array
    {
        return self::TRANSPORTE_ESTADOS;
    }

    public static function transporteFases(): array
    {
        return self::TRANSPORTE_FASES;
    }

    public static function transporteEstadoFases(): array
    {
        return self::TRANSPORTE_ESTADO_FASES;
    }

    public static function transporteFasePara(?string $estado): ?string
    {
        return $estado ? (self::TRANSPORTE_ESTADO_FASES[$estado] ?? $estado) : null;
    }

    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }

    public function ubicaciones()
    {
        return $this->hasMany(PedidoUbicacion::class);
    }

    public function transporteAcceso()
    {
        return $this->hasOne(TransporteAcceso::class, 'grupo_envio', 'grupo_envio');
    }

    public function detallesEnvio()
    {
        return $this->hasMany(self::class, 'grupo_envio', 'grupo_envio');
    }

    public function transporteEventos()
    {
        return $this->hasMany(TransporteEvento::class, 'pedido_detalle_id');
    }

    public function resenaOrganico()
    {
        return $this->resenaProducto();
    }

    public function resenaProducto()
    {
        return $this->hasOne(ResenaProducto::class, 'pedido_detalle_id');
    }

    public function reclamos()
    {
        return $this->hasMany(Reclamo::class, 'pedido_detalle_id');
    }

    public function ultimaUbicacion()
    {
        return $this->hasOne(PedidoUbicacion::class)->latestOfMany();
    }

    public function vendedor()
    {
        return $this->belongsTo(User::class, 'vendedor_id');
    }

    public function transportista()
    {
        return $this->belongsTo(User::class, 'transportista_id');
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
        if (! $this->es_alquiler_maquinaria) {
            return null;
        }

        return $this->alquiler_unidad
            ?: (str_contains(strtolower($this->notas ?? ''), 'día') ? 'dia' : 'hora');
    }

    public function getCantidadTiempoTextoAttribute(): string
    {
        if (! $this->es_alquiler_maquinaria) {
            return (string) $this->cantidad;
        }

        $unidad = $this->alquiler_unidad_normalizada;
        $singular = $unidad === 'dia' ? 'día' : 'hora';
        $plural = $unidad === 'dia' ? 'días' : 'horas';

        return $this->cantidad.' '.($this->cantidad == 1 ? $singular : $plural);
    }

    public function getCantidadLabelAttribute(): string
    {
        if (! $this->es_alquiler_maquinaria) {
            return 'Cantidad';
        }

        return $this->alquiler_unidad_normalizada === 'dia'
            ? 'Días solicitados'
            : 'Horas solicitadas';
    }

    public function getPrecioLabelAttribute(): string
    {
        if (! $this->es_alquiler_maquinaria) {
            return 'Precio unitario';
        }

        return $this->alquiler_unidad_normalizada === 'dia'
            ? 'Precio por día'
            : 'Precio por hora';
    }

    public function getPrecioCortoLabelAttribute(): string
    {
        if (! $this->es_alquiler_maquinaria) {
            return 'unitario';
        }

        return $this->alquiler_unidad_normalizada === 'dia' ? 'por día' : 'por hora';
    }

    public function getEstadoAlquilerActualAttribute(): ?string
    {
        if (! $this->es_alquiler_maquinaria || $this->estado_solicitud !== 'aceptada') {
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

        if (! $this->es_alquiler_maquinaria) {
            return $this->estado_transporte_actual === 'entregado';
        }

        return $this->estado_alquiler_actual === 'devuelto';
    }

    public function getEstadoTransporteActualAttribute(): ?string
    {
        if ($this->estado_solicitud !== 'aceptada') {
            return null;
        }

        return $this->estado_transporte ?: 'asignado';
    }

    public function getEstadoTransporteLabelAttribute(): ?string
    {
        $estado = $this->estado_transporte_actual;

        $estados = [
            ...self::TRANSPORTE_ESTADOS,
            'aceptado' => 'Aceptado',
            'preparando' => 'Preparando',
            'cancelado' => 'Cancelado',
        ];

        return $estado ? ($estados[$estado] ?? ucfirst(str_replace('_', ' ', $estado))) : null;
    }

    public function getSiguienteEstadoTransporteAttribute(): ?string
    {
        if ($this->estado_transporte_actual === 'esperando_confirmacion') {
            return null;
        }

        if (! $this->es_alquiler_maquinaria && in_array($this->estado_transporte_actual, [
            'entregado',
            'devolucion_solicitada',
            'en_camino_recoger_devolucion',
            'llego_recoger_devolucion',
            'maquinaria_recogida_retorno',
            'en_camino_retorno',
            'llego_retorno',
            'devuelto_vendedor',
        ], true)) {
            return null;
        }

        return match ($this->estado_transporte_actual) {
            'asignado' => 'en_camino_recogida',
            'en_camino_recogida' => 'llego_recogida',
            'llego_recogida' => 'producto_recogido',
            'producto_recogido' => 'en_camino_entrega',
            'en_camino_entrega' => 'llego_destino',
            'llego_destino' => 'esperando_confirmacion',
            'entregado' => $this->es_alquiler_maquinaria ? 'devolucion_solicitada' : null,
            'devolucion_solicitada' => 'en_camino_recoger_devolucion',
            'en_camino_recoger_devolucion' => 'llego_recoger_devolucion',
            'llego_recoger_devolucion' => 'maquinaria_recogida_retorno',
            'maquinaria_recogida_retorno' => 'en_camino_retorno',
            'en_camino_retorno' => 'llego_retorno',
            'llego_retorno' => 'devuelto_vendedor',
            default => null,
        };
    }

    public function getSiguienteEstadoTransporteLabelAttribute(): ?string
    {
        $estado = $this->siguiente_estado_transporte;

        return $estado ? self::TRANSPORTE_ESTADOS[$estado] : null;
    }

    public function getProductLatitudAttribute()
    {
        if ($this->origen_latitud !== null) {
            return $this->origen_latitud;
        }

        return match ($this->product_type) {
            'ganado', 'maquinaria' => $this->product?->latitud,
            'organico' => $this->product?->latitud_origen,
            default => null,
        };
    }

    public function getProductLongitudAttribute()
    {
        if ($this->origen_longitud !== null) {
            return $this->origen_longitud;
        }

        return match ($this->product_type) {
            'ganado', 'maquinaria' => $this->product?->longitud,
            'organico' => $this->product?->longitud_origen,
            default => null,
        };
    }

    public function getOrigenDireccionActualAttribute(): ?string
    {
        return $this->origen_direccion
            ?: ($this->product?->ubicacion ?? $this->organico?->origen);
    }

    public function getVendedorTelefonoAttribute(): ?string
    {
        $telefonoProducto = $this->product?->telefono;

        if ($telefonoProducto) {
            return $telefonoProducto;
        }

        if (! $this->vendedor_id) {
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

        if (! $productLat || ! $productLng || ! $destinoLat || ! $destinoLng) {
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
