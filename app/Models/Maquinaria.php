<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Maquinaria extends Model
{
    protected $fillable = [
        'nombre',
        'user_id',
        'tipo_maquinaria_id',
        'marca_maquinaria_id',
        'modelo',
        'telefono',
        'precio_dia',
        'estado',
        'estado_maquinaria_id',
        'descripcion',
        'categoria_id',
        'ubicacion',
        'departamento',
        'municipio',
        'provincia',
        'ciudad',
        'latitud',
        'longitud',
    ];

    /**
     * Relación: una maquinaria pertenece a una categoría
     */
    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    /**
     * Relación: una maquinaria pertenece a un tipo de maquinaria
     */
    public function tipoMaquinaria()
    {
        return $this->belongsTo(TipoMaquinaria::class, 'tipo_maquinaria_id');
    }

    /**
     * Relación: una maquinaria pertenece a una marca de maquinaria
     */
    public function marcaMaquinaria()
    {
        return $this->belongsTo(MarcaMaquinaria::class, 'marca_maquinaria_id');
    }

    /**
     * Relación: una maquinaria pertenece a un usuario
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    /**
     * Relación: una maquinaria pertenece a un estado de maquinaria
     */
    public function estadoMaquinaria()
    {
        return $this->belongsTo(EstadoMaquinaria::class, 'estado_maquinaria_id');
    }

    /**
     * Relación: una maquinaria tiene muchas imágenes
     */
    public function imagenes()
    {
        return $this->hasMany(MaquinariaImagen::class)->orderBy('orden');
    }
}
