<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrganicoImagen extends Model
{

    protected $table = 'organico_imagenes';

    protected $fillable = [
        'organico_id',
        'ruta',
        'orden',
    ];

    /**
     * Relación: una imagen pertenece a un orgánico
     */
    public function organico()
    {
        return $this->belongsTo(Organico::class);
    }
}
