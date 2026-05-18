<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImagenMarcaGanado extends Model
{
    protected $table = 'imagenes_marca_ganado';

    protected $fillable = [
        'marca_animal_id',
        'ruta',
        'orden',
    ];

    public function marcaAnimal()
    {
        return $this->belongsTo(MarcaAnimal::class);
    }
}
