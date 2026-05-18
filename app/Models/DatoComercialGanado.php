<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DatoComercialGanado extends Model
{
    protected $table = 'datos_comerciales_ganado';

    protected $fillable = [
        'ganado_id',
        'precio',
        'stock',
        'fecha_publicacion',
    ];

    public function ganado()
    {
        return $this->belongsTo(Ganado::class);
    }
}
