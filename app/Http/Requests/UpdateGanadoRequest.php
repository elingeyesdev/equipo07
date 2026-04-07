<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGanadoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre'              => 'required|string|max:255',
            'tipo_animal_id'      => 'required|exists:tipo_animals,id',
            'raza_id'             => 'nullable|exists:razas,id',
            'edad_anos'           => 'required|integer|min:0|max:25',
            'edad_meses'          => 'required|integer|min:0|max:11',
            'edad_dias'           => 'required|integer|min:0|max:30',
            'peso_actual'         => 'nullable|numeric|min:0',
            'sexo'                => 'nullable|string|max:20',
            'cantidad_leche_dia'  => 'nullable|numeric|min:0',
            'descripcion'         => 'nullable|string|max:5000',
            'precio'              => 'nullable|numeric|min:0',
            'stock'               => 'required|integer|min:0',
            'tipo_peso_id'        => 'required|exists:tipo_pesos,id',
            'imagenes.*'          => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
            'imagenes_eliminar.*' => 'nullable|exists:ganado_imagenes,id',
            'categoria_id'        => 'required|exists:categorias,id',
            'ubicacion'           => 'nullable|string|max:255',
            'latitud'             => 'nullable|numeric|between:-90,90',
            'longitud'            => 'nullable|numeric|between:-180,180',
            'departamento'        => 'nullable|string|max:255',
            'municipio'           => 'nullable|string|max:255',
            'provincia'           => 'nullable|string|max:255',
            'ciudad'              => 'nullable|string|max:255',
            'dato_sanitario_id'   => 'nullable|exists:datos_sanitarios,id',
        ];
    }
}
