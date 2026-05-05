<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGanadoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Asumimos que la autorización se maneja por middleware
    }

    public function rules(): array
    {
        return [
            // Identidad
            'nombre'             => 'required|string|max:255',
            'tipo_animal_id'     => 'required|exists:tipo_animals,id',
            'raza_id'            => 'nullable|exists:razas,id',
            'sexo'               => 'nullable|string|in:Macho,Hembra,Mixto',
            
            // Físico
            'edad_anos'          => 'required|integer|min:0|max:25',
            'edad_meses'         => 'required|integer|min:0|max:11',
            'peso_actual'        => 'nullable|numeric|min:0',
            
            // Lógica Comercial (NUEVO)
            'tipo_venta'         => 'required|string|in:lote,genetica',
            'tipo_precio'        => 'required|string|in:kilo_vivo,kilo_gancho,al_barrer,precio_fijo',
            'stock'              => 'required|integer|min:1',
            'precio'             => 'required|numeric|min:0',
            
            // Certificaciones Oficiales (NUEVO)
            'tiene_sanidad'      => 'nullable', // Viene como 'on' desde el checkbox
            'archivo_sanidad'    => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120', // Máx 5MB
            'es_campeon'         => 'nullable', // Viene como 'on' desde el checkbox
            'archivo_genetica'   => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',

            // Extra y Ubicación
            'descripcion'        => 'nullable|string|max:5000',
            'imagenes.*'         => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
            'ubicacion'          => 'nullable|string|max:255',
            'latitud'            => 'nullable|numeric|between:-90,90',
            'longitud'           => 'nullable|numeric|between:-180,180',
            'departamento'       => 'nullable|string|max:255',
            'municipio'          => 'nullable|string|max:255',
            'provincia'          => 'nullable|string|max:255',
            'ciudad'             => 'nullable|string|max:255',
        ];
    }
}