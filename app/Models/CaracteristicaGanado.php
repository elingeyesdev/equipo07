<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaracteristicaGanado extends Model
{
    protected $table = 'caracteristicas_ganado';

    protected $fillable = [
        'ganado_id',
        'edad',
        'edad_valor',
        'unidad_edad',
        'fecha_nacimiento',
        'sexo',
        'descripcion',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
    ];

    public function ganado()
    {
        return $this->belongsTo(Ganado::class);
    }
}
