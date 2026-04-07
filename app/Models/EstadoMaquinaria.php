<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoMaquinaria extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    /**
     * Relación: un estado tiene muchas maquinarias
     */
    public function maquinarias()
    {
        return $this->hasMany(Maquinaria::class, 'estado_maquinaria_id');
    }
}
