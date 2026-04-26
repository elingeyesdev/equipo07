<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GanadoDocumento extends Model
{
    protected $table = 'ganado_documentos';

    protected $fillable = [
        'ganado_id',
        'tipo_documento',
        'ruta',
        'descripcion'
    ];

    public function ganado()
    {
        return $this->belongsTo(Ganado::class);
    }
}
