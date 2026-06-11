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
    public const ESTADOS_DELIVERY = [
        'aceptado' => 'Aceptado',
        'preparando' => 'Preparando',
        'en_camino_entrega' => 'En camino',
        'esperando_confirmacion' => 'Entregado, esperando confirmacion',
        'entregado' => 'Finalizado',
        'cancelado' => 'Cancelado',
    ];

    public const ESTADOS_ORGANICO = self::ESTADOS_DELIVERY;

    public function generar(PedidoDetalle $detalle, ?int $createdBy = null, bool $regenerar = false): TransporteAcceso
    {
        $this->validarDetalle($detalle);

        return DB::transaction(function () use ($detalle, $createdBy, $regenerar) {
            $existente = TransporteAcceso::where('pedido_detalle_id', $detalle->id)
                ->lockForUpdate()
                ->first();

            if ($existente && !$regenerar && $existente->estaActivo()) {
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
                ...$data,
            ]);
        });
    }

    public function buscarActivo(string $codigo): ?TransporteAcceso
    {
        $acceso = TransporteAcceso::with([
            'detalle.pedido.user',
            'detalle.vendedor',
            'detalle.ganado',
            'detalle.maquinaria',
            'detalle.organico',
        ])->where('codigo_hash', self::hashCodigo($codigo))->first();

        if (!$acceso || !$acceso->estaActivo()) {
            return null;
        }

        if ($acceso->detalle?->estado_solicitud !== 'aceptada') {
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
        return match ($detalle->estado_transporte_actual) {
            'preparando' => 'en_camino_entrega',
            'en_camino_entrega' => 'esperando_confirmacion',
            default => null,
        };
    }

    public function prepararPorVendedor(PedidoDetalle $detalle, int $vendedorId): PedidoDetalle
    {
        return DB::transaction(function () use ($detalle, $vendedorId) {
            $detalle = PedidoDetalle::whereKey($detalle->id)
                ->lockForUpdate()
                ->firstOrFail();

            $this->validarDetalle($detalle);

            if ((int) $detalle->vendedor_id !== $vendedorId) {
                abort(403);
            }

            if (!in_array($detalle->estado_transporte_actual, ['aceptado', 'asignado'], true)) {
                throw ValidationException::withMessages([
                    'transporte' => 'El producto ya fue marcado como preparado.',
                ]);
            }

            $estadoAnterior = $detalle->estado_transporte_actual;
            $detalle->update(['estado_transporte' => 'preparando']);
            $detalle->pedido()->update(['estado' => 'en_proceso']);

            TransporteEvento::create([
                'pedido_detalle_id' => $detalle->id,
                'transporte_acceso_id' => $detalle->transporteAcceso?->id,
                'user_id' => $vendedorId,
                'actor' => 'vendedor',
                'estado_anterior' => $estadoAnterior,
                'estado_nuevo' => 'preparando',
            ]);

            return $detalle->fresh();
        });
    }

    public function avanzar(TransporteAcceso $acceso, string $accion, ?string $motivoCancelacion = null): PedidoDetalle
    {
        return DB::transaction(function () use ($acceso, $accion, $motivoCancelacion) {
            $detalle = PedidoDetalle::whereKey($acceso->pedido_detalle_id)
                ->lockForUpdate()
                ->firstOrFail();

            $this->validarDetalle($detalle);
            $estadoAnterior = $detalle->estado_transporte_actual;

            if ($accion === 'cancelar') {
                if (!in_array($estadoAnterior, ['preparando', 'en_camino_entrega'], true)) {
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

                if (!$estadoNuevo) {
                    throw ValidationException::withMessages([
                        'estado' => 'No hay un siguiente estado disponible.',
                    ]);
                }
            }

            $detalle->update([
                'estado_transporte' => $estadoNuevo,
                'cancelacion_motivo' => $estadoNuevo === 'cancelado' ? trim($motivoCancelacion) : $detalle->cancelacion_motivo,
                'cancelado_at' => $estadoNuevo === 'cancelado' ? now() : $detalle->cancelado_at,
            ]);

            $estadoPedido = match ($estadoNuevo) {
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

            $detalle->pedido()->update(['estado' => $estadoPedido]);

            TransporteEvento::create([
                'pedido_detalle_id' => $detalle->id,
                'transporte_acceso_id' => $acceso->id,
                'actor' => 'externo',
                'estado_anterior' => $estadoAnterior,
                'estado_nuevo' => $estadoNuevo,
                'metadata' => array_filter([
                    'ip' => request()->ip(),
                    'motivo_cancelacion' => $estadoNuevo === 'cancelado' ? trim($motivoCancelacion) : null,
                ]),
            ]);

            if ($estadoNuevo === 'cancelado') {
                $acceso->update(['estado' => TransporteAcceso::ESTADO_REVOCADO]);
            }

            return $detalle->fresh();
        });
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

    private function validarDetalle(PedidoDetalle $detalle): void
    {
        if (!in_array($detalle->product_type, ['ganado', 'maquinaria', 'organico'], true)
            || $detalle->estado_solicitud !== 'aceptada') {
            throw ValidationException::withMessages([
                'transporte' => 'El acceso externo solo esta disponible para solicitudes aceptadas.',
            ]);
        }
    }
}
