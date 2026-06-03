<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $fillable = [
        'user_id',
        'total',
        'estado',
        'metodo_pago',
        'observaciones',
        'destino_entrega',
        'telefono_contacto',
        'destino_latitud',
        'destino_longitud',
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'destino_latitud' => 'decimal:8',
        'destino_longitud' => 'decimal:8',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function detalles()
    {
        return $this->hasMany(PedidoDetalle::class);
    }
}
