<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reclamo extends Model
{
    public const ESTADOS = [
        'recibida' => 'Recibida',
        'en_revision' => 'En revision',
        'resuelta' => 'Resuelta',
    ];

    public const TIPOS = [
        'retraso' => 'Retraso en la entrega',
        'mal_estado' => 'Producto en mal estado',
        'incumplimiento' => 'Incumplimiento de lo acordado',
        'cancelacion' => 'Cancelacion del envio',
        'otro' => 'Otro problema',
    ];

    protected $fillable = [
        'pedido_detalle_id',
        'creador_id',
        'creador_rol',
        'tipo',
        'descripcion',
        'estado',
        'respuesta_admin',
    ];

    public function detalle()
    {
        return $this->belongsTo(PedidoDetalle::class, 'pedido_detalle_id');
    }

    public function creador()
    {
        return $this->belongsTo(User::class, 'creador_id');
    }

    public function getTipoLabelAttribute(): string
    {
        return self::TIPOS[$this->tipo] ?? ucfirst(str_replace('_', ' ', $this->tipo));
    }

    public function getEstadoLabelAttribute(): string
    {
        return self::ESTADOS[$this->estado] ?? ucfirst(str_replace('_', ' ', $this->estado));
    }
}
