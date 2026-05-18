<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarcaAnimal extends Model
{
    protected $table = 'marcas_animales';

    protected $fillable = [
        'dato_sanitario_id',
        'marca_ganado',
        'senal_numero',
    ];

    public function datoSanitario()
    {
        return $this->belongsTo(DatoSanitario::class);
    }

    public function imagenes()
    {
        return $this->hasMany(ImagenMarcaGanado::class)->orderBy('orden');
    }

    public function imagenPrincipal()
    {
        return $this->hasOne(ImagenMarcaGanado::class)->oldest('orden')->oldest('id');
    }
}
