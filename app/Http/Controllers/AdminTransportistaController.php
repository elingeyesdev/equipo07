<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class AdminTransportistaController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('role')
            ->where(function ($sub) {
                $sub->whereDoesntHave('role')
                    ->orWhereHas('role', function ($role) {
                        $role->where('nombre', '!=', Role::ADMIN);
                    });
            })
            ->orderBy('name');

        if ($request->filled('q')) {
            $q = $request->q;

            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%");
            });
        }

        if ($request->filled('rol')) {
            if ($request->rol === 'sin_rol') {
                $query->whereNull('role_id');
            } else {
                $query->whereHas('role', function ($role) use ($request) {
                    $role->where('nombre', $request->rol);
                });
            }
        }

        $usuarios = $query->paginate(12)->withQueryString();

        return view('admin.transportistas.index', compact('usuarios'));
    }

    public function hacerTransportista(User $usuario)
    {
        if ($usuario->isAdmin()) {
            return back()->with('error', 'No puedes cambiar el rol de un administrador.');
        }

        $role = Role::where('nombre', Role::TRANSPORTISTA)->firstOrFail();

        $usuario->update([
            'role_id' => $role->id,
        ]);

        return back()->with('success', $usuario->name . ' ahora tiene rol transportista.');
    }

    public function quitarTransportista(User $usuario)
    {
        if (!$usuario->isTransportista()) {
            return back()->with('error', 'Este usuario no tiene rol transportista.');
        }

        $role = Role::where('nombre', Role::CLIENTE)->firstOrFail();

        $usuario->update([
            'role_id' => $role->id,
        ]);

        return back()->with('success', $usuario->name . ' volvio al rol cliente.');
    }
}
