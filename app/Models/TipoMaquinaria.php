<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoMaquinaria extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    /**
     * Relación: un tipo de maquinaria tiene muchas maquinarias
     */
    public function maquinarias()
    {
        return $this->hasMany(Maquinaria::class);
    }
}
