<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Raza extends Model
{
    use SoftDeletes; // Activa el Falso Borrado

    protected $fillable = [
        'nombre',
        'descripcion',
        'tipo_animal_id',
    ];

    public function tipoAnimal()
    {
        // withTrashed() permite ver la especie paterna incluso si fue archivada
        return $this->belongsTo(TipoAnimal::class)->withTrashed();
    }
}