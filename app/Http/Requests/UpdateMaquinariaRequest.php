<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMaquinariaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => 'required|string|max:255',
            'categoria_id' => 'nullable|exists:categorias,id',
            'tipo_maquinaria_id' => 'required|exists:tipo_maquinarias,id',
            'marca_maquinaria_id' => 'required|exists:marcas_maquinarias,id',
            'modelo' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'precio_dia' => 'required|numeric|min:0',
            'tarifa_unidad' => 'required|in:hora,dia',
            'estado_maquinaria_id' => 'required|exists:estado_maquinarias,id',
            'descripcion' => 'nullable|string|max:5000',
            'imagenes.*' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
            'imagenes_eliminar.*' => 'nullable|exists:maquinaria_imagenes,id',
            'imagen_portada' => 'nullable|string',
            'ubicacion' => 'nullable|string|max:255',
            'latitud' => 'nullable|numeric|between:-90,90',
            'longitud' => 'nullable|numeric|between:-180,180',
            'departamento' => 'nullable|string|max:255',
            'municipio' => 'nullable|string|max:255',
            'provincia' => 'nullable|string|max:255',
            'ciudad' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre de la maquinaria es obligatorio.',
            'nombre.max' => 'El nombre no debe superar los 255 caracteres.',
            'categoria_id.required' => 'La categoría es obligatoria.',
            'categoria_id.exists'   => 'La categoría seleccionada no es válida.',
            'tipo_maquinaria_id.required' => 'El tipo de maquinaria es obligatorio.',
            'tipo_maquinaria_id.exists'   => 'El tipo de maquinaria seleccionado no es válido.',
            'marca_maquinaria_id.required' => 'La marca de maquinaria es obligatoria.',
            'marca_maquinaria_id.exists'   => 'La marca de maquinaria seleccionada no es válida.',
            'modelo.max' => 'El modelo no debe superar los 255 caracteres.',
            'telefono.max' => 'El teléfono no debe superar los 20 caracteres.',
            'precio_dia.required' => 'El precio por día es obligatorio.',
            'precio_dia.numeric' => 'El precio por día debe ser un número válido.',
            'precio_dia.min' => 'El precio por día no puede ser negativo.',
            'estado_maquinaria_id.required' => 'El estado de la maquinaria es obligatorio.',
            'estado_maquinaria_id.exists' => 'El estado seleccionado no es válido.',
            'descripcion.max' => 'La descripción no debe superar los 5000 caracteres.',
            'imagenes.*.image' => 'Los archivos deben ser imágenes válidas.',
            'imagenes.*.mimes' => 'Las imágenes deben ser de tipo: jpeg, jpg, png o gif.',
            'imagenes.*.max' => 'Cada imagen no debe superar los 2MB.',
            'imagenes_eliminar.*.exists' => 'Una de las imágenes seleccionadas para eliminar no es válida.',
            'ubicacion.max' => 'La ubicación no debe superar los 255 caracteres.',
            'latitud.numeric' => 'La latitud debe ser un número válido.',
            'latitud.between' => 'La latitud debe estar entre -90 y 90.',
            'longitud.numeric' => 'La longitud debe ser un número válido.',
            'longitud.between' => 'La longitud debe estar entre -180 y 180.',
            'departamento.max' => 'El departamento no debe superar los 255 caracteres.',
            'municipio.max' => 'El municipio no debe superar los 255 caracteres.',
            'provincia.max' => 'La provincia no debe superar los 255 caracteres.',
            'ciudad.max' => 'La ciudad no debe superar los 255 caracteres.',
        ];
    }
}
