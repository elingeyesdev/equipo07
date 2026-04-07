<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organico extends Model
{
    use HasFactory;

    /**
     * Tabla asociada al modelo
     */
    protected $table = 'organicos';

    /**
     * Campos que se pueden asignar masivamente
     */
    protected $fillable = [
        'nombre',
        'user_id',
        'categoria_id',
        'unidad_id',
        'precio',
        'stock',
        'fecha_cosecha',
        'descripcion',
        'origen',
        'latitud_origen',
        'longitud_origen',
        'departamento_origen',
        'municipio_origen',
        'provincia_origen',
        'ciudad_origen',
        'tipo_cultivo_id',
    ];

    /**
     * Relación: un orgánico pertenece a una categoría
     */
    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id', 'id');
    }

    /**
     * Relación: un orgánico pertenece a un usuario
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    /**
     * Relación: un orgánico pertenece a una unidad de medida
     */
    public function unidad()
    {
        return $this->belongsTo(UnidadOrganico::class, 'unidad_id');
    }

    /**
     * Relación: un orgánico tiene muchas imágenes
     */
    public function imagenes()
    {
        return $this->hasMany(OrganicoImagen::class)->orderBy('orden');
    }

    public function tipoCultivo()
    {
        return $this->belongsTo(TipoCultivo::class, 'tipo_cultivo_id');
    }
    public function unidadOrganico()
    {
        return $this->belongsTo(\App\Models\UnidadOrganico::class, 'unidad_id');
    }

    public function trazabilidad()
    {
        return $this->hasOne(OrganicoTrazabilidad::class, 'organico_id');
    }
}
