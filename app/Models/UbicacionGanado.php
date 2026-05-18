<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UbicacionGanado extends Model
{
    protected $table = 'ubicacion_ganado';

    protected $fillable = [
        'ubicacion',
        'latitud',
        'longitud',
        'ubicacion_geografica_ganado_id',
    ];

    public function ubicacionGeografica()
    {
        return $this->belongsTo(UbicacionGeograficaGanado::class, 'ubicacion_geografica_ganado_id');
    }

    public function ganados()
    {
        return $this->hasMany(Ganado::class, 'ubicacion_ganado_id');
    }
}
