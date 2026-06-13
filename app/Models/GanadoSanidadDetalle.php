<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GanadoSanidadDetalle extends Model
{
    protected $fillable = ['ganado_id', 'requisito_id', 'fecha_aplicacion', 'texto_referencia', 'estado_auditoria'];

    public function ganado()
    {
        return $this->belongsTo(Ganado::class);
    }
}
