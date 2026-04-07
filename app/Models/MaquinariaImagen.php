<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaquinariaImagen extends Model
{
    /**
     * Tabla asociada al modelo
     */
    protected $table = 'maquinaria_imagenes';

    protected $fillable = [
        'maquinaria_id',
        'ruta',
        'orden',
    ];

    /**
     * Relación: una imagen pertenece a una maquinaria
     */
    public function maquinaria()
    {
        return $this->belongsTo(Maquinaria::class);
    }
}
