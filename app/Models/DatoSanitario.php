<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DatoSanitario extends Model
{
    protected $table = 'datos_sanitarios';

    protected $fillable = [
        'user_id',
        'ganado_id',
        'vacuna',
        'vacunado_fiebre_aftosa',
        'vacunado_antirabica',
        'tratamiento',
        'medicamento',
        'fecha_aplicacion',
        'proxima_fecha',
        'veterinario',
        'observaciones',
        'certificado_imagen',
        'certificado_campeon_imagen',
        'logro_campeon_raza',
        'logro_gran_campeon_macho',
        'logro_gran_campeon_hembra',
        'logro_mejor_ubre',
        'logro_campeona_litros_dia',
        'logro_mejor_lactancia',
        'logro_mejor_calidad_leche',
        'logro_mejor_novillo',
        'logro_gran_campeon_carne',
        'logro_mejor_semental',
        'logro_mejor_madre',
        'logro_mejor_padre',
        'logro_mejor_fertilidad',
        'arbol_genealogico',
        'destino_matadero_campo',
        'hoja_ruta_foto',
        'marca_ganado',
        'marca_ganado_foto',
        'senal_numero',
        'nombre_dueno',
        'carnet_dueno_foto'
    ];

    protected $casts = [
        'vacunado_fiebre_aftosa' => 'boolean',
        'vacunado_antirabica' => 'boolean',
    ];

    public function ganado()
    {
        return $this->belongsTo(Ganado::class);
    }
}
