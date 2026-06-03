<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ganado extends Model
{
    use HasFactory;

protected $fillable = [
    'nombre', 'user_id', 'tipo_animal_id', 'raza_id', 'categoria_id', 'ubicacion_ganado_id', 'es_campeon',
    'modalidad', 'proposito', 'tipo_genetica' // <-- NUEVOS
];

    protected $appends = [
        'ubicacion',
        'latitud',
        'longitud',
        'departamento',
        'municipio',
        'provincia',
        'ciudad',
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
        return $this->hasOneThrough(
            \App\Models\TipoPeso::class,
            DatoProductivoGanado::class,
            'ganado_id',
            'id',
            'id',
            'tipo_peso_id'
        );
    }

    public function datoProductivo()
    {
        return $this->hasOne(DatoProductivoGanado::class);
    }

    public function datoComercial()
    {
        return $this->hasOne(DatoComercialGanado::class);
    }

    public function caracteristica()
    {
        return $this->hasOne(CaracteristicaGanado::class);
    }

    public function genealogia()
    {
        return $this->hasOne(GenealogiaGanado::class);
    }

    public function raza()
    {
        return $this->belongsTo(Raza::class);
    }

    // Relacion real: un ganado puede tener varios registros sanitarios.
    public function datosSanitarios()
    {
        return $this->hasMany(DatoSanitario::class);
    }

    public function datoSanitario()
    {
        return $this->hasOne(DatoSanitario::class)->latestOfMany();
    }

    /**
     * Relación: un ganado tiene una ubicación normalizada
     */
    public function ubicacionGanado()
    {
        return $this->belongsTo(UbicacionGanado::class, 'ubicacion_ganado_id');
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
        return $this->hasOneThrough(
            Ganado::class,
            GenealogiaGanado::class,
            'ganado_id',
            'id',
            'id',
            'madre_id'
        );
    }

    /**
     * Relación: un ganado tiene un padre
     */
    public function padre()
    {
        return $this->hasOneThrough(
            Ganado::class,
            GenealogiaGanado::class,
            'ganado_id',
            'id',
            'id',
            'padre_id'
        );
    }

    /**
     * Relación: un ganado puede ser madre de otros ganados
     */
    public function hijosMadre()
    {
        return $this->hasManyThrough(
            Ganado::class,
            GenealogiaGanado::class,
            'madre_id',
            'id',
            'id',
            'ganado_id'
        );
    }

    /**
     * Relación: un ganado puede ser padre de otros ganados
     */
    public function hijosPadre()
    {
        return $this->hasManyThrough(
            Ganado::class,
            GenealogiaGanado::class,
            'padre_id',
            'id',
            'id',
            'ganado_id'
        );
    }

    public function getUbicacionAttribute($value)
    {
        return $this->ubicacionGanado?->ubicacion;
    }

    public function getEdadAttribute($value)
    {
        return $this->caracteristica?->edad ?? $value;
    }

    public function getSexoAttribute($value)
    {
        return $this->caracteristica?->sexo ?? $value;
    }

    public function getDescripcionAttribute($value)
    {
        return $this->caracteristica?->descripcion ?? $value;
    }

    public function getTipoPesoIdAttribute($value)
    {
        return $this->datoProductivo?->tipo_peso_id ?? $value;
    }

    public function getPesoActualAttribute($value)
    {
        return $this->datoProductivo?->peso_actual ?? $value;
    }

    public function getCantidadLecheDiaAttribute($value)
    {
        return $this->datoProductivo?->cantidad_leche_dia ?? $value;
    }

    public function getPrecioAttribute($value)
    {
        return $this->datoComercial?->precio ?? $value;
    }

    public function getStockAttribute($value)
    {
        return $this->datoComercial?->stock ?? $value;
    }

    public function getFechaPublicacionAttribute($value)
    {
        return $this->datoComercial?->fecha_publicacion ?? $value;
    }

    public function getMadreIdAttribute($value)
    {
        return $this->genealogia?->madre_id ?? $value;
    }

    public function getPadreIdAttribute($value)
    {
        return $this->genealogia?->padre_id ?? $value;
    }

    public function getLatitudAttribute($value)
    {
        return $this->ubicacionGanado?->latitud;
    }

    public function getLongitudAttribute($value)
    {
        return $this->ubicacionGanado?->longitud;
    }

    public function getDepartamentoAttribute($value)
    {
        return $this->ubicacionGanado?->ubicacionGeografica?->departamento;
    }

    public function getMunicipioAttribute($value)
    {
        return $this->ubicacionGanado?->ubicacionGeografica?->municipio;
    }

    public function getProvinciaAttribute($value)
    {
        return $this->ubicacionGanado?->ubicacionGeografica?->provincia;
    }

    public function getCiudadAttribute($value)
    {
        return $this->ubicacionGanado?->ubicacionGeografica?->ciudad;
    }
}
