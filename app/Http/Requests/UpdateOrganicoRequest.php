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
            'nombre' => 'required|string|max:255',
            'categoria_id' => 'required|exists:categorias,id',
            'unidad_id' => 'nullable|exists:unidades_organicos,id',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'fecha_cosecha' => 'nullable|date',
            'descripcion' => 'nullable|string|max:5000',
            'tipo_cultivo_id' => ['required', 'exists:tipo_cultivos,id'],

            'origen' => 'nullable|string|max:255',
            'latitud_origen' => 'nullable|numeric|between:-90,90',
            'longitud_origen' => 'nullable|numeric|between:-180,180',
            'departamento_origen' => 'nullable|string|max:255',
            'municipio_origen' => 'nullable|string|max:255',
            'provincia_origen' => 'nullable|string|max:255',
            'ciudad_origen' => 'nullable|string|max:255',
            'referencia_ubicacion' => 'nullable|string|max:255',

            'finca' => 'nullable|string|max:255',
            'fecha_siembra' => 'nullable|date',
            'tratamientos_utilizados' => 'nullable|string|max:5000',
            'observaciones_trazabilidad' => 'nullable|string|max:5000',

            'certificados' => 'nullable|array',
            'certificados.*.incluido' => 'nullable|boolean',
            'certificados.*.sin_certificado' => 'nullable|boolean',
            'certificados.*.archivo' => 'nullable|file|mimes:pdf,jpeg,jpg,png|max:4096',
            'certificados.*.fecha_emision' => 'nullable|date',
            'certificados.*.fecha_vencimiento' => 'nullable|date',
            'certificados.*.observaciones' => 'nullable|string|max:1000',
            'certificados_adicionales' => 'nullable|array',
            'certificados_adicionales.*.nombre' => 'nullable|string|max:255',
            'certificados_adicionales.*.archivo' => 'nullable|file|mimes:pdf,jpeg,jpg,png|max:4096',
            'certificados_adicionales.*.fecha_emision' => 'nullable|date',
            'certificados_adicionales.*.fecha_vencimiento' => 'nullable|date',
            'certificados_adicionales.*.observaciones' => 'nullable|string|max:1000',

            'imagenes.*' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
            'imagenes_eliminar.*' => 'nullable|exists:organico_imagenes,id',
        ];
    }

    public function messages(): array
    {
        return [
            'categoria_id.required' => 'La categoria es obligatoria.',
            'categoria_id.exists' => 'La categoria seleccionada no es valida.',
            'unidad_id.exists' => 'La unidad seleccionada no es valida.',
            'precio.required' => 'El precio es obligatorio.',
            'stock.required' => 'El stock es obligatorio.',
            'imagenes.*.image' => 'Los archivos deben ser imagenes validas.',
            'imagenes.*.mimes' => 'Las imagenes deben ser de tipo: jpeg, jpg, png o gif.',
            'imagenes.*.max' => 'Cada imagen no debe superar los 2MB.',
            'certificados.*.archivo.mimes' => 'Los certificados deben ser PDF o imagen.',
            'certificados.*.archivo.max' => 'Cada certificado no debe superar los 4MB.',
            'certificados_adicionales.*.archivo.mimes' => 'Los certificados adicionales deben ser PDF o imagen.',
            'certificados_adicionales.*.archivo.max' => 'Cada certificado adicional no debe superar los 4MB.',
            'tipo_cultivo_id.required' => 'El tipo de cultivo es obligatorio.',
            'tipo_cultivo_id.exists' => 'El tipo de cultivo seleccionado no es valido.',
        ];
    }

}
