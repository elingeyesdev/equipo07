<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UbicacionGeograficaOrganico extends Model
{
    use HasFactory;

    protected $table = 'ubicacion_geografica_organicos';

    protected $fillable = [
        'departamento',
        'municipio',
        'provincia',
        'ciudad',
    ];

    public function ubicaciones()
    {
        return $this->hasMany(UbicacionOrganico::class, 'ubicacion_geografica_organico_id');
    }
}
