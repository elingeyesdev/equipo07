<?php

namespace App\Http\Controllers;

use App\Models\PedidoUbicacion;
use App\Services\TransporteAccesoService;
use Illuminate\Http\Request;

class TransportePublicoController extends Controller
{
    public function index(Request $request)
    {
        if ($request->session()->has('transporte_acceso_id')) {
            return redirect()->route('transporte.envio');
        }

        return response()
            ->view('transporte.index')
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    public function acceder(Request $request, TransporteAccesoService $service)
    {
        $data = $request->validate([
            'codigo' => ['required', 'string', 'max:30'],
        ], [
            'codigo.required' => 'Ingresa el codigo entregado por el vendedor.',
        ]);

        $acceso = $service->buscarActivo($data['codigo']);

        if (! $acceso) {
            return back()
                ->withInput()
                ->withErrors(['codigo' => 'El codigo no es valido, vencio o fue revocado.']);
        }

        $request->session()->regenerate();
        $request->session()->put('transporte_acceso_id', $acceso->id);
        $acceso->update(['last_access_at' => now()]);

        return redirect()->route('transporte.envio');
    }

    public function envio(Request $request, TransporteAccesoService $service)
    {
        $acceso = $request->attributes->get('transporteAcceso');
        $acceso->load([
            'detalle.pedido.user',
            'detalle.vendedor',
            'detalle.organico',
            'detalle.ganado',
            'detalle.maquinaria',
            'detalle.ultimaUbicacion',
            'detalle.transporteEventos' => fn ($query) => $query->latest('created_at')->limit(12),
            'detalles' => fn ($query) => $query
                ->where('estado_solicitud', 'aceptada')
                ->with(['organico', 'ganado', 'maquinaria'])
                ->orderBy('id'),
        ]);

        $detalle = $acceso->detalle;
        $detallesEnvio = $acceso->detalles;
        $siguienteEstado = $service->siguienteEstado($detalle);
        $estadoLabels = $service->estadosPara($detalle);
        $faseLabels = $service->fasesPara($detalle);
        $flujo = $service->flujoVisiblePara($detalle);
        $estadoFases = $detalle->es_alquiler_maquinaria
            ? \App\Models\PedidoDetalle::transporteEstadoFases()
            : [];
        $siguienteEstadoLabel = $siguienteEstado
            ? ($faseLabels[$estadoFases[$siguienteEstado] ?? $siguienteEstado]
                ?? $estadoLabels[$siguienteEstado]
                ?? ucfirst(str_replace('_', ' ', $siguienteEstado)))
            : null;
        $puedeActivarGps = $service->puedeActivarGps($detalle);

        return response()
            ->view('transporte.envio', compact(
                'acceso',
                'detalle',
                'detallesEnvio',
                'siguienteEstado',
                'estadoLabels',
                'faseLabels',
                'flujo',
                'estadoFases',
                'siguienteEstadoLabel',
                'puedeActivarGps'
            ))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    public function ubicacion(Request $request, TransporteAccesoService $service)
    {
        $acceso = $request->attributes->get('transporteAcceso');
        $detalle = $acceso->detalle;

        if (! $service->puedeActivarGps($detalle)) {
            return response()->json([
                'ok' => false,
                'message' => 'El transporte todavia no esta habilitado para compartir ubicacion.',
            ], 422);
        }

        $data = $request->validate([
            'latitud' => ['required', 'numeric', 'between:-90,90'],
            'longitud' => ['required', 'numeric', 'between:-180,180'],
            'precision_metros' => ['nullable', 'numeric', 'min:0', 'max:100000'],
            'velocidad_m_s' => ['nullable', 'numeric', 'min:0', 'max:500'],
            'rumbo_grados' => ['nullable', 'numeric', 'min:0', 'max:360'],
        ]);

        $ubicacion = null;
        $detallesEnvio = $acceso->detalles()
            ->where('estado_solicitud', 'aceptada')
            ->get();

        foreach ($detallesEnvio as $detalleEnvio) {
            $ubicacion = PedidoUbicacion::create([
                'pedido_id' => $detalleEnvio->pedido_id,
                'pedido_detalle_id' => $detalleEnvio->id,
                'user_id' => null,
                'transporte_acceso_id' => $acceso->id,
                'latitud' => $data['latitud'],
                'longitud' => $data['longitud'],
                'precision_metros' => $data['precision_metros'] ?? null,
                'velocidad_m_s' => $data['velocidad_m_s'] ?? null,
                'rumbo_grados' => $data['rumbo_grados'] ?? null,
                'tipo_recorrido' => $service->tipoRecorrido($detalleEnvio),
            ]);
        }

        return response()->json([
            'ok' => true,
            'ubicacion' => [
                'latitud' => (float) $ubicacion->latitud,
                'longitud' => (float) $ubicacion->longitud,
                'precision_metros' => $ubicacion->precision_metros !== null
                    ? (float) $ubicacion->precision_metros
                    : null,
                'fecha' => $ubicacion->created_at?->format('d/m/Y H:i:s'),
            ],
        ]);
    }

    public function estado(Request $request, TransporteAccesoService $service)
    {
        $data = $request->validate([
            'accion' => ['required', 'in:avanzar,cancelar'],
            'motivo_cancelacion' => ['required_if:accion,cancelar', 'nullable', 'string', 'min:10', 'max:1000'],
        ]);

        $acceso = $request->attributes->get('transporteAcceso');
        $detalle = $service->avanzar($acceso, $data['accion'], $data['motivo_cancelacion'] ?? null);

        return response()->json([
            'ok' => true,
            'message' => 'Estado actualizado correctamente.',
            'estado' => $detalle->estado_transporte_actual,
            'estado_label' => $detalle->estado_transporte_label,
            'siguiente_estado' => $service->siguienteEstado($detalle),
            'siguiente_estado_label' => $this->siguienteEstadoVisibleLabel($detalle, $service),
            'motivo_cancelacion' => $detalle->cancelacion_motivo,
            'puede_activar_gps' => $service->puedeActivarGps($detalle),
        ]);
    }

    public function actualizacion(Request $request, TransporteAccesoService $service)
    {
        $acceso = $request->attributes->get('transporteAcceso');
        $detalle = $acceso->detalle()->with('ultimaUbicacion')->firstOrFail();
        $ubicacion = $detalle->ultimaUbicacion;

        return response()->json([
            'ok' => true,
            'estado' => $detalle->estado_transporte_actual,
            'estado_label' => $detalle->estado_transporte_label,
            'siguiente_estado' => $service->siguienteEstado($detalle),
            'siguiente_estado_label' => $this->siguienteEstadoVisibleLabel($detalle, $service),
            'motivo_cancelacion' => $detalle->cancelacion_motivo,
            'puede_activar_gps' => $service->puedeActivarGps($detalle),
            'ubicacion' => $ubicacion ? [
                'latitud' => (float) $ubicacion->latitud,
                'longitud' => (float) $ubicacion->longitud,
                'precision_metros' => $ubicacion->precision_metros !== null
                    ? (float) $ubicacion->precision_metros
                    : null,
                'fecha_humana' => $ubicacion->created_at?->format('d/m/Y H:i:s'),
            ] : null,
        ]);
    }

    public function salir(Request $request)
    {
        $request->session()->forget('transporte_acceso_id');
        $request->session()->regenerateToken();

        return redirect()->route('transporte.index');
    }

    private function siguienteEstadoVisibleLabel($detalle, TransporteAccesoService $service): ?string
    {
        $siguiente = $service->siguienteEstado($detalle);

        if (! $siguiente) {
            return null;
        }

        $estadoLabels = $service->estadosPara($detalle);
        $faseLabels = $service->fasesPara($detalle);
        $estadoFases = $detalle->es_alquiler_maquinaria
            ? \App\Models\PedidoDetalle::transporteEstadoFases()
            : [];

        return $faseLabels[$estadoFases[$siguiente] ?? $siguiente]
            ?? $estadoLabels[$siguiente]
            ?? ucfirst(str_replace('_', ' ', $siguiente));
    }
}
