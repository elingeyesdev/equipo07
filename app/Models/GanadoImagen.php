<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GanadoImagen extends Model
{
    /**
     * Tabla asociada al modelo
     */
    protected $table = 'ganado_imagenes';

    protected $fillable = [
        'ganado_id',
        'ruta',
        'orden',
    ];

    /**
     * Relación: una imagen pertenece a un ganado
     */
    public function ganado()
    {
        return $this->belongsTo(Ganado::class);
    }
}
