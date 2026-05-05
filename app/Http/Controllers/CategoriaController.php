<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    /**
     * Muestra la lista de categorías (Modo Solo Lectura).
     */
    public function index()
    {
        $categorias = Categoria::all();
        return view('categorias.index', compact('categorias'));
    }

    // Funciones create, store, edit, update y destroy han sido 
    // ELIMINADAS intencionalmente por diseño de arquitectura.
    // Las Categorías Principales son pilares estructurales del sistema.
}