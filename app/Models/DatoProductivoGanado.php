<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DatoProductivoGanado extends Model
{
    protected $table = 'datos_productivos_ganado';

protected $fillable = ['ganado_id', 'tipo_peso_id', 'peso_actual', 'unidad_peso', 'tipo_pesaje', 'cantidad_leche_dia'];

    public function ganado()
    {
        return $this->belongsTo(Ganado::class);
    }

    public function tipoPeso()
    {
        return $this->belongsTo(TipoPeso::class, 'tipo_peso_id');
    }
}
