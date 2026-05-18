<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GenealogiaGanado extends Model
{
    protected $table = 'genealogias_ganado';

    protected $fillable = [
        'ganado_id',
        'madre_id',
        'padre_id',
    ];

    public function ganado()
    {
        return $this->belongsTo(Ganado::class);
    }

    public function madre()
    {
        return $this->belongsTo(Ganado::class, 'madre_id');
    }

    public function padre()
    {
        return $this->belongsTo(Ganado::class, 'padre_id');
    }
}
