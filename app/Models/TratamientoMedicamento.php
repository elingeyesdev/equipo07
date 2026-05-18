<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TratamientoMedicamento extends Model
{
    protected $table = 'tratamientos_medicamentos';

    protected $fillable = [
        'dato_sanitario_id',
        'tratamiento',
        'medicamento',
        'fecha_aplicacion',
        'proxima_fecha',
        'veterinario',
        'observaciones',
    ];

    public function datoSanitario()
    {
        return $this->belongsTo(DatoSanitario::class);
    }
}
