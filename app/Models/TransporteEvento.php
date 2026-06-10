<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransporteEvento extends Model
{
    public const UPDATED_AT = null;

    protected $table = 'transporte_eventos';

    protected $fillable = [
        'pedido_detalle_id',
        'transporte_acceso_id',
        'user_id',
        'actor',
        'estado_anterior',
        'estado_nuevo',
        'metadata',
        'created_at',
    ];

    protected $casts = [
        'metadata' => 'array',
        'created_at' => 'datetime',
    ];

    public function detalle()
    {
        return $this->belongsTo(PedidoDetalle::class, 'pedido_detalle_id');
    }

    public function acceso()
    {
        return $this->belongsTo(TransporteAcceso::class, 'transporte_acceso_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
