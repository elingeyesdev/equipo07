<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DatoDueno extends Model
{
    protected $table = 'datos_duenos';

    protected $fillable = [
        'dato_sanitario_id',
        'nombre_dueno',
        'carnet_dueno_foto',
    ];

    public function datoSanitario()
    {
        return $this->belongsTo(DatoSanitario::class);
    }
}
