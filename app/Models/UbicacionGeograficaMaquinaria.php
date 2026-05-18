<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UbicacionGeograficaMaquinaria extends Model
{
    protected $table = 'ubicacion_geografica_maquinarias';

    protected $fillable = [
        'departamento',
        'municipio',
        'provincia',
        'ciudad',
    ];

    public function ubicaciones()
    {
        return $this->hasMany(UbicacionMaquinaria::class, 'ubicacion_geografica_maquinaria_id');
    }
}
