<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CertificadoOrganico extends Model
{
    use HasFactory;

    protected $table = 'certificados_organicos';

    protected $fillable = [
        'slug',
        'nombre',
        'descripcion',
        'tipo',
        'es_obligatorio',
        'activo',
        'orden',
    ];

    protected $casts = [
        'es_obligatorio' => 'boolean',
        'activo' => 'boolean',
    ];

    public function registros()
    {
        return $this->hasMany(OrganicoCertificado::class, 'certificado_organico_id');
    }
}
