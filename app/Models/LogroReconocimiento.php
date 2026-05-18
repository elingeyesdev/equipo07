<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogroReconocimiento extends Model
{
    protected $table = 'logros_reconocimientos';

    protected $fillable = [
        'dato_sanitario_id',
    ];

    public function datoSanitario()
    {
        return $this->belongsTo(DatoSanitario::class);
    }

    public function bellezaEstructura()
    {
        return $this->hasOne(BellezaEstructura::class);
    }

    public function produccionLeche()
    {
        return $this->hasOne(ProduccionLeche::class);
    }

    public function produccionCarne()
    {
        return $this->hasOne(ProduccionCarne::class);
    }

    public function reproduccionLogro()
    {
        return $this->hasOne(ReproduccionLogro::class);
    }
}
