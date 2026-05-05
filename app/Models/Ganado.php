<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ganado extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'user_id',
        'tipo_animal_id',
        'raza_id',
        'edad',
        'peso_actual',
        'sexo',
        'cantidad_leche_dia',
        'precio',
        'stock',
        'imagen',
        'descripcion',
        'categoria_id',
        'fecha_publicacion',
        'ubicacion',
        'departamento',
        'municipio',
        'provincia',
        'ciudad',
        'latitud',
        'longitud',
        'tipo_venta',
        'tipo_precio',
        'tiene_sanidad',
        'archivo_sanidad',
        'es_campeon',
        'archivo_genetica',
        'madre_id',
        'padre_id',
    ];


    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function tipoAnimal()
    {
        return $this->belongsTo(\App\Models\TipoAnimal::class);
    }

    public function tipoPeso()
    {
        return $this->belongsTo(\App\Models\TipoPeso::class, 'tipo_peso_id');
    }

    public function raza()
    {
        return $this->belongsTo(Raza::class);
    }

    // ✅ RELACIÓN CORRECTA (uno a uno)
    public function datoSanitario()
    {
        return $this->belongsTo(DatoSanitario::class, 'dato_sanitario_id');
    }

    /**
     * Relación: un ganado pertenece a un usuario
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    /**
     * Relación: un ganado tiene muchas imágenes
     */
    public function imagenes()
    {
        return $this->hasMany(GanadoImagen::class)->orderBy('orden');
    }

    /**
     * Relación: un ganado tiene una madre
     */
    public function madre()
    {
        return $this->belongsTo(Ganado::class, 'madre_id');
    }

    /**
     * Relación: un ganado tiene un padre
     */
    public function padre()
    {
        return $this->belongsTo(Ganado::class, 'padre_id');
    }

    /**
     * Relación: un ganado puede ser madre de otros ganados
     */
    public function hijosMadre()
    {
        return $this->hasMany(Ganado::class, 'madre_id');
    }

    /**
     * Relación: un ganado puede ser padre de otros ganados
     */
    public function hijosPadre()
    {
        return $this->hasMany(Ganado::class, 'padre_id');
    }
}
