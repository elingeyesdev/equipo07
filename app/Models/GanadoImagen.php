<?php

namespace App\Models;

use App\Models\Concerns\NormalizesStoredPaths;
use Illuminate\Database\Eloquent\Model;

class GanadoImagen extends Model
{
    use NormalizesStoredPaths;

    /**
     * Tabla asociada al modelo
     */
    protected $table = 'ganado_imagenes';

    protected $fillable = [
        'ganado_id',
        'ruta',
        'orden',
    ];

    /**
     * Relación: una imagen pertenece a un ganado
     */
    public function ganado()
    {
        return $this->belongsTo(Ganado::class);
    }

    public function getRutaNormalizadaAttribute(): ?string
    {
        return static::normalizeStoredPathValue($this->ruta);
    }

    public function getUrlAttribute(): ?string
    {
        return $this->storageAssetUrl($this->ruta);
    }
}
