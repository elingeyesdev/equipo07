<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArchivoArbolGenealogico extends Model
{
    protected $table = 'archivos_arbol_genealogico';

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
