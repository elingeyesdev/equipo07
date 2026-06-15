<?php

namespace App\Services;

use App\Models\PedidoDetalle;
use App\Models\TransporteAcceso;
use App\Models\TransporteEvento;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class TransporteAccesoService
{
    public const ESTADOS_ORGANICO = [
        'aceptado' => 'Aceptado',
        'preparando' => 'Preparando',
        'en_camino_entrega' => 'En camino',
        'esperando_confirmacion' => 'Entregado, esperando confirmacion',
        'entregado' => 'Finalizado',
        'cancelado' => 'Cancelado',
    ];

    public function generar(PedidoDetalle $detalle, ?int $createdBy = null, bool $regenerar = false): TransporteAcceso
    {
        $this->validarDetalleTransportable($detalle);

        return DB::transaction(function () use ($detalle, $createdBy, $regenerar) {
            $existente = TransporteAcceso::where('grupo_envio', $detalle->grupo_envio)
                ->lockForUpdate()
                ->first();

            if ($existente && ! $regenerar && $existente->estaActivo()) {
                return $existente;
            }

            $codigo = $this->crearCodigo();
            $data = [
                'created_by' => $createdBy,
                'codigo_hash' => self::hashCodigo($codigo),
                'codigo_cifrado' => $codigo,
                'estado' => TransporteAcceso::ESTADO_ACTIVO,
                'expires_at' => now()->addDays(config('transporte.codigo_dias_vigencia', 7)),
                'last_access_at' => null,
            ];

            if ($existente) {
                $existente->update($data);

                return $existente->fresh();
            }

            return TransporteAcceso::create([
                'pedido_detalle_id' => $detalle->id,
                'grupo_envio' => $detalle->grupo_envio,
                ...$data,
            ]);
        });
    }

    public function buscarActivo(string $codigo): ?TransporteAcceso
    {
        $acceso = TransporteAcceso::with([
            'detalle.pedido.user',
            'detalle.vendedor',
            'detalle.organico',
            'detalle.ganado',
            'detalle.maquinaria',
            'detalles.organico',
            'detalles.ganado',
            'detalles.maquinaria',
        ])->where('codigo_hash', self::hashCodigo($codigo))->first();

        if (! $acceso || ! $acceso->estaActivo()) {
            return null;
        }

        if (! $this->esTransportable($acceso->detalle)) {
            return null;
        }

        return $acceso;
    }

    public function revocar(TransporteAcceso $acceso): void
    {
        $acceso->update(['estado' => TransporteAcceso::ESTADO_REVOCADO]);
    }

    public function siguienteEstado(PedidoDetalle $detalle): ?string
    {
        if ($detalle->es_alquiler_maquinaria) {
            return match ($detalle->estado_transporte_actual) {
                'asignado' => 'en_camino_recogida',
                'en_camino_recogida' => 'producto_recogido',
                'producto_recogido' => 'en_camino_entrega',
                'en_camino_entrega' => 'llego_destino',
                'llego_destino' => 'esperando_confirmacion',
                'entregado' => 'en_camino_retorno',
                'devolucion_solicitada' => 'en_camino_retorno',
                'en_camino_retorno' => 'devuelto_vendedor',
                default => null,
            };
        }

        return match ($detalle->estado_transporte_actual) {
            'preparando' => 'en_camino_entrega',
            'en_camino_entrega' => 'esperando_confirmacion',
            default => null,
        };
    }

    public function estadosPara(PedidoDetalle $detalle): array
    {
        if ($detalle->es_alquiler_maquinaria) {
            return PedidoDetalle::transporteEstados();
        }

        return self::ESTADOS_ORGANICO;
    }

    public function flujoPara(PedidoDetalle $detalle): array
    {
        return array_keys($this->estadosPara($detalle));
    }

    public function fasesPara(PedidoDetalle $detalle): array
    {
        if ($detalle->es_alquiler_maquinaria) {
            return PedidoDetalle::transporteFases();
        }

        return self::ESTADOS_ORGANICO;
    }

    public function flujoVisiblePara(PedidoDetalle $detalle): array
    {
        return array_keys($this->fasesPara($detalle));
    }

    public function puedeActivarGps(PedidoDetalle $detalle): bool
    {
        if ($detalle->es_alquiler_maquinaria) {
            return ! in_array($detalle->estado_transporte_actual, [
                null,
                'esperando_confirmacion',
                'entregado',
                'devolucion_solicitada',
                'devuelto_vendedor',
                'cancelado',
            ], true);
        }

        return in_array($detalle->estado_transporte_actual, [
            'preparando',
            'en_camino_entrega',
            'esperando_confirmacion',
        ], true);
    }

    public function tipoRecorrido(PedidoDetalle $detalle): string
    {
        return in_array($detalle->estado_transporte_actual, [
            'devolucion_solicitada',
            'en_camino_recoger_devolucion',
            'llego_recoger_devolucion',
            'maquinaria_recogida_retorno',
            'en_camino_retorno',
            'llego_retorno',
            'devuelto_vendedor',
        ], true) ? 'devolucion' : 'entrega';
    }

    public function prepararPorVendedor(PedidoDetalle $detalle, int $vendedorId): PedidoDetalle
    {
        return DB::transaction(function () use ($detalle, $vendedorId) {
            $detalle = PedidoDetalle::whereKey($detalle->id)
                ->lockForUpdate()
                ->firstOrFail();

            $this->validarDetalleTransportable($detalle);

            if ((int) $detalle->vendedor_id !== $vendedorId) {
                abort(403);
            }

            $pendientesGrupo = PedidoDetalle::where('grupo_envio', $detalle->grupo_envio)
                ->where('estado_solicitud', 'pendiente')
                ->exists();

            if ($pendientesGrupo) {
                throw ValidationException::withMessages([
                    'transporte' => 'Responde primero todos los productos de este envio antes de habilitar el transporte.',
                ]);
            }

            if (! in_array($detalle->estado_transporte_actual, ['aceptado', 'asignado'], true)) {
                throw ValidationException::withMessages([
                    'transporte' => 'El transporte ya fue habilitado.',
                ]);
            }

            $estadoAnterior = $detalle->estado_transporte_actual;
            $estadoNuevo = $detalle->es_alquiler_maquinaria ? 'asignado' : 'preparando';
            $detalles = $this->detallesActivosDelGrupo($detalle, true);
            PedidoDetalle::whereIn('id', $detalles->pluck('id'))
                ->update(['estado_transporte' => $estadoNuevo]);
            $detalle->pedido()->update(['estado' => 'en_proceso']);

            foreach ($detalles as $detalleGrupo) {
                TransporteEvento::create([
                    'pedido_detalle_id' => $detalleGrupo->id,
                    'transporte_acceso_id' => $detalle->transporteAcceso?->id,
                    'user_id' => $vendedorId,
                    'actor' => 'vendedor',
                    'estado_anterior' => $detalleGrupo->estado_transporte_actual,
                    'estado_nuevo' => $estadoNuevo,
                ]);
            }

            return $detalle->fresh();
        });
    }

    public function avanzar(TransporteAcceso $acceso, string $accion, ?string $motivoCancelacion = null): PedidoDetalle
    {
        return DB::transaction(function () use ($acceso, $accion, $motivoCancelacion) {
            $detalle = PedidoDetalle::whereKey($acceso->pedido_detalle_id)
                ->lockForUpdate()
                ->firstOrFail();

            $this->validarDetalleTransportable($detalle);
            $estadoAnterior = $detalle->estado_transporte_actual;
            $detalles = $this->detallesActivosDelGrupo($detalle, true);

            if ($accion === 'cancelar') {
                if (! in_array($estadoAnterior, ['preparando', 'en_camino_entrega', 'asignado', 'en_camino_recogida'], true)) {
                    throw ValidationException::withMessages([
                        'estado' => 'El envio ya no puede cancelarse desde transporte.',
                    ]);
                }

                if (mb_strlen(trim((string) $motivoCancelacion)) < 10) {
                    throw ValidationException::withMessages([
                        'motivo_cancelacion' => 'Explica el motivo de la cancelacion con al menos 10 caracteres.',
                    ]);
                }

                $estadoNuevo = 'cancelado';
            } else {
                $estadoNuevo = $this->siguienteEstado($detalle);

                if (! $estadoNuevo) {
                    throw ValidationException::withMessages([
                        'estado' => 'No hay un siguiente estado disponible.',
                    ]);
                }
            }

            PedidoDetalle::whereIn('id', $detalles->pluck('id'))->update([
                'estado_transporte' => $estadoNuevo,
                'cancelacion_motivo' => $estadoNuevo === 'cancelado' ? trim($motivoCancelacion) : null,
                'cancelado_at' => $estadoNuevo === 'cancelado' ? now() : null,
            ]);

            $estadoPedido = $this->estadoPedidoPara($detalle, $estadoNuevo);
            foreach ($detalles as $detalleGrupo) {
                $estadoAlquiler = $this->estadoAlquilerPara($detalleGrupo, $estadoNuevo);

                if ($estadoAlquiler) {
                    $detalleGrupo->update(['estado_alquiler' => $estadoAlquiler]);
                }
            }

            $detalle->pedido()->update(['estado' => $estadoPedido]);

            foreach ($detalles as $detalleGrupo) {
                TransporteEvento::create([
                    'pedido_detalle_id' => $detalleGrupo->id,
                    'transporte_acceso_id' => $acceso->id,
                    'actor' => 'externo',
                    'estado_anterior' => $detalleGrupo->estado_transporte_actual,
                    'estado_nuevo' => $estadoNuevo,
                    'metadata' => array_filter([
                        'ip' => request()->ip(),
                        'motivo_cancelacion' => $estadoNuevo === 'cancelado' ? trim($motivoCancelacion) : null,
                    ]),
                ]);
            }

            if ($estadoNuevo === 'cancelado') {
                $acceso->update(['estado' => TransporteAcceso::ESTADO_REVOCADO]);
            }

            return $detalle->fresh();
        });
    }

    private function estadoPedidoPara(PedidoDetalle $detalle, string $estadoNuevo): string
    {
        if ($detalle->es_alquiler_maquinaria) {
            return match ($estadoNuevo) {
                'asignado',
                'en_camino_recogida',
                'llego_recogida',
                'producto_recogido',
                'en_camino_entrega',
                'llego_destino',
                'esperando_confirmacion' => $estadoNuevo,
                'devolucion_solicitada' => 'en_uso',
                'en_camino_recoger_devolucion',
                'llego_recoger_devolucion',
                'maquinaria_recogida_retorno',
                'en_camino_retorno',
                'llego_retorno' => $estadoNuevo,
                'devuelto_vendedor' => 'devuelto',
                'cancelado' => 'cancelado',
                default => $detalle->pedido->estado,
            };
        }

        return match ($estadoNuevo) {
            'preparando' => 'en_proceso',
            'en_camino_entrega' => 'en_camino_entrega',
            'esperando_confirmacion' => 'esperando_confirmacion',
            'cancelado' => $detalle->pedido->detalles()
                ->where('id', '!=', $detalle->id)
                ->where('estado_solicitud', 'aceptada')
                ->whereNotIn('estado_transporte', ['cancelado', 'rechazado'])
                ->exists()
                    ? 'en_proceso'
                    : 'cancelado',
            default => $detalle->pedido->estado,
        };
    }

    private function estadoAlquilerPara(PedidoDetalle $detalle, string $estadoNuevo): ?string
    {
        if (! $detalle->es_alquiler_maquinaria) {
            return null;
        }

        return match ($estadoNuevo) {
            'en_camino_entrega', 'llego_destino', 'esperando_confirmacion' => 'en_camino_entrega',
            'devolucion_solicitada',
            'en_camino_recoger_devolucion',
            'llego_recoger_devolucion',
            'maquinaria_recogida_retorno' => 'en_uso',
            'en_camino_retorno', 'llego_retorno' => 'en_camino_retorno',
            'devuelto_vendedor' => 'devuelto',
            default => $detalle->estado_alquiler,
        };
    }

    public static function normalizarCodigo(string $codigo): string
    {
        return Str::upper(preg_replace('/[^A-Z0-9]/i', '', trim($codigo)) ?? '');
    }

    public static function hashCodigo(string $codigo): string
    {
        return hash('sha256', self::normalizarCodigo($codigo));
    }

    private function crearCodigo(): string
    {
        $alfabeto = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';

        do {
            $partes = [];

            for ($grupo = 0; $grupo < 3; $grupo++) {
                $parte = '';
                for ($i = 0; $i < 4; $i++) {
                    $parte .= $alfabeto[random_int(0, strlen($alfabeto) - 1)];
                }
                $partes[] = $parte;
            }

            $codigo = implode('-', $partes);
        } while (TransporteAcceso::where('codigo_hash', self::hashCodigo($codigo))->exists());

        return $codigo;
    }

    private function validarDetalleTransportable(PedidoDetalle $detalle): void
    {
        if (! $this->esTransportable($detalle)) {
            throw ValidationException::withMessages([
                'transporte' => 'El acceso externo solo esta disponible para solicitudes de organicos, ganado o maquinaria aceptadas.',
            ]);
        }
    }

    private function esTransportable(?PedidoDetalle $detalle): bool
    {
        return $detalle
            && in_array($detalle->product_type, ['organico', 'ganado', 'maquinaria'], true)
            && $detalle->estado_solicitud === 'aceptada';
    }

    private function detallesActivosDelGrupo(PedidoDetalle $detalle, bool $bloquear = false)
    {
        $query = PedidoDetalle::where('grupo_envio', $detalle->grupo_envio)
            ->where('estado_solicitud', 'aceptada')
            ->orderBy('id');

        if ($bloquear) {
            $query->lockForUpdate();
        }

        return $query->get();
    }
}
