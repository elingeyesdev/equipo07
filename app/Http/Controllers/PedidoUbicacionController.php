<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\PedidoDetalle;
use App\Models\PedidoUbicacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PedidoUbicacionController extends Controller
{
    private const RADIO_LLEGADA_METROS = 100;

    public function transportista(PedidoDetalle $solicitud)
    {
        $this->authorizeTransportista($solicitud);

        if ($solicitud->estado_solicitud !== 'aceptada') {
            return redirect()
                ->route('vendedor.solicitudes.show', $solicitud)
                ->with('error', 'Primero debes aceptar la solicitud para iniciar el recorrido.');
        }

        $solicitud->load(['pedido.user']);

        return view('vendedor.solicitudes.tracking', compact('solicitud'));
    }

    public function store(Request $request, PedidoDetalle $solicitud)
    {
        $this->authorizeTransportista($solicitud);

        if ($solicitud->estado_solicitud !== 'aceptada') {
            abort(403);
        }

        $data = $request->validate([
            'latitud' => 'required|numeric|between:-90,90',
            'longitud' => 'required|numeric|between:-180,180',
            'precision_metros' => 'nullable|numeric|min:0|max:100000',
            'velocidad_m_s' => 'nullable|numeric|min:0|max:500',
            'rumbo_grados' => 'nullable|numeric|min:0|max:360',
            'tipo_recorrido' => 'nullable|in:entrega,devolucion',
        ]);

        $ubicacion = PedidoUbicacion::create([
            'pedido_id' => $solicitud->pedido_id,
            'pedido_detalle_id' => $solicitud->id,
            'user_id' => Auth::id(),
            'latitud' => $data['latitud'],
            'longitud' => $data['longitud'],
            'precision_metros' => $data['precision_metros'] ?? null,
            'velocidad_m_s' => $data['velocidad_m_s'] ?? null,
            'rumbo_grados' => $data['rumbo_grados'] ?? null,
            'tipo_recorrido' => $data['tipo_recorrido'] ?? 'entrega',
        ]);

        return response()->json([
            'ok' => true,
            'ubicacion' => $this->formatUbicacion($ubicacion),
        ]);
    }

    public function latest(Pedido $pedido)
    {
        if ((int) $pedido->user_id !== (int) Auth::id()) {
            if (Auth::user()?->isAdmin()) {
                return $this->latestResponse($pedido);
            }

            $isSeller = $pedido->detalles()
                ->where('vendedor_id', Auth::id())
                ->exists();
            $isTransportista = $pedido->detalles()
                ->where('transportista_id', Auth::id())
                ->exists();

            if (!$isSeller && !$isTransportista) {
                abort(403);
            }
        }

        return $this->latestResponse($pedido);
    }

    public function detalleLatest(PedidoDetalle $detalle)
    {
        $user = Auth::user();
        $compradorId = Pedido::whereKey($detalle->pedido_id)->value('user_id');
        $autorizado = (int) $compradorId === (int) Auth::id()
            || (int) $detalle->vendedor_id === (int) Auth::id()
            || (int) $detalle->transportista_id === (int) Auth::id()
            || $user?->isAdmin();

        if (!$autorizado) {
            abort(403);
        }

        $ubicacion = PedidoUbicacion::where('pedido_detalle_id', $detalle->id)
            ->latest('id')
            ->first();

        return response()->json([
            'ok' => true,
            'detalle_id' => $detalle->id,
            'estado' => $detalle->estado_transporte_actual,
            'estado_label' => $detalle->estado_transporte_label,
            'ubicacion' => $ubicacion ? $this->formatUbicacion($ubicacion) : null,
        ])->header('Cache-Control', 'no-store, no-cache, must-revalidate');
    }

    public function estadosPedido(Pedido $pedido)
    {
        if ((int) $pedido->user_id !== (int) Auth::id() && !Auth::user()?->isAdmin()) {
            abort(403);
        }

        $detalles = $pedido->detalles()
            ->whereIn('product_type', ['organico', 'ganado', 'maquinaria'])
            ->get()
            ->map(fn (PedidoDetalle $detalle) => [
                'detalle_id' => $detalle->id,
                'estado_solicitud' => $detalle->estado_solicitud,
                'estado' => $detalle->estado_transporte_actual,
                'estado_label' => $detalle->estado_transporte_label,
                'recepcion_confirmada' => (bool) $detalle->recepcion_confirmada_at,
                'motivo_cancelacion' => $detalle->cancelacion_motivo,
            ]);

        return response()->json([
            'ok' => true,
            'detalles' => $detalles,
        ])->header('Cache-Control', 'no-store, no-cache, must-revalidate');
    }

    private function latestResponse(Pedido $pedido)
    {
        $ubicacion = $pedido->ubicaciones()
            ->with('detalle')
            ->latest()
            ->first();

        return response()->json([
            'ok' => true,
            'ubicacion' => $ubicacion ? $this->formatUbicacion($ubicacion) : null,
        ]);
    }

    public function estadoDetalle(PedidoDetalle $detalle)
    {
        $detalle->load(['pedido', 'transportista']);
        $user = Auth::user();
        $isBuyer = (int) $detalle->pedido->user_id === (int) Auth::id();
        $isSeller = (int) $detalle->vendedor_id === (int) Auth::id();
        $isTransportista = (int) $detalle->transportista_id === (int) Auth::id();

        if (!$isBuyer && !$isSeller && !$isTransportista && !$user?->isAdmin()) {
            abort(403);
        }

        return response()->json([
            'ok' => true,
            'detalle_id' => $detalle->id,
            'pedido_estado' => $detalle->pedido->estado,
            'estado_solicitud' => $detalle->estado_solicitud,
            'estado_transporte' => $detalle->estado_transporte_actual,
            'estado_transporte_label' => $detalle->estado_transporte_label,
            'siguiente_estado' => $detalle->siguiente_estado_transporte,
            'siguiente_estado_label' => $detalle->siguiente_estado_transporte_label,
            'estado_alquiler' => $detalle->estado_alquiler_actual,
            'estado_alquiler_label' => $detalle->estado_alquiler_label,
            'recepcion_confirmada_at' => $detalle->recepcion_confirmada_at?->format('d/m/Y H:i'),
            'puede_confirmar_recepcion' => $isBuyer
                && $detalle->estado_solicitud === 'aceptada'
                && $detalle->estado_transporte_actual === 'esperando_confirmacion',
            'puede_finalizar_desde_vendedor' => $isSeller && $detalle->puede_finalizar_desde_vendedor,
        ]);
    }

    public function avanzarEstado(PedidoDetalle $solicitud)
    {
        $this->authorizeTransportista($solicitud);

        if ($solicitud->estado_solicitud !== 'aceptada') {
            if (request()->expectsJson()) {
                return response()->json([
                    'ok' => false,
                    'message' => 'Primero debes aceptar la solicitud para mover el transporte.',
                ], 422);
            }

            return back()->with('error', 'Primero debes aceptar la solicitud para mover el transporte.');
        }

        $siguienteEstado = $solicitud->siguiente_estado_transporte;

        if (!$siguienteEstado) {
            if (request()->expectsJson()) {
                return response()->json([
                    'ok' => false,
                    'message' => 'No hay un siguiente estado de transporte disponible.',
                ], 422);
            }

            return back()->with('error', 'No hay un siguiente estado de transporte disponible.');
        }

        if ($siguienteEstado === 'esperando_confirmacion') {
            request()->validate([
                'firma_transportista' => ['required', 'string', 'starts_with:data:image/png;base64,'],
            ], [
                'firma_transportista.required' => 'La firma del transportista es obligatoria para registrar la entrega.',
            ]);
        }

        $errorDistancia = $this->validarDistanciaParaEstado($solicitud, $siguienteEstado);

        if ($errorDistancia) {
            if (request()->expectsJson()) {
                return response()->json([
                    'ok' => false,
                    'message' => $errorDistancia,
                ], 422);
            }

            return back()->with('error', $errorDistancia);
        }

        $update = [
            'estado_transporte' => $siguienteEstado,
        ];

        if ($siguienteEstado === 'esperando_confirmacion') {
            $update['firma_transportista'] = request('firma_transportista');
            $update['firma_transportista_at'] = now();
        }

        $solicitud->update($update);

        $this->sincronizarEstadoPedido($solicitud->fresh(), $siguienteEstado);

        $solicitud = $solicitud->fresh();

        if (request()->expectsJson()) {
            return response()->json([
                'ok' => true,
                'message' => 'Estado de transporte actualizado a: ' . PedidoDetalle::transporteEstados()[$siguienteEstado] . '.',
                'estado' => $solicitud->estado_transporte_actual,
                'estado_label' => $solicitud->estado_transporte_label,
                'siguiente_estado' => $solicitud->siguiente_estado_transporte,
                'siguiente_estado_label' => $solicitud->siguiente_estado_transporte_label,
            ]);
        }

        return back()->with('success', 'Estado de transporte actualizado a: ' . PedidoDetalle::transporteEstados()[$siguienteEstado] . '.');
    }

    private function sincronizarEstadoPedido(PedidoDetalle $solicitud, string $estado): void
    {
        if (in_array($estado, ['en_camino_recogida', 'producto_recogido', 'en_camino_entrega', 'llego_destino', 'esperando_confirmacion'], true)) {
            $solicitud->pedido()->update(['estado' => $estado]);
        }

        if ($solicitud->es_alquiler_maquinaria) {
            if ($estado === 'en_camino_entrega') {
                $solicitud->update(['estado_alquiler' => 'en_camino_entrega']);
            } elseif ($estado === 'llego_destino') {
                $solicitud->update(['estado_alquiler' => 'entregado']);
            } elseif ($estado === 'devolucion_solicitada') {
                $solicitud->update(['estado_alquiler' => 'en_uso']);
            } elseif ($estado === 'en_camino_retorno') {
                $solicitud->update(['estado_alquiler' => 'en_camino_retorno']);
                $solicitud->pedido()->update(['estado' => 'en_camino_retorno']);
            } elseif ($estado === 'devuelto_vendedor') {
                $solicitud->update(['estado_alquiler' => 'devuelto']);
                $solicitud->pedido()->update(['estado' => 'devuelto']);
            }
        }
    }

    private function validarDistanciaParaEstado(PedidoDetalle $solicitud, string $estado): ?string
    {
        $target = $this->targetParaEstado($solicitud, $estado);

        if (!$target) {
            return null;
        }

        $ubicacion = $solicitud->ultimaUbicacion;

        if (!$ubicacion) {
            return 'Primero inicia el GPS y espera a que se envie tu ubicacion actual.';
        }

        $distancia = $this->distanciaMetros(
            (float) $ubicacion->latitud,
            (float) $ubicacion->longitud,
            $target['latitud'],
            $target['longitud']
        );

        if ($distancia > self::RADIO_LLEGADA_METROS) {
            return 'No puedes marcar "' . PedidoDetalle::transporteEstados()[$estado] . '". Estas a '
                . number_format($distancia, 0) . ' m de ' . $target['label']
                . '. Debes estar a ' . self::RADIO_LLEGADA_METROS . ' m o menos.';
        }

        return null;
    }

    private function targetParaEstado(PedidoDetalle $solicitud, string $estado): ?array
    {
        if (in_array($estado, ['llego_recogida', 'llego_retorno'], true)) {
            return $this->coordsObjetivo(
                $solicitud->product_latitud,
                $solicitud->product_longitud,
                'el punto del producto o retorno'
            );
        }

        if (in_array($estado, ['llego_destino', 'llego_recoger_devolucion'], true)) {
            return $this->coordsObjetivo(
                $solicitud->pedido?->destino_latitud,
                $solicitud->pedido?->destino_longitud,
                'el destino del comprador'
            );
        }

        return null;
    }

    private function coordsObjetivo($latitud, $longitud, string $label): ?array
    {
        if (!$latitud || !$longitud) {
            return null;
        }

        return [
            'latitud' => (float) $latitud,
            'longitud' => (float) $longitud,
            'label' => $label,
        ];
    }

    private function distanciaMetros(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $earthRadiusM = 6371000;
        $latDelta = deg2rad($lat2 - $lat1);
        $lngDelta = deg2rad($lng2 - $lng1);
        $originLat = deg2rad($lat1);
        $targetLat = deg2rad($lat2);

        $a = sin($latDelta / 2) ** 2
            + cos($originLat) * cos($targetLat) * sin($lngDelta / 2) ** 2;

        return $earthRadiusM * 2 * atan2(sqrt($a), sqrt(1 - $a));
    }

    private function authorizeTransportista(PedidoDetalle $solicitud): void
    {
        if ((int) $solicitud->transportista_id !== (int) Auth::id() && !Auth::user()?->isAdmin()) {
            abort(403);
        }
    }

    private function formatUbicacion(PedidoUbicacion $ubicacion): array
    {
        return [
            'latitud' => (float) $ubicacion->latitud,
            'longitud' => (float) $ubicacion->longitud,
            'precision_metros' => $ubicacion->precision_metros !== null ? (float) $ubicacion->precision_metros : null,
            'velocidad_m_s' => $ubicacion->velocidad_m_s !== null ? (float) $ubicacion->velocidad_m_s : null,
            'rumbo_grados' => $ubicacion->rumbo_grados !== null ? (float) $ubicacion->rumbo_grados : null,
            'tipo_recorrido' => $ubicacion->tipo_recorrido,
            'producto' => $ubicacion->detalle?->nombre_producto,
            'estado_transporte' => $ubicacion->detalle?->estado_transporte_actual,
            'estado_transporte_label' => $ubicacion->detalle?->estado_transporte_label,
            'fecha' => $ubicacion->created_at?->toIso8601String(),
            'fecha_humana' => $ubicacion->created_at?->format('d/m/Y H:i:s'),
        ];
    }
}
