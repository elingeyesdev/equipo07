<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ProductorController extends Controller
{
    /**
     * Mostrar el perfil de un productor específico con todos sus productos agrupados.
     */
    public function show($id)
    {
        // Encontrar al usuario, y traer también sus publicaciones
        $productor = User::with([
            'ganados.datoSanitario', 
            'ganados.imagenes',
            'maquinarias.imagenes', 
            'maquinarias.marca', // Intentamos cargar marca si existe
            'organicos.trazabilidad',
            'organicos.imagenes'
        ])->findOrFail($id);

        // Si el usuario no tiene el rol de vendedor (y lo queremos restringir), 
        // podríamos lanzar un 404 o redirigir, pero si tiene productos publicados, se los mostramos.
        // Opcional:
        // if (!$productor->isVendedor()) {
        //     abort(404, 'Productor no encontrado');
        // }

        return view('productor.show', compact('productor'));
    }
}
