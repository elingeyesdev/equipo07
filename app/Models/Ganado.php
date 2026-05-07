<?php

namespace App\Models;

use App\Models\Concerns\NormalizesStoredPaths;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ganado extends Model
{
    use HasFactory;
    use NormalizesStoredPaths;

    protected $fillable = [
        'nombre',
        'user_id',
        'tipo_animal_id',
        'raza_id',
        'edad',
        'tipo_peso_id',
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
        'es_campeon',     // 👈 nuevo
        'madre_id',       // 👈 nuevo
        'padre_id',       // 👈 nuevo
        'ubicacion_id',
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
        return $this->hasOne(DatoSanitario::class, 'ganado_id');
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

    public function logros()
    {
        return $this->hasMany(GanadoLogro::class);
    }

    public function documentos()
    {
        return $this->hasMany(GanadoDocumento::class);
    }

    /**
     * Relación: un ganado pertenece a una ubicación (Nueva estructura)
     */
    public function ubicacionAsociada()
    {
        return $this->belongsTo(Ubicacion::class, 'ubicacion_id');
    }

    /**
     * Relación polimórfica: un ganado puede tener una publicación centralizada
     */
    public function publicacion()
    {
        return $this->morphOne(\App\Models\Publicacion::class, 'publicable');
    }

    /**
     * Relación para la nueva tabla de árbol genealógico (normalizado)
     */
    public function genealogias()
    {
        return $this->hasMany(GanadoGenealogia::class, 'ganado_id');
    }
}
