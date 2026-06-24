<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use Closure;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:255', 'regex:/^[\pL\s.\'-]+$/u'],
            'email' => 'required|string|email:rfc,dns|max:255|unique:users',
            'password' => [
                'required',
                'string',
                'confirmed',
                Password::min(8),
                function (string $attribute, mixed $value, Closure $fail): void {
                    $password = strtolower(trim((string) $value));
                    $name = strtolower(trim((string) $this->input('name')));
                    $email = strtolower(trim((string) $this->input('email')));

                    if ($password !== '' && ($password === $name || $password === $email)) {
                        $fail('La contraseña no puede ser igual al nombre ni al correo electrónico.');
                    }
                },
            ],
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
            'name.required' => 'El nombre es obligatorio.',
            'name.min' => 'El nombre debe tener al menos 3 caracteres.',
            'name.max' => 'El nombre no puede exceder 255 caracteres.',
            'name.regex' => 'El nombre solo puede contener letras, espacios, puntos, guiones o apóstrofes.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Debe ser un correo electrónico válido con un dominio existente.',
            'email.max' => 'El correo no puede exceder 255 caracteres.',
            'email.unique' => 'Este correo electrónico ya está registrado.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ];
    }
}
