<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendedorTransportistaController extends Controller
{
    public function index(Request $request)
    {
        $query = User::withCount('transportistaEnvios')
            ->where('transportista_creado_por_id', Auth::id())
            ->whereHas('role', function ($role) {
                $role->where('nombre', Role::TRANSPORTISTA);
            })
            ->orderBy('name');

        if ($request->filled('q')) {
            $q = $request->q;

            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('telefono', 'like', "%{$q}%");
            });
        }

        $transportistas = $query->paginate(10)->withQueryString();

        return view('vendedor.transportistas.index', compact('transportistas'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:255', 'regex:/^[\pL\s.\'-]+$/u'],
            'email' => 'required|email:rfc,dns|max:255|unique:users,email',
            'telefono' => ['nullable', 'string', 'max:18', 'regex:/^(?:\+?591[\s-]?)?[0-9](?:[\s-]?[0-9]){6,7}$/'],
            'password' => [
                'required',
                'string',
                'min:8',
                'max:72',
                'confirmed',
                function (string $attribute, mixed $value, Closure $fail) use ($request): void {
                    $password = strtolower(trim((string) $value));
                    $name = strtolower(trim((string) $request->input('name')));
                    $email = strtolower(trim((string) $request->input('email')));

                    if ($password !== '' && ($password === $name || $password === $email)) {
                        $fail('La contraseña no puede ser igual al nombre ni al correo electrónico.');
                    }
                },
            ],
        ], [
            'name.min' => 'El nombre debe tener al menos 3 caracteres.',
            'name.regex' => 'El nombre solo puede contener letras, espacios, puntos, guiones o apóstrofes.',
            'email.email' => 'Debe ser un correo electrónico válido con un dominio existente.',
            'telefono.regex' => 'El teléfono debe ser un número boliviano válido.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.max' => 'La contraseña no puede exceder 72 caracteres.',
        ]);

        $role = Role::where('nombre', Role::TRANSPORTISTA)->firstOrFail();

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'telefono' => $data['telefono'] ?? null,
            'password' => $data['password'],
            'role_id' => $role->id,
            'transportista_creado_por_id' => Auth::id(),
        ]);

        return back()->with('success', 'Transportista creado correctamente. Ya puedes asignarlo a un envio.');
    }
}
