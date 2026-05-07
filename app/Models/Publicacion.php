<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Publicacion extends Model
{
    use HasFactory;

    protected $table = 'publicaciones';

    protected $fillable = [
        'user_id',
        'publicable_type',
        'publicable_id',
        'titulo',
        'descripcion',
        'precio',
        'stock',
        'estado',
    ];

    /**
     * Obtener el modelo que posee la publicación (Ganado, Maquinaria, Organico).
     */
    public function publicable()
    {
        return $this->morphTo();
    }

    /**
     * Obtener el usuario dueño de la publicación.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtener los detalles de pedidos asociados a la publicación.
     */
    public function pedidoDetalles()
    {
        return $this->hasMany(PedidoDetalle::class);
    }
}
