<?php

namespace App\Http\Controllers;

use App\Models\SolicitudVendedor;
use App\Models\User;
use App\Models\Role;
use App\Http\Requests\StoreSolicitudVendedorRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SolicitudVendedorController extends Controller
{
    /**
     * Mostrar formulario de solicitud (Cliente)
     */
    public function create()
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Debes iniciar sesión para enviar una solicitud.');
        }

        $user = Auth::user();

        // Verificar si ya es vendedor o admin
        if ($user->isVendedor() || $user->isAdmin()) {
            return redirect()->route('home')
                ->with('info', 'Ya tienes permisos de vendedor. No necesitas enviar una solicitud.');
        }

        // Verificar si el usuario ya tiene una solicitud pendiente
        $solicitudPendiente = $user->solicitudPendiente();
        if ($solicitudPendiente) {
            return view('solicitudes_vendedor.create')
                ->with('info', 'Ya tienes una solicitud pendiente. Espera la respuesta del administrador.');
        }

        return view('solicitudes_vendedor.create');
    }

    /**
     * Guardar solicitud (Cliente)
     */
    public function store(StoreSolicitudVendedorRequest $request)
    {
        $user = Auth::user();

        // Verificar si ya tiene una solicitud pendiente
        $solicitudPendiente = $user->solicitudPendiente();
        if ($solicitudPendiente) {
            return redirect()->route('solicitar-vendedor')
                ->with('error', 'Ya tienes una solicitud pendiente. No puedes enviar otra hasta que sea procesada.')
                ->withInput();
        }

        // Verificar si el usuario ya es vendedor o admin
        if ($user->isVendedor() || $user->isAdmin()) {
            return redirect()->route('solicitar-vendedor')
                ->with('info', 'Ya tienes permisos de vendedor. No necesitas enviar una solicitud.')
                ->withInput();
        }

        $data = [
            'user_id' => $user->id,
            'motivo' => $request->validated()['motivo'],
            'telefono' => $request->validated()['telefono'],
            'direccion' => $request->validated()['direccion'],
            'documento' => $request->validated()['documento'] ?? null,
            'estado' => 'pendiente',
        ];

        // Guardar archivo si se subió
        if ($request->hasFile('archivo_documento')) {
            $data['archivo_documento'] = $request->file('archivo_documento')
                ->store('solicitudes_vendedor', 'public');
        }

        try {
            SolicitudVendedor::create($data);

            return redirect()->route('solicitar-vendedor')
                ->with('success', 'Tu solicitud ha sido enviada correctamente. El administrador la revisará pronto.');
        } catch (\Exception $e) {
            return redirect()->route('solicitar-vendedor')
                ->with('error', 'Hubo un error al guardar tu solicitud. Por favor, intenta nuevamente.')
                ->withInput();
        }
    }

    /**
     * Listar solicitudes (Admin)
     */
    public function index(Request $request)
    {
        $query = SolicitudVendedor::with('user');

        // Filtro por estado
        if ($request->has('estado') && $request->estado !== '') {
            $query->where('estado', $request->estado);
        }

        // Ordenar por fecha más reciente
        $solicitudes = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('solicitudes_vendedor.index', compact('solicitudes'));
    }

    /**
     * Mostrar detalle de solicitud (Admin)
     */
    public function show(SolicitudVendedor $solicitudVendedor)
    {
        $solicitudVendedor->load('user');
        return view('solicitudes_vendedor.show', compact('solicitudVendedor'));
    }

    /**
     * Aprobar solicitud (Admin)
     */
    public function aprobar($id)
    {
        try {
            $solicitud = SolicitudVendedor::with('user')->findOrFail($id);

            // Validar que no esté ya aprobada o rechazada
            if ($solicitud->estado !== 'pendiente') {
                return redirect()->route('admin.solicitudes-vendedor.index')
                    ->with('error', 'Esta solicitud ya ha sido procesada.');
            }

            // Cambiar estado
            $solicitud->estado = 'aprobada';
            $solicitud->save();

            // Cambiar rol del usuario a vendedor
            $user = $solicitud->user;
            if ($user) {
                $vendedorRole = Role::where('nombre', 'vendedor')->first();
                if ($vendedorRole) {
                    $user->role_id = $vendedorRole->id;
                    $user->save();
                }
            }

            // Registrar fecha de revisión
            $solicitud->fecha_revision_admin = now();
            $solicitud->save();

            return redirect()->route('admin.solicitudes-vendedor.index')
                ->with('success', "Solicitud aprobada. El usuario {$user->name} ahora tiene rol de vendedor.");
        } catch (\Exception $e) {
            return redirect()->route('admin.solicitudes-vendedor.index')
                ->with('error', 'Hubo un error al aprobar la solicitud: ' . $e->getMessage());
        }
    }

    /**
     * Rechazar solicitud (Admin)
     */
    public function rechazar($id)
    {
        try {
            $solicitud = SolicitudVendedor::with('user')->findOrFail($id);

            // Validar que no esté ya aprobada o rechazada
            if ($solicitud->estado !== 'pendiente') {
                return redirect()->route('admin.solicitudes-vendedor.index')
                    ->with('error', 'Esta solicitud ya ha sido procesada.');
            }

            // Cambiar estado
            $solicitud->estado = 'rechazada';
            $solicitud->fecha_revision_admin = now();
            $solicitud->save();

            $userName = $solicitud->user ? $solicitud->user->name : 'Usuario';
            return redirect()->route('admin.solicitudes-vendedor.index')
                ->with('success', "Solicitud de {$userName} rechazada correctamente.");
        } catch (\Exception $e) {
            return redirect()->route('admin.solicitudes-vendedor.index')
                ->with('error', 'Hubo un error al rechazar la solicitud: ' . $e->getMessage());
        }
    }
}
