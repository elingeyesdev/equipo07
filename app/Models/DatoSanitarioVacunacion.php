<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DatoSanitarioVacunacion extends Model
{
    protected $table = 'dato_sanitario_vacunaciones';

    protected $fillable = [
        'dato_sanitario_id',
        'vacuna',
        'vacunado_fiebre_aftosa',
        'vacunado_antirabica',
    ];

    protected $casts = [
        'vacunado_fiebre_aftosa' => 'boolean',
        'vacunado_antirabica' => 'boolean',
    ];

    public function datoSanitario()
    {
        return $this->belongsTo(DatoSanitario::class);
    }

    public function imagenes()
    {
        return $this->hasMany(ImagenDatoSanitarioVacunacion::class)->orderBy('orden');
    }

    public function imagenPrincipal()
    {
        return $this->hasOne(ImagenDatoSanitarioVacunacion::class)->oldest('orden')->oldest('id');
    }
}
