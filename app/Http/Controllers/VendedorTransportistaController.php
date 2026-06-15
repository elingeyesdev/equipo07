<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
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
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'telefono' => 'nullable|string|max:30',
            'password' => 'required|string|min:6|confirmed',
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
