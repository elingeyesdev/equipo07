<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $fillable = [
        'user_id',
        'product_type',
        'product_id',
        'cantidad',
        'precio_unitario',
        'subtotal',
        'notas',
    ];

    protected $casts = [
        'precio_unitario' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    /**
     * Relación: un item del carrito pertenece a un usuario
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con Ganado
     */
    public function ganado()
    {
        return $this->belongsTo(Ganado::class, 'product_id');
    }

    /**
     * Relación con Maquinaria
     */
    public function maquinaria()
    {
        return $this->belongsTo(Maquinaria::class, 'product_id');
    }

    /**
     * Relación con Orgánico
     */
    public function organico()
    {
        return $this->belongsTo(Organico::class, 'product_id');
    }

    /**
     * Obtener el producto relacionado según el tipo (método helper)
     */
    public function getProductAttribute()
    {
        switch ($this->product_type) {
            case 'ganado':
                return $this->ganado;
            case 'maquinaria':
                return $this->maquinaria;
            case 'organico':
                return $this->organico;
            default:
                return null;
        }
    }

    /**
     * Calcular el subtotal
     */
    public function calculateSubtotal()
    {
        $this->subtotal = $this->precio_unitario * $this->cantidad;
        return $this->subtotal;
    }
}
