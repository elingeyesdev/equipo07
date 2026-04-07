<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarcaMaquinaria extends Model
{
    use HasFactory;

    protected $table = 'marcas_maquinarias';

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    /**
     * Relación: una marca de maquinaria tiene muchas maquinarias
     */
    public function maquinarias()
    {
        return $this->hasMany(Maquinaria::class);
    }
}
