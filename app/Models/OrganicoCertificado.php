<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganicoCertificado extends Model
{
    use HasFactory;

    public const ESTADO_PENDIENTE = 'pendiente';
    public const ESTADO_VERIFICADO = 'verificado';
    public const ESTADO_RECHAZADO = 'rechazado';

    protected $table = 'organico_certificados';

    protected $fillable = [
        'organico_id',
        'certificado_organico_id',
        'nombre_adicional',
        'estado',
        'archivo',
        'fecha_emision',
        'fecha_vencimiento',
        'observaciones',
    ];

    protected $casts = [
        'fecha_emision' => 'date',
        'fecha_vencimiento' => 'date',
    ];

    public function organico()
    {
        return $this->belongsTo(Organico::class);
    }

    public function certificado()
    {
        return $this->belongsTo(CertificadoOrganico::class, 'certificado_organico_id');
    }
}
