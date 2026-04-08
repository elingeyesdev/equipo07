<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Raza extends Model
{
    protected $fillable = [
        'nombre',
        'descripcion',
        'tipo_animal_id',
    ];

    public function tipoAnimal()
    {
        return $this->belongsTo(TipoAnimal::class);
    }
}
