<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    /**
     * Relación: un rol tiene muchos usuarios
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Constantes para los nombres de roles
     */
    const ADMIN = 'admin';
    const VENDEDOR = 'vendedor';
    const CLIENTE = 'cliente';

    /**
     * Scope para obtener rol por nombre
     */
    public function scopePorNombre($query, $nombre)
    {
        return $query->where('nombre', $nombre);
    }
}
