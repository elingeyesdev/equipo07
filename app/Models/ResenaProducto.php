<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResenaProducto extends Model
{
    protected $table = 'resenas_organicos';

    protected $fillable = [
        'pedido_detalle_id',
        'comprador_id',
        'vendedor_id',
        'product_type',
        'product_id',
        'organico_id',
        'estrellas',
        'comentario',
    ];

    protected $casts = [
        'estrellas' => 'integer',
    ];

    public function detalle()
    {
        return $this->belongsTo(PedidoDetalle::class, 'pedido_detalle_id');
    }

    public function comprador()
    {
        return $this->belongsTo(User::class, 'comprador_id');
    }

    public function vendedor()
    {
        return $this->belongsTo(User::class, 'vendedor_id');
    }

    public function organico()
    {
        return $this->belongsTo(Organico::class);
    }
}
