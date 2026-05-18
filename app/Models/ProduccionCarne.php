<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProduccionCarne extends Model
{
    protected $table = 'produccion_carnes';

    protected $fillable = [
        'logro_reconocimiento_id',
        'logro_mejor_novillo',
        'logro_gran_campeon_carne',
        'logro_mejor_semental',
    ];

    protected $casts = [
        'logro_mejor_novillo' => 'boolean',
        'logro_gran_campeon_carne' => 'boolean',
        'logro_mejor_semental' => 'boolean',
    ];

    public function logroReconocimiento()
    {
        return $this->belongsTo(LogroReconocimiento::class);
    }
}
