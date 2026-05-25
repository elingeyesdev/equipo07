<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UbicacionOrganicoUnificada extends Model
{
    use HasFactory;

    protected $table = 'ubicaciones_organicos';

    protected $fillable = [
        'organico_id',
        'direccion',
        'departamento',
        'provincia',
        'municipio',
        'ciudad',
        'latitud',
        'longitud',
        'referencia',
    ];

    public function organico()
    {
        return $this->belongsTo(Organico::class);
    }
}
