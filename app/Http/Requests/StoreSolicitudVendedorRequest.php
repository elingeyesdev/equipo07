<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreSolicitudVendedorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'motivo' => 'required|string|min:10|max:1000',
            'telefono' => 'required|string|max:20|regex:/^[0-9+\-\s()]+$/',
            'direccion' => 'required|string|max:255',
            'documento' => 'nullable|string|max:255',
            'archivo_documento' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120', // 5MB
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'motivo.required' => 'El motivo de la solicitud es obligatorio.',
            'motivo.min' => 'El motivo debe tener al menos 10 caracteres.',
            'motivo.max' => 'El motivo no puede exceder 1000 caracteres.',
            'telefono.required' => 'El teléfono es obligatorio.',
            'telefono.max' => 'El teléfono no puede exceder 20 caracteres.',
            'telefono.regex' => 'El teléfono solo puede contener números, espacios, guiones y paréntesis.',
            'direccion.required' => 'La dirección es obligatoria.',
            'direccion.max' => 'La dirección no puede exceder 255 caracteres.',
            'documento.max' => 'El documento no puede exceder 255 caracteres.',
            'archivo_documento.file' => 'El archivo debe ser un archivo válido.',
            'archivo_documento.mimes' => 'El archivo debe ser de tipo: pdf, doc, docx, jpg, jpeg o png.',
            'archivo_documento.max' => 'El archivo no puede exceder 5MB.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'motivo' => 'motivo de la solicitud',
            'telefono' => 'teléfono',
            'direccion' => 'dirección',
            'documento' => 'documento',
            'archivo_documento' => 'archivo adjunto',
        ];
    }
}
