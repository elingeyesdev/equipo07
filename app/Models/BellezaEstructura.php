<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BellezaEstructura extends Model
{
    protected $table = 'belleza_estructuras';

    protected $fillable = [
        'logro_reconocimiento_id',
        'logro_campeon_raza',
        'logro_gran_campeon_macho',
        'logro_gran_campeon_hembra',
        'logro_mejor_ubre',
    ];

    protected $casts = [
        'logro_campeon_raza' => 'boolean',
        'logro_gran_campeon_macho' => 'boolean',
        'logro_gran_campeon_hembra' => 'boolean',
        'logro_mejor_ubre' => 'boolean',
    ];

    public function logroReconocimiento()
    {
        return $this->belongsTo(LogroReconocimiento::class);
    }
}
