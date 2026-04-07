<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudVendedor extends Model
{
    use HasFactory;

    protected $table = 'solicitudes_vendedor';

    protected $fillable = [
        'user_id',
        'motivo',
        'telefono',
        'direccion',
        'documento',
        'archivo_documento',
        'estado',
        'fecha_revision_admin',
    ];

    protected $casts = [
        'fecha_revision_admin' => 'datetime',
    ];

    /**
     * Relación: una solicitud pertenece a un usuario
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope para filtrar por estado
     */
    public function scopePorEstado($query, $estado)
    {
        return $query->where('estado', $estado);
    }

    /**
     * Scope para solicitudes pendientes
     */
    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }
}
