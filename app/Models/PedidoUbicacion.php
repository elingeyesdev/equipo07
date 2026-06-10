<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PedidoUbicacion extends Model
{
    protected $table = 'pedido_ubicaciones';

    protected $fillable = [
        'pedido_id',
        'pedido_detalle_id',
        'user_id',
        'transporte_acceso_id',
        'latitud',
        'longitud',
        'precision_metros',
        'velocidad_m_s',
        'rumbo_grados',
        'tipo_recorrido',
    ];

    protected $casts = [
        'latitud' => 'decimal:8',
        'longitud' => 'decimal:8',
        'precision_metros' => 'decimal:2',
        'velocidad_m_s' => 'decimal:2',
        'rumbo_grados' => 'decimal:2',
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }

    public function detalle()
    {
        return $this->belongsTo(PedidoDetalle::class, 'pedido_detalle_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function transporteAcceso()
    {
        return $this->belongsTo(TransporteAcceso::class, 'transporte_acceso_id');
    }
}
