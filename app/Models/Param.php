<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Param extends Model
{
    protected $fillable = ['grupo', 'clave', 'nombre', 'parent_id', 'activo'];

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }
    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function scopeGrupo($q, string $g)
    {
        return $q->where('grupo', $g)->where('activo', true)->orderBy('nombre');
    }
    public function scopeHijosDe($q, $parentId)
    {
        return $q->where('parent_id', $parentId)->where('activo', true)->orderBy('nombre');
    }
}
