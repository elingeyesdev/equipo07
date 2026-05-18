<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UbicacionMaquinaria extends Model
{
    protected $table = 'ubicacion_maquinaria';

    protected $fillable = [
        'ubicacion',
        'latitud',
        'longitud',
        'ubicacion_geografica_maquinaria_id',
    ];

    public function ubicacionGeografica()
    {
        return $this->belongsTo(UbicacionGeograficaMaquinaria::class, 'ubicacion_geografica_maquinaria_id');
    }

    public function maquinarias()
    {
        return $this->hasMany(Maquinaria::class, 'ubicacion_maquinaria_id');
    }
}
