<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrganicoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre'             => 'required|string|max:255',
            'categoria_id'       => 'required|exists:categorias,id',
            'unidad_id'          => 'nullable|exists:unidades_organicos,id',
            'precio'             => 'required|numeric|min:0',
            'stock'              => 'required|integer|min:0',
            'fecha_cosecha'      => 'required|date|after_or_equal:fecha_siembra',
            'descripcion'        => 'nullable|string|max:5000',
            'origen'             => 'required|string|max:255',
            'latitud_origen'     => 'nullable|numeric|between:-90,90',
            'longitud_origen'    => 'nullable|numeric|between:-180,180',
            'imagenes.*'         => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
            'imagenes_eliminar.*' => 'nullable|exists:organico_imagenes,id',
            'tipo_cultivo_id'    => ['required', 'exists:tipo_cultivos,id'],
            'finca'              => 'required|string|max:255',
            'ubicacion'          => 'required|string|max:255',
            'fecha_siembra'      => 'required|date|before_or_equal:today',
            'tratamientos_utilizados' => 'required|string|max:5000',
            'certificaciones'    => 'required|string|max:5000',
            'observaciones'      => 'nullable|string|max:5000',
        ];
    }

    public function messages(): array
    {
        return [
            'categoria_id.required'    => 'La categoría es obligatoria.',
            'categoria_id.exists'      => 'La categoría seleccionada no es válida.',
            'unidad_id.exists'         => 'La unidad seleccionada no es válida.',
            'precio.required'          => 'El precio es obligatorio.',
            'stock.required'           => 'El stock es obligatorio.',
            'imagenes.*.image'         => 'Los archivos deben ser imágenes válidas.',
            'imagenes.*.mimes'         => 'Las imágenes deben ser de tipo: jpeg, jpg, png o gif.',
            'imagenes.*.max'           => 'Cada imagen no debe superar los 2MB.',
            'tipo_cultivo_id.required' => 'El tipo de cultivo es obligatorio.',
            'tipo_cultivo_id.exists'   => 'El tipo de cultivo seleccionado no es válido.',
            'origen.required'          => 'El origen es obligatorio para la trazabilidad.',
            'finca.required'           => 'La finca es obligatoria para la trazabilidad.',
            'ubicacion.required'       => 'La ubicación es obligatoria para la trazabilidad.',
            'fecha_siembra.required'   => 'La fecha de siembra es obligatoria.',
            'fecha_siembra.before_or_equal' => 'La fecha de siembra no puede ser futura.',
            'fecha_cosecha.required'   => 'La fecha de cosecha es obligatoria.',
            'tratamientos_utilizados.required' => 'Los procesos o tratamientos son obligatorios.',
            'certificaciones.required' => 'Las certificaciones son obligatorias.',
        ];
    }
}
