<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UbicacionOrganico extends Model
{
    use HasFactory;

    protected $table = 'ubicacion_organico';

    protected $fillable = [
        'ubicacion',
        'latitud',
        'longitud',
        'ubicacion_geografica_organico_id',
    ];

    public function organicos()
    {
        return $this->hasMany(Organico::class, 'ubicacion_organico_id');
    }

    public function ubicacionGeografica()
    {
        return $this->belongsTo(UbicacionGeograficaOrganico::class, 'ubicacion_geografica_organico_id');
    }
}
