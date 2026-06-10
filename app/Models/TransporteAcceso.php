<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransporteAcceso extends Model
{
    public const ESTADO_ACTIVO = 'activo';
    public const ESTADO_REVOCADO = 'revocado';
    public const ESTADO_EXPIRADO = 'expirado';

    protected $table = 'transporte_accesos';

    protected $fillable = [
        'pedido_detalle_id',
        'created_by',
        'codigo_hash',
        'codigo_cifrado',
        'estado',
        'expires_at',
        'last_access_at',
    ];

    protected $hidden = [
        'codigo_hash',
        'codigo_cifrado',
    ];

    protected $casts = [
        'codigo_cifrado' => 'encrypted',
        'expires_at' => 'datetime',
        'last_access_at' => 'datetime',
    ];

    public function detalle()
    {
        return $this->belongsTo(PedidoDetalle::class, 'pedido_detalle_id');
    }

    public function creador()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function eventos()
    {
        return $this->hasMany(TransporteEvento::class, 'transporte_acceso_id');
    }

    public function ubicaciones()
    {
        return $this->hasMany(PedidoUbicacion::class, 'transporte_acceso_id');
    }

    public function estaActivo(): bool
    {
        return $this->estado === self::ESTADO_ACTIVO
            && (!$this->expires_at || $this->expires_at->isFuture());
    }

    public function getCodigoAttribute(): string
    {
        return (string) $this->codigo_cifrado;
    }
}
