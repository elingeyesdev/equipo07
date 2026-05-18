<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DatoComercialOrganico extends Model
{
    use HasFactory;

    protected $table = 'datos_comerciales_organicos';

    protected $fillable = [
        'organico_id',
        'unidad_id',
        'precio',
        'stock',
    ];

    public function organico()
    {
        return $this->belongsTo(Organico::class);
    }

    public function unidad()
    {
        return $this->belongsTo(UnidadOrganico::class, 'unidad_id');
    }
}
