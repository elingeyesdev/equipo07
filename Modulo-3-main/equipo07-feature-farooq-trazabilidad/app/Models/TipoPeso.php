<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoPeso extends Model
{
    protected $fillable = [
        'nombre',
        'descripcion'
    ];
}
