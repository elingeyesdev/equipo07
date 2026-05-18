<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImagenDatoSanitarioVacunacion extends Model
{
    protected $table = 'imagenes_dato_sanitario_vacunaciones';

    protected $fillable = [
        'dato_sanitario_vacunacion_id',
        'ruta',
        'orden',
    ];

    public function vacunacion()
    {
        return $this->belongsTo(DatoSanitarioVacunacion::class, 'dato_sanitario_vacunacion_id');
    }
}
