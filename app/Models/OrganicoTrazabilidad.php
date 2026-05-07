<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganicoTrazabilidad extends Model
{
    use HasFactory;

    protected $table = 'organico_trazabilidades';

    protected $fillable = [
        'organico_id',
        'origen',
        'finca',
        'ubicacion',
        'fecha_siembra',
        'fecha_cosecha',
        'tratamientos_utilizados',
        'certificaciones',
        'observaciones',
    ];

    protected $casts = [
        'fecha_siembra' => 'date',
        'fecha_cosecha' => 'date',
    ];

    public function organico()
    {
        return $this->belongsTo(Organico::class, 'organico_id');
    }
}
