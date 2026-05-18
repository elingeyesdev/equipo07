<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImagenCertificadoCampeon extends Model
{
    protected $table = 'imagenes_certificado_campeon';

    protected $fillable = [
        'dato_sanitario_id',
        'ruta',
        'orden',
    ];

    public function datoSanitario()
    {
        return $this->belongsTo(DatoSanitario::class);
    }
}
