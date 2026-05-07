<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GanadoLogro extends Model
{
    protected $table = 'ganado_logros';

    protected $fillable = [
        'ganado_id',
        'tipo_logro',
        'descripcion',
        'certificado_imagen',
        'fecha_logro'
    ];

    public function ganado()
    {
        return $this->belongsTo(Ganado::class);
    }
}
