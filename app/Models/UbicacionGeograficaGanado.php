<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UbicacionGeograficaGanado extends Model
{
    protected $table = 'ubicacion_geografica_ganados';

    protected $fillable = [
        'departamento',
        'municipio',
        'provincia',
        'ciudad',
    ];

    public function ubicaciones()
    {
        return $this->hasMany(UbicacionGanado::class, 'ubicacion_geografica_ganado_id');
    }
}
