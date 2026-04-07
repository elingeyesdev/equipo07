<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnidadOrganico extends Model
{
    use HasFactory;

    /**
     * Tabla asociada al modelo
     */
    protected $table = 'unidades_organicos';

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    /**
     * Relación: una unidad tiene muchos orgánicos
     */
    public function organicos()
    {
        return $this->hasMany(Organico::class, 'unidad_id');
    }
}
