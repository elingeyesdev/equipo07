<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GanadoGenealogia extends Model
{
    protected $table = 'ganado_genealogias';

    protected $fillable = [
        'ganado_id',
        'pariente_id',
        'tipo_relacion',
        'observaciones',
    ];

    public function ganado()
    {
        return $this->belongsTo(Ganado::class, 'ganado_id');
    }

    public function pariente()
    {
        return $this->belongsTo(Ganado::class, 'pariente_id');
    }
}
