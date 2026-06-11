<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maquinaria extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'user_id',
        'tipo_maquinaria_id',
        'marca_maquinaria_id',
        'modelo',
        'telefono',
        'precio_dia',
        'tarifa_unidad',
        'estado_maquinaria_id',
        'ubicacion_maquinaria_id',
        'descripcion',
        'categoria_id',
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
     * Relación: una maquinaria tiene una ubicación normalizada
     */
    public function ubicacionMaquinaria()
    {
        return $this->belongsTo(UbicacionMaquinaria::class, 'ubicacion_maquinaria_id');
    }

    /**
     * Relación: una maquinaria tiene muchas imágenes
     */
    public function imagenes()
    {
        return $this->hasMany(MaquinariaImagen::class)->orderBy('orden');
    }

    public function resenas()
    {
        return $this->hasMany(ResenaProducto::class, 'product_id')
            ->where('product_type', 'maquinaria');
    }

    public function getUbicacionAttribute($value)
    {
        return $this->ubicacionMaquinaria?->ubicacion ?? $value;
    }

    public function getLatitudAttribute($value)
    {
        return $this->ubicacionMaquinaria?->latitud ?? $value;
    }

    public function getLongitudAttribute($value)
    {
        return $this->ubicacionMaquinaria?->longitud ?? $value;
    }

    public function getDepartamentoAttribute($value)
    {
        return $this->ubicacionMaquinaria?->ubicacionGeografica?->departamento ?? $value;
    }

    public function getMunicipioAttribute($value)
    {
        return $this->ubicacionMaquinaria?->ubicacionGeografica?->municipio ?? $value;
    }

    public function getProvinciaAttribute($value)
    {
        return $this->ubicacionMaquinaria?->ubicacionGeografica?->provincia ?? $value;
    }

    public function getCiudadAttribute($value)
    {
        return $this->ubicacionMaquinaria?->ubicacionGeografica?->ciudad ?? $value;
    }
}
