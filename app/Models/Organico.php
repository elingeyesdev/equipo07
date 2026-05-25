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
        'fecha_cosecha',
        'descripcion',
        'tipo_cultivo_id',
        'ubicacion_organico_id',
    ];

    protected $appends = [
        'precio',
        'stock',
        'unidad_id',
        'origen',
        'latitud_origen',
        'longitud_origen',
        'departamento_origen',
        'municipio_origen',
        'provincia_origen',
        'ciudad_origen',
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
        return $this->hasOneThrough(
            UnidadOrganico::class,
            DatoComercialOrganico::class,
            'organico_id',
            'id',
            'id',
            'unidad_id'
        );
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
        return $this->unidad();
    }

    public function ubicacionOrganico()
    {
        return $this->belongsTo(UbicacionOrganico::class, 'ubicacion_organico_id');
    }

    public function ubicacionUnificada()
    {
        return $this->hasOne(UbicacionOrganicoUnificada::class, 'organico_id');
    }

    public function trazabilidad()
    {
        return $this->hasOne(OrganicoTrazabilidad::class, 'organico_id');
    }

    public function certificadoRegistros()
    {
        return $this->hasMany(OrganicoCertificado::class, 'organico_id');
    }

    public function datoComercial()
    {
        return $this->hasOne(DatoComercialOrganico::class);
    }

    public function getPrecioAttribute($value)
    {
        return $this->datoComercial?->precio ?? $value;
    }

    public function getStockAttribute($value)
    {
        return $this->datoComercial?->stock ?? $value;
    }

    public function getUnidadIdAttribute($value)
    {
        return $this->datoComercial?->unidad_id ?? $value;
    }

    public function getOrigenAttribute($value)
    {
        return $this->ubicacionUnificada?->direccion
            ?? $this->ubicacionOrganico?->ubicacion
            ?? $value;
    }

    public function getLatitudOrigenAttribute($value)
    {
        return $this->ubicacionUnificada?->latitud
            ?? $this->ubicacionOrganico?->latitud
            ?? $value;
    }

    public function getLongitudOrigenAttribute($value)
    {
        return $this->ubicacionUnificada?->longitud
            ?? $this->ubicacionOrganico?->longitud
            ?? $value;
    }

    public function getDepartamentoOrigenAttribute()
    {
        return $this->ubicacionUnificada?->departamento
            ?? $this->ubicacionOrganico?->ubicacionGeografica?->departamento;
    }

    public function getMunicipioOrigenAttribute()
    {
        return $this->ubicacionUnificada?->municipio
            ?? $this->ubicacionOrganico?->ubicacionGeografica?->municipio;
    }

    public function getProvinciaOrigenAttribute()
    {
        return $this->ubicacionUnificada?->provincia
            ?? $this->ubicacionOrganico?->ubicacionGeografica?->provincia;
    }

    public function getCiudadOrigenAttribute()
    {
        return $this->ubicacionUnificada?->ciudad
            ?? $this->ubicacionOrganico?->ubicacionGeografica?->ciudad;
    }
}
