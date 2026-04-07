<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TipoCultivo extends Model
{
    use HasFactory;

    protected $table = 'tipo_cultivos';

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    public function organicos()
    {
        return $this->hasMany(Organico::class, 'tipo_cultivo_id');
    }
}
