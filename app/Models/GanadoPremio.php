<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GanadoPremio extends Model
{
    protected $fillable = ['ganado_id', 'nombre_evento', 'titulo_galardon', 'ruta_imagen', 'estado_auditoria'];

    public function ganado()
    {
        return $this->belongsTo(Ganado::class);
    }
}
