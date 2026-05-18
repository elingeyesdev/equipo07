<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReproduccionLogro extends Model
{
    protected $table = 'reproduccion_logros';

    protected $fillable = [
        'logro_reconocimiento_id',
        'logro_mejor_madre',
        'logro_mejor_padre',
        'logro_mejor_fertilidad',
    ];

    protected $casts = [
        'logro_mejor_madre' => 'boolean',
        'logro_mejor_padre' => 'boolean',
        'logro_mejor_fertilidad' => 'boolean',
    ];

    public function logroReconocimiento()
    {
        return $this->belongsTo(LogroReconocimiento::class);
    }
}
