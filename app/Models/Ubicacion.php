<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ubicacion extends Model
{
    protected $table = 'ubicaciones';

    protected $fillable = [
        'departamento',
        'provincia',
        'municipio',
        'ciudad',
        'direccion',
        'latitud',
        'longitud'
    ];

    public function ganados()
    {
        return $this->hasMany(Ganado::class);
    }

    public function maquinarias()
    {
        return $this->hasMany(Maquinaria::class);
    }

    public function organicos()
    {
        return $this->hasMany(Organico::class);
    }
}
