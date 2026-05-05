<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoAnimal extends Model
{
    use SoftDeletes; // Activa el Falso Borrado

    protected $fillable = ['nombre', 'descripcion'];
}