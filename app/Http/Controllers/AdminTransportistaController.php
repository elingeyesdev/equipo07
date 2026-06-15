<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class AdminTransportistaController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['role', 'transportistaCreador'])
            ->whereHas('role', function ($role) {
                $role->where('nombre', Role::TRANSPORTISTA);
            })
            ->orderBy('name');

        if ($request->filled('q')) {
            $q = $request->q;

            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%");
            });
        }

        $usuarios = $query->paginate(12)->withQueryString();

        return view('admin.transportistas.index', compact('usuarios'));
    }
}
