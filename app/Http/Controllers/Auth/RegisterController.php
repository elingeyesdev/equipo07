<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    /**
     * Mostrar formulario de registro
     */
    public function showRegisterForm()
    {
        // Si ya está autenticado, redirigir
        if (Auth::check()) {
            return redirect()->route('home');
        }

        return view('public.auth.register');
    }

    /**
     * Procesar registro
     */
    public function register(RegisterRequest $request)
    {
        try {
            // Obtener rol de cliente por defecto
            $clienteRole = Role::where('nombre', 'cliente')->first();

            if (!$clienteRole) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'email' => 'Error en la configuración del sistema. Contacta al administrador.',
                    ]);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => $clienteRole->id, // Asignar rol cliente por defecto
            ]);

            // Autenticar automáticamente al usuario
            Auth::login($user);

            $request->session()->regenerate();

            return redirect()->route('home')
                ->with('success', '¡Cuenta creada exitosamente! Bienvenido a AgroVida.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors([
                    'email' => 'Hubo un error al crear la cuenta. Por favor, intenta nuevamente.',
                ]);
        }
    }
}
