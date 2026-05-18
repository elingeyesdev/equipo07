<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProduccionLeche extends Model
{
    protected $table = 'produccion_leches';

    protected $fillable = [
        'logro_reconocimiento_id',
        'logro_campeona_litros_dia',
        'logro_mejor_lactancia',
        'logro_mejor_calidad_leche',
    ];

    protected $casts = [
        'logro_campeona_litros_dia' => 'boolean',
        'logro_mejor_lactancia' => 'boolean',
        'logro_mejor_calidad_leche' => 'boolean',
    ];

    public function logroReconocimiento()
    {
        return $this->belongsTo(LogroReconocimiento::class);
    }
}
