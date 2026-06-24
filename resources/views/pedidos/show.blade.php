@extends('layouts.adminlte')

@section('title', 'Detalle del Pedido')
@section('page_title', 'Detalle del Pedido')

@section('content')
    @php
        $estadoNorm = strtolower(str_replace(' ', '_', $pedido->estado));
        $color = match ($estadoNorm) {
            'pendiente' => 'warning',
            'en_proceso' => 'info',
            'entregado', 'completado', 'finalizado' => 'success',
            'cancelado' => 'danger',
            default => 'secondary',
        };
        $detallesConRuta = $pedido->detalles
            ->filter(fn($detalle) => in_array($detalle->product_type, ['organico', 'ganado', 'maquinaria'], true)
                && $detalle->estado_solicitud === 'aceptada'
                && $detalle->product_latitud
                && $detalle->product_longitud)
            ->values();
        $detalleConUbicacion = $detallesConRuta->first()
            ?: $pedido->detalles->first(fn($detalle) => $detalle->product_latitud && $detalle->product_longitud);
        $mapaDestinoUrl = $pedido->destino_latitud && $pedido->destino_longitud
            ? 'https://www.google.com/maps/search/?api=1&query=' . $pedido->destino_latitud . ',' . $pedido->destino_longitud
            : null;

        $trackingOptions = $detallesConRuta
            ->map(fn($detalle) => [
                'id' => $detalle->id,
                'name' => $detalle->nombre_producto,
                'type' => ucfirst($detalle->product_type),
                'originLabel' => $detalle->origen_direccion_actual ?: 'Origen no registrado',
                'originLat' => (float) $detalle->product_latitud,
                'originLng' => (float) $detalle->product_longitud,
                'distanceKm' => $detalle->distancia_destino_km,
                'estadoLabel' => $detalle->estado_transporte_label ?: 'Pendiente',
                'trackingUrl' => route('pedidos.detalles.tracking.latest', $detalle, false),
                'mapsUrl' => $pedido->destino_latitud && $pedido->destino_longitud
                    ? 'https://www.google.com/maps/dir/?api=1&origin=' . $detalle->product_latitud . ',' . $detalle->product_longitud . '&destination=' . $pedido->destino_latitud . ',' . $pedido->destino_longitud . '&travelmode=driving'
                    : null,
            ])
            ->values();

        $liveDetailStates = $pedido->detalles
            ->map(fn($detalle) => [
                'id' => $detalle->id,
                'url' => route('pedidos.detalles.estadoTransporte', $detalle, false),
            ])
            ->values();
    @endphp

    <div class="container-fluid orders-page">
        <style>
            .order-rental-tracking {
                margin-top: .75rem;
                padding: .85rem;
                border: 1px solid rgba(63, 126, 42, .14);
                border-radius: 12px;
                background: #f7fbf4;
            }

            .order-rental-tracking__title {
                display: flex;
                justify-content: space-between;
                gap: .75rem;
                margin-bottom: .7rem;
                font-size: .82rem;
                font-weight: 800;
            }

            .order-rental-tracking__steps {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(105px, 1fr));
                gap: .45rem;
            }

            .order-rental-tracking__step {
                padding: .5rem;
                border: 1px solid #dfe8dc;
                border-radius: 9px;
                background: #fff;
                color: #6b776b;
                font-size: .72rem;
                font-weight: 700;
                text-align: center;
            }

            .order-rental-tracking__step.is-done,
            .order-rental-tracking__step.is-current {
                border-color: rgba(63, 126, 42, .35);
                color: #1f2a1b;
            }

            .order-rental-tracking__step.is-current {
                background: #eef8ea;
            }

            @media (max-width: 768px) {
                .order-rental-tracking__steps {
                    grid-template-columns: repeat(2, minmax(0, 1fr));
                }
            }

            .orders-detail-expanded-row td {
                padding: 0 1rem 1rem;
                vertical-align: top;
                background: #fbfdf9;
            }

            .orders-detail-expanded {
                max-width: 980px;
            }

            .post-sale-box textarea {
                height: 86px;
                min-height: 86px;
                max-height: 86px;
                resize: none;
            }

            .post-sale-box .post-sale-review textarea {
                height: 74px;
                min-height: 74px;
                max-height: 74px;
            }

            .tracking-pin {
                display: flex;
                align-items: center;
                justify-content: center;
                width: 30px;
                height: 30px;
                border: 3px solid #fff;
                border-radius: 999px;
                color: #fff;
                box-shadow: 0 4px 12px rgba(0, 0, 0, .25);
            }

            .tracking-pin--current {
                background: #0d6efd;
            }

            .tracking-pin--target {
                background: #198754;
            }

            .tracking-pin--origin {
                background: #fd7e14;
            }

            .tracking-map-marker {
                display: inline-flex;
                align-items: center;
                gap: .35rem;
                padding: .18rem .55rem .18rem .18rem;
                border: 2px solid #fff;
                border-radius: 999px;
                background: rgba(255, 255, 255, .96);
                box-shadow: 0 4px 12px rgba(0, 0, 0, .24);
                white-space: nowrap;
            }

            .tracking-map-marker small {
                color: #1f2a1b;
                font-size: .72rem;
                font-weight: 800;
            }

            .order-route-picker {
                display: flex;
                flex-wrap: wrap;
                gap: .5rem;
                margin-top: .75rem;
            }

            .order-route-picker__btn {
                text-align: left;
                white-space: normal;
            }

            .order-route-picker__btn small {
                display: block;
                font-weight: 600;
                opacity: .78;
            }

            .order-map-header {
                display: flex;
                align-items: center;
                justify-content: flex-end;
                margin-top: .75rem;
            }

            .order-map-fullscreen {
                position: fixed;
                inset: 0;
                z-index: 3000;
                display: none;
                grid-template-rows: auto 1fr;
                background: #101810;
            }

            body.order-map-fullscreen-open {
                overflow: hidden;
            }

            body.order-map-fullscreen-open .order-map-fullscreen {
                display: grid;
            }

            .order-map-fullscreen__header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 12px;
                padding: 12px 16px;
                color: #fff;
                background: rgba(16, 24, 16, .96);
            }

            .order-map-fullscreen__header strong,
            .order-map-fullscreen__header small {
                display: block;
            }

            .order-map-fullscreen__header small {
                color: #d9e8d5;
            }

            .order-map-fullscreen__close {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 42px;
                height: 42px;
                border: 1px solid rgba(255, 255, 255, .28);
                border-radius: 999px;
                color: #fff;
                background: transparent;
                cursor: pointer;
            }

            .order-map-fullscreen__map {
                min-height: 0;
            }

            .order-map-fullscreen__map #pedido-destino-map {
                height: 100% !important;
                min-height: 100%;
                margin-top: 0 !important;
                border-radius: 0 !important;
            }
        </style>
        <div class="orders-card orders-detail-card">
            <div class="orders-detail-header">
                <div class="orders-detail-header__title">
                    <span><i class="fas fa-receipt"></i></span>
                    <div>
                        <h2>Pedido #{{ $pedido->id }}</h2>
                        <small>Fecha: {{ $pedido->created_at->format('d/m/Y H:i') }}</small>
                    </div>
                </div>

                <span class="orders-status orders-status--{{ $color }}">
                    {{ str_replace('_', ' ', $estadoNorm) }}
                </span>
            </div>

            <div class="orders-card__body">
                @if (session('success'))
                    <div class="alert alert-success orders-alert">
                        <i class="fas fa-check-circle mr-1"></i>{{ session('success') }}
                    </div>
                @endif

                @if ($pedido->detalles->where('estado_solicitud', 'cancelada_producto_vendido')->count() > 0)
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        Uno o mas productos de este pedido ya fueron vendidos a otro comprador y tu solicitud fue cancelada.
                    </div>
                @endif

                @if ($pedido->detalles->where('estado_solicitud', 'aceptada')->count() > 0)
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle mr-1"></i>
                        El vendedor acepto venderte uno o mas productos de este pedido.
                    </div>
                @endif

                <div class="orders-table-heading">
                    <h3>
                        <i class="fas fa-box-open"></i>
                        Productos
                    </h3>
                    <span>Detalle de productos incluidos en el pedido</span>
                </div>

                <div class="alert alert-light border mb-4">
                    <strong><i class="fas fa-map-marker-alt mr-1"></i>Destino del producto:</strong>
                    <div class="mt-1">{{ $pedido->destino_entrega ?: 'No especificado' }}</div>
                    <div class="mt-2">
                        <strong><i class="fas fa-phone-alt mr-1"></i>Telefono de contacto:</strong>
                        {{ $pedido->telefono_contacto ?: 'No especificado' }}
                    </div>
                    @if ($pedido->destino_latitud && $pedido->destino_longitud)
                        @if ($trackingOptions->count() > 1)
                            <div class="order-route-picker" role="tablist" aria-label="Rutas por producto">
                                @foreach ($trackingOptions as $index => $option)
                                    <button type="button"
                                        class="btn btn-sm {{ $index === 0 ? 'btn-success' : 'btn-outline-success' }} order-route-picker__btn"
                                        data-route-option="{{ $option['id'] }}">
                                        <i class="fas fa-route mr-1"></i>{{ $option['name'] }}
                                        <small>{{ $option['type'] }} · {{ $option['estadoLabel'] }}</small>
                                    </button>
                                @endforeach
                            </div>
                        @endif
                        <div class="order-map-header">
                            <button type="button" class="btn btn-sm btn-outline-success" id="pedido-map-expand">
                                <i class="fas fa-expand mr-1"></i>Ampliar mapa
                            </button>
                        </div>
                        <div id="pedido-map-shell">
                            <div id="pedido-destino-map" class="mt-3"
                                style="height: 320px; width: 100%; border-radius: 8px; overflow: hidden;"></div>
                        </div>
                        <div id="pedido-live-tracking" class="alert alert-light border mt-3 mb-0">
                            <strong><i class="fas fa-location-arrow mr-1"></i>Seguimiento en vivo:</strong>
                            <span id="pedido-live-status">Esperando ubicacion del transportista.</span>
                        </div>
                        <div class="small text-muted mt-2" id="pedido-route-summary"
                            style="{{ $trackingOptions->isNotEmpty() ? '' : 'display: none;' }}">
                            <i class="fas fa-route mr-1"></i>
                            <span id="pedido-route-summary-text"></span>
                        </div>
                        @if ($trackingOptions->isEmpty())
                            <div class="small text-muted mt-2">
                                <i class="fas fa-info-circle mr-1"></i>
                                No se pudo dibujar la ruta porque el producto no tiene coordenadas registradas.
                            </div>
                        @endif
	                        <a class="btn btn-sm btn-outline-success mt-2" target="_blank"
	                            href="{{ $trackingOptions->first()['mapsUrl'] ?? $mapaDestinoUrl }}"
	                            id="pedido-google-maps-link">
	                            <i class="fas fa-external-link-alt mr-1"></i>{{ $trackingOptions->isNotEmpty() ? 'Abrir ruta en Google Maps' : 'Abrir mapa' }}
	                        </a>
	                    @endif
	                </div>

                <div class="order-map-fullscreen" id="pedido-map-fullscreen" aria-hidden="true">
                    <div class="order-map-fullscreen__header">
                        <div>
                            <strong><i class="fas fa-map-marked-alt mr-1"></i>Seguimiento del pedido</strong>
                            <small id="pedido-map-fullscreen-status">Esperando ubicacion del transportista.</small>
                        </div>
                        <button type="button" class="order-map-fullscreen__close" id="pedido-map-close"
                            aria-label="Cerrar mapa ampliado">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="order-map-fullscreen__map" id="pedido-map-fullscreen-target"></div>
                </div>

		                <div class="table-responsive orders-table-wrap">
                    @php
                        $resumenesEnvio = $pedido->detalles
                            ->groupBy('grupo_envio')
                            ->map(function ($detalles) {
                                return [
                                    'total' => $detalles->count(),
                                    'pendientes' => $detalles->where('estado_solicitud', 'pendiente')->count(),
                                    'aceptados' => $detalles->where('estado_solicitud', 'aceptada')->count(),
                                    'rechazados' => $detalles->where('estado_solicitud', 'rechazada')->count(),
                                ];
                            });
                    @endphp
                    @foreach ($resumenesEnvio as $resumenEnvio)
                        @if (!$resumenEnvio['pendientes'] && $resumenEnvio['total'] > 1)
                            <div class="alert {{ $resumenEnvio['rechazados'] ? 'alert-info' : 'alert-success' }} mx-3">
                                @if ($resumenEnvio['rechazados'])
                                    Se aceptaron {{ $resumenEnvio['aceptados'] }} producto(s) y se rechazaron
                                    {{ $resumenEnvio['rechazados'] }}. El envío incluirá únicamente los aceptados.
                                @else
                                    Todos los productos de este envío fueron aceptados.
                                @endif
                            </div>
                        @endif
                    @endforeach
                    <table class="table table-hover orders-table mb-0">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Tipo</th>
                                <th>Cantidad/Tiempo</th>
                                <th>Precio</th>
                                <th>Total</th>
                                <th>Respuesta</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $estadoSolicitudLabels = [
                                    'pendiente' => ['Pendiente', 'warning'],
                                    'aceptada' => ['Aceptada', 'success'],
                                    'rechazada' => ['Rechazada', 'secondary'],
                                    'cancelada_producto_vendido' => ['Producto vendido', 'danger'],
                                ];
                            @endphp
                            @foreach ($pedido->detalles as $detalle)
                                @php
                                    [$labelSolicitud, $colorSolicitud] = $estadoSolicitudLabels[$detalle->estado_solicitud] ?? ['Pendiente', 'warning'];
                                    $isMaquinaria = $detalle->es_alquiler_maquinaria;
                                    $cantidadTexto = $detalle->cantidad_tiempo_texto;
                                    $precioLabel = $detalle->precio_corto_label;
                                @endphp
                                <tr @if(in_array($detalle->product_type, ['organico', 'ganado', 'maquinaria'], true))
                                    data-buyer-delivery-detail="{{ $detalle->id }}"
                                    data-request-state="{{ $detalle->estado_solicitud }}"
                                    data-transport-state="{{ $detalle->estado_transporte_actual }}"
                                    @endif>
                                    <td>
                                        <span class="orders-table__id">{{ $detalle->nombre_producto }}</span>
                                        @if ($detalle->vendedor_telefono)
                                            <br>
                                            <small class="text-muted">
                                                <i class="fas fa-phone-alt mr-1"></i>Vendedor:
                                                <a href="tel:{{ $detalle->vendedor_telefono }}">
                                                    {{ $detalle->vendedor_telefono }}
                                                </a>
                                            </small>
                                        @else
                                            <br>
                                            <small class="text-muted">
                                                <i class="fas fa-phone-alt mr-1"></i>Vendedor: No especificado
                                            </small>
                                        @endif
                                        <br>
                                        <small class="text-primary" data-live-transport-wrap="{{ $detalle->id }}"
                                            style="{{ $detalle->estado_solicitud === 'aceptada' && $detalle->estado_transporte_label ? '' : 'display: none;' }}">
                                            <i class="fas fa-truck mr-1"></i>Transporte:
                                            <span data-live-transport-label="{{ $detalle->id }}">
                                                {{ $detalle->estado_transporte_label ?: 'Pendiente' }}
                                            </span>
                                        </small>
                                    </td>
                                    <td>{{ ucfirst($detalle->product_type) }}</td>
                                    <td>{{ $cantidadTexto }}</td>
                                    <td>
                                        Bs {{ number_format($detalle->precio_unitario, 2) }}
                                        <br><small class="text-muted">{{ $precioLabel }}</small>
                                    </td>
                                    <td class="orders-table__total">Bs {{ number_format($detalle->subtotal, 2) }}</td>
                                    <td>
                                        <span class="badge badge-{{ $colorSolicitud }}">{{ $labelSolicitud }}</span>
                                    </td>
                                </tr>
                                @if (in_array($detalle->product_type, ['organico', 'ganado', 'maquinaria'], true) && $detalle->estado_solicitud === 'aceptada')
                                    @php
                                        $deliveryLabels = $detalle->es_alquiler_maquinaria
                                            ? \App\Models\PedidoDetalle::transporteFases()
                                            : \App\Services\TransporteAccesoService::ESTADOS_ORGANICO;
                                        $deliveryStatePhases = $detalle->es_alquiler_maquinaria
                                            ? \App\Models\PedidoDetalle::transporteEstadoFases()
                                            : [];
                                        $deliveryFlow = array_keys($deliveryLabels);
                                        $deliveryCurrent = $detalle->estado_transporte_actual === 'asignado' && in_array($detalle->product_type, ['organico', 'ganado'], true)
                                            ? 'aceptado'
                                            : $detalle->estado_transporte_actual;
                                        $deliveryCurrentVisible = $deliveryStatePhases[$deliveryCurrent] ?? $deliveryCurrent;
                                        $deliveryIndex = array_search($deliveryCurrentVisible, $deliveryFlow, true);
                                        $mostrarPostventa = $detalle->estado_solicitud === 'aceptada'
                                            && (
                                                $detalle->recepcion_confirmada_at
                                                || in_array($detalle->estado_transporte_actual, ['entregado', 'cancelado'], true)
                                                || $pedido->estado === 'finalizado'
                                            );
                                    @endphp
                                    <tr class="orders-detail-expanded-row">
                                        <td colspan="6">
                                            <div class="orders-detail-expanded">
                                                <div class="order-rental-tracking">
                                                    <div class="order-rental-tracking__title">
                                                        <span><i class="fas fa-truck mr-1"></i>Seguimiento de entrega</span>
                                                        <span class="text-success"
                                                            id="buyer-delivery-status-{{ $detalle->id }}">{{ $detalle->estado_transporte_label }}</span>
                                                    </div>
                                                    <div class="order-rental-tracking__steps">
                                                        @foreach ($deliveryFlow as $deliveryStepIndex => $deliveryState)
                                                            @php
                                                                $deliveryClass = '';
                                                                if ($deliveryIndex !== false && $deliveryStepIndex < $deliveryIndex) {
                                                                    $deliveryClass = 'is-done';
                                                                } elseif ($deliveryState === $deliveryCurrentVisible) {
                                                                    $deliveryClass = 'is-current';
                                                                }
                                                            @endphp
                                                            <div class="order-rental-tracking__step {{ $deliveryClass }}"
                                                                data-delivery-detail="{{ $detalle->id }}"
                                                                data-state="{{ $deliveryState }}">
                                                                {{ $deliveryLabels[$deliveryState] ?? ucfirst(str_replace('_', ' ', $deliveryState)) }}
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>

                                                <div data-live-confirm-container="{{ $detalle->id }}"
                                                    style="{{ $detalle->estado_transporte_actual === 'esperando_confirmacion' ? '' : 'display: none;' }}">
                                                    <form action="{{ route('pedidos.detalles.confirmarRecepcion', $detalle) }}"
                                                        method="POST" class="mt-2 buyer-signature-form"
                                                        id="buyer-receive-form-{{ $detalle->id }}"
                                                        style="{{ $detalle->estado_transporte_actual === 'esperando_confirmacion' ? '' : 'display:none' }}"
                                                        data-product-name="{{ $detalle->nombre_producto }}">
                                                        @csrf
                                                        <input type="hidden" name="firma_comprador" value="">
                                                        <button type="submit" class="btn btn-sm btn-success">
                                                            <i class="fas fa-check-circle mr-1"></i>Confirmar recepcion
                                                        </button>
                                                    </form>
                                                </div>
                                                <div class="small text-success mt-2"
                                                    id="buyer-received-{{ $detalle->id }}"
                                                    data-live-recepcion="{{ $detalle->id }}"
                                                    style="{{ $detalle->recepcion_confirmada_at ? '' : 'display: none;' }}">
                                                    <i class="fas fa-check-circle mr-1"></i>
                                                    Recepcion confirmada
                                                    <span data-live-recepcion-date="{{ $detalle->id }}">
                                                        {{ $detalle->recepcion_confirmada_at ? 'el ' . $detalle->recepcion_confirmada_at->format('d/m/Y H:i') : '' }}
                                                    </span>
                                                </div>
                                                <button type="button"
                                                    class="btn btn-sm btn-outline-primary mt-2 js-comprobante-modal"
                                                    data-comprobante-url="{{ route('pedidos.comprobante.reserva', $pedido) }}"
                                                    data-comprobante-title="Comprobante de reserva">
                                                    <i class="fas fa-file-invoice mr-1"></i>Comprobante de reserva
                                                </button>
                                                @if ($detalle->recepcion_confirmada_at)
                                                    <button type="button"
                                                        class="btn btn-sm btn-outline-primary mt-2 js-comprobante-modal"
                                                        data-comprobante-url="{{ route('pedidos.detalles.comprobante.final', $detalle) }}"
                                                        data-comprobante-title="Comprobante final">
                                                        <i class="fas fa-file-signature mr-1"></i>Comprobante final
                                                    </button>
                                                @endif

                                                @if($mostrarPostventa)
                                                    @include('organicos.partials.postventa', ['detalle' => $detalle, 'modo' => 'comprador'])
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="orders-detail-total">
                    <span>Total</span>
                    <strong>Bs {{ number_format($pedido->total, 2) }}</strong>
                </div>
            </div>

            <div class="orders-card__footer">
                <a href="{{ route('pedidos.index') }}" class="btn orders-back-btn">
                    <i class="fas fa-arrow-left mr-1"></i> Volver a mis pedidos
                </a>
            </div>
        </div>
    </div>

    <div class="modal fade" id="comprobanteModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="comprobanteModalTitle">Comprobante</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-0">
                    <iframe id="comprobanteModalFrame" title="Vista previa del comprobante"
                        style="width: 100%; height: 76vh; border: 0;"></iframe>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-success" id="comprobanteModalPrint">
                        <i class="fas fa-download mr-1"></i>Descargar / imprimir PDF
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var statesUrl = @json(route('pedidos.tracking.estados', $pedido, false));
            var transportStatePhases = @json(\App\Models\PedidoDetalle::transporteEstadoFases());
            var statusBusy = false;
            var statusTimer = null;
            var comprobanteModal = document.getElementById('comprobanteModal');
            var comprobanteFrame = document.getElementById('comprobanteModalFrame');
            var comprobanteTitle = document.getElementById('comprobanteModalTitle');
            var comprobantePrint = document.getElementById('comprobanteModalPrint');

            document.querySelectorAll('.js-comprobante-modal').forEach(function(button) {
                button.addEventListener('click', function() {
                    comprobanteTitle.textContent = button.dataset.comprobanteTitle || 'Comprobante';
                    comprobanteFrame.src = button.dataset.comprobanteUrl;

                    if (window.jQuery) {
                        window.jQuery(comprobanteModal).modal('show');
                    }
                });
            });

            if (comprobanteModal && window.jQuery) {
                window.jQuery(comprobanteModal).on('hidden.bs.modal', function() {
                    comprobanteFrame.src = 'about:blank';
                });
            }

            if (comprobantePrint) {
                comprobantePrint.addEventListener('click', function() {
                    if (comprobanteFrame.contentWindow) {
                        comprobanteFrame.contentWindow.focus();
                        comprobanteFrame.contentWindow.print();
                    }
                });
            }

            function updateDeliveryDetail(detail) {
                var row = document.querySelector('[data-buyer-delivery-detail="' + detail.detalle_id + '"]');

                if (row && row.dataset.requestState !== detail.estado_solicitud) {
                    window.location.reload();
                    return;
                }

                if (row
                    && ['entregado', 'cancelado'].includes(detail.estado)
                    && row.dataset.transportState !== detail.estado) {
                    window.location.reload();
                    return;
                }

                var status = document.getElementById('buyer-delivery-status-' + detail.detalle_id);
                if (status && detail.estado_label) {
                    status.textContent = detail.estado_label;
                }

                var steps = Array.from(document.querySelectorAll('[data-delivery-detail="' + detail.detalle_id + '"]'));
                var deliveryFlow = steps.map(function(step) { return step.dataset.state; });
                var currentVisibleState = transportStatePhases[detail.estado] || detail.estado;
                var currentIndex = deliveryFlow.indexOf(currentVisibleState);
                steps.forEach(function(step) {
                    var index = deliveryFlow.indexOf(step.dataset.state);
                    step.classList.toggle('is-done', currentIndex >= 0 && index < currentIndex);
                    step.classList.toggle('is-current', step.dataset.state === currentVisibleState);
                });

                var receiveForm = document.getElementById('buyer-receive-form-' + detail.detalle_id);
                var receivedMessage = document.getElementById('buyer-received-' + detail.detalle_id);

                if (receiveForm) {
                    receiveForm.style.display = detail.estado === 'esperando_confirmacion' ? '' : 'none';
                }

                if (receivedMessage) {
                    receivedMessage.style.display = detail.estado === 'entregado' ? '' : 'none';
                }
            }

            function scheduleStatusRefresh() {
                clearTimeout(statusTimer);
                statusTimer = setTimeout(refreshStatuses, 7000);
            }

            function refreshStatuses() {
                if (statusBusy || document.hidden) {
                    scheduleStatusRefresh();
                    return;
                }

                statusBusy = true;
                fetch(statesUrl, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    cache: 'no-store'
                })
                    .then(function(response) {
                        if (!response.ok) {
                            throw new Error('No se pudieron consultar los estados');
                        }

                        return response.json();
                    })
                    .then(function(data) {
                        (data.detalles || []).forEach(updateDeliveryDetail);
                    })
                    .catch(function() {
                        // Se vuelve a intentar en el siguiente intervalo sin interrumpir la vista.
                    })
                    .finally(function() {
                        statusBusy = false;
                        scheduleStatusRefresh();
                    });
            }

            document.addEventListener('visibilitychange', function() {
                if (!document.hidden) {
                    clearTimeout(statusTimer);
                    refreshStatuses();
                }
            });

            refreshStatuses();
        });
    </script>

    @if ($pedido->destino_latitud && $pedido->destino_longitud)
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var destinoLat = {{ $pedido->destino_latitud }};
                var destinoLng = {{ $pedido->destino_longitud }};
                var routeOptions = @json($trackingOptions);
                var selectedRoute = routeOptions[0] || null;
	                var liveStatus = document.getElementById('pedido-live-status');
	                var fullscreenStatus = document.getElementById('pedido-map-fullscreen-status');
	                var routeSummary = document.getElementById('pedido-route-summary');
	                var routeSummaryText = document.getElementById('pedido-route-summary-text');
	                var mapsLink = document.getElementById('pedido-google-maps-link');
                    var mapShell = document.getElementById('pedido-map-shell');
                    var mapFullscreen = document.getElementById('pedido-map-fullscreen');
                    var mapFullscreenTarget = document.getElementById('pedido-map-fullscreen-target');
                var liveMarker = null;
                var lastLiveKey = null;
                var remainingRouteLine = null;
                var remainingRouteKey = null;
                var trackingBusy = false;
                var trackingTimer = null;
                var routeRequestId = 0;
                var map = L.map('pedido-destino-map').setView([destinoLat, destinoLng], 16);
                var routeLayer = L.layerGroup().addTo(map);
                var liveLayer = L.layerGroup().addTo(map);
                var currentIcon = L.divIcon({
                    className: '',
                    html: '<div class="tracking-map-marker">' +
                        '<span class="tracking-pin tracking-pin--current"><i class="fas fa-truck"></i></span>' +
                        '<small>Transportista</small>' +
                    '</div>',
                    iconSize: [160, 40],
                    iconAnchor: [18, 18],
                    popupAnchor: [0, -16]
                });
                var targetIcon = L.divIcon({
                    className: '',
                    html: '<div class="tracking-map-marker">' +
                        '<span class="tracking-pin tracking-pin--target"><i class="fas fa-home"></i></span>' +
                        '<small>Comprador</small>' +
                    '</div>',
                    iconSize: [150, 40],
                    iconAnchor: [18, 18],
                    popupAnchor: [0, -16]
                });
                var originIcon = L.divIcon({
                    className: '',
                    html: '<div class="tracking-map-marker">' +
                        '<span class="tracking-pin tracking-pin--origin"><i class="fas fa-box"></i></span>' +
                        '<small>Producto</small>' +
                    '</div>',
                    iconSize: [145, 40],
                    iconAnchor: [18, 18],
                    popupAnchor: [0, -16]
                });

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(map);

                var targetMarker = L.marker([destinoLat, destinoLng], {
                    icon: targetIcon
                });

                targetMarker
                    .addTo(map)
                    .bindPopup('<strong>Destino del comprador</strong><br>' + @json($pedido->destino_entrega))
                    .openPopup();

                function resetLiveTracking() {
                    clearTimeout(trackingTimer);
                    liveLayer.clearLayers();
                    liveMarker = null;
                    remainingRouteLine = null;
                    remainingRouteKey = null;
                    lastLiveKey = null;
                    trackingBusy = false;
                }

                function updateRouteSummary(option) {
                    if (!routeSummary || !routeSummaryText) {
                        return;
                    }

                    if (!option) {
                        routeSummary.style.display = 'none';
                        return;
                    }

                    var text = 'Ruta seleccionada: ' + option.name + '.';

                    if (option.distanceKm !== null && option.distanceKm !== undefined) {
                        text += ' Distancia aproximada: ' + Number(option.distanceKm).toFixed(1) + ' km.';
                    }

                    routeSummaryText.textContent = text;
                    routeSummary.style.display = '';
                }

                function updateRouteButtons(option) {
                    document.querySelectorAll('[data-route-option]').forEach(function(button) {
                        var active = option && String(button.dataset.routeOption) === String(option.id);
                        button.classList.toggle('btn-success', active);
                        button.classList.toggle('btn-outline-success', !active);
                    });
                }

                function drawFallbackLine(option) {
                    var fallbackLine = L.polyline([
                        [option.originLat, option.originLng],
                        [destinoLat, destinoLng]
                    ], {
                        color: '#28a745',
                        weight: 4,
                        opacity: 0.85,
                        dashArray: '8, 8'
                    }).addTo(routeLayer);

                    fallbackLine.bindPopup('Distancia aproximada: ' + (
                        option.distanceKm !== null && option.distanceKm !== undefined
                            ? Number(option.distanceKm).toFixed(1)
                            : '0.0'
                    ) + ' km');

                    map.fitBounds(fallbackLine.getBounds(), {
                        padding: [40, 40],
                        maxZoom: 14
                    });
                }

                function drawSelectedRoute(option) {
                    routeRequestId++;
                    var currentRequest = routeRequestId;
                    routeLayer.clearLayers();
                    targetMarker.addTo(routeLayer);
                    updateRouteSummary(option);
                    updateRouteButtons(option);

                    if (mapsLink && option && option.mapsUrl) {
                        mapsLink.href = option.mapsUrl;
                        mapsLink.innerHTML = '<i class="fas fa-external-link-alt mr-1"></i>Abrir ruta en Google Maps';
                    }

                    if (!option) {
                        setLiveStatus('Selecciona un producto para ver su ruta.');
                        map.setView([destinoLat, destinoLng], 16);
                        return;
                    }

                    L.marker([option.originLat, option.originLng], {
                        icon: originIcon
                    })
                        .addTo(routeLayer)
                        .bindPopup('<strong>Producto</strong><br>' + option.name + '<br>' + option.originLabel);

                    var osrmUrl = 'https://router.project-osrm.org/route/v1/driving/' +
                        option.originLng + ',' + option.originLat + ';' + destinoLng + ',' + destinoLat +
                        '?overview=full&geometries=geojson';

                    fetch(osrmUrl)
                        .then(function(response) {
                            if (!response.ok) {
                                throw new Error('No se pudo obtener la ruta');
                            }

                            return response.json();
                        })
                        .then(function(data) {
                            if (currentRequest !== routeRequestId) {
                                return;
                            }

                            var route = data.routes && data.routes[0];

                            if (!route || !route.geometry || !route.geometry.coordinates) {
                                drawFallbackLine(option);
                                return;
                            }

                            var routeCoordinates = route.geometry.coordinates.map(function(coordinate) {
                                return [coordinate[1], coordinate[0]];
                            });

                            var routeLine = L.polyline(routeCoordinates, {
                                color: '#28a745',
                                weight: 5,
                                opacity: 0.9
                            }).addTo(routeLayer);

                            var distanceKm = route.distance ? (route.distance / 1000).toFixed(1) : null;
                            var durationMin = route.duration ? Math.round(route.duration / 60) : null;
                            var popup = '<strong>Ruta vehicular estimada</strong>';

                            if (distanceKm) {
                                popup += '<br>Recorrido: ' + distanceKm + ' km';
                            }

                            if (durationMin) {
                                popup += '<br>Tiempo aprox.: ' + durationMin + ' min';
                            }

                            if (option.mapsUrl) {
                                popup += '<br><a href="' + option.mapsUrl + '" target="_blank" rel="noopener">Abrir en Google Maps</a>';
                            }

                            routeLine.bindPopup(popup);

                            map.fitBounds(routeLine.getBounds(), {
                                padding: [40, 40],
                                maxZoom: 14
                            });
                        })
                        .catch(function() {
                            if (currentRequest === routeRequestId) {
                                drawFallbackLine(option);
                            }
                        });
                }

	                function setLiveStatus(text) {
	                    if (liveStatus) {
	                        liveStatus.textContent = text;
	                    }
                        if (fullscreenStatus) {
                            fullscreenStatus.textContent = text;
                        }
	                }

                    function resizePedidoMapSoon() {
                        setTimeout(function() {
                            map.invalidateSize();

                            if (remainingRouteLine) {
                                map.fitBounds(remainingRouteLine.getBounds(), { padding: [40, 40], maxZoom: 15 });
                            } else if (liveMarker) {
                                map.setView(liveMarker.getLatLng(), 16);
                            }
                        }, 90);
                    }

                    function openPedidoMapFullscreen() {
                        mapFullscreenTarget.appendChild(document.getElementById('pedido-destino-map'));
                        document.body.classList.add('order-map-fullscreen-open');
                        mapFullscreen.setAttribute('aria-hidden', 'false');
                        resizePedidoMapSoon();
                    }

                    function closePedidoMapFullscreen() {
                        mapShell.appendChild(document.getElementById('pedido-destino-map'));
                        document.body.classList.remove('order-map-fullscreen-open');
                        mapFullscreen.setAttribute('aria-hidden', 'true');
                        resizePedidoMapSoon();
                    }

                function targetForLiveState(ubicacion) {
                    var estado = ubicacion.estado_transporte;

                    if (selectedRoute && [
                        'asignado',
                        'en_camino_recogida',
                        'devolucion_solicitada',
                        'en_camino_recoger_devolucion',
                        'llego_recoger_devolucion',
                        'maquinaria_recogida_retorno',
                        'en_camino_retorno',
                        'llego_retorno',
                        'devuelto_vendedor'
                    ].indexOf(estado) !== -1) {
                        return {
                            lat: selectedRoute.originLat,
                            lng: selectedRoute.originLng,
                            label: 'punto del producto'
                        };
                    }

                    return {
                        lat: destinoLat,
                        lng: destinoLng,
                        label: 'destino del comprador'
                    };
                }

                function animateMarkerTo(marker, nextLatLng) {
                    var start = marker.getLatLng();
                    var startTime = performance.now();
                    var duration = 1900;

                    function step(now) {
                        var progress = Math.min(1, (now - startTime) / duration);
                        var eased = progress < .5
                            ? 2 * progress * progress
                            : 1 - Math.pow(-2 * progress + 2, 2) / 2;

                        marker.setLatLng([
                            start.lat + ((nextLatLng[0] - start.lat) * eased),
                            start.lng + ((nextLatLng[1] - start.lng) * eased)
                        ]);

                        if (progress < 1) {
                            requestAnimationFrame(step);
                        }
                    }

                    requestAnimationFrame(step);
                }

                function drawRemainingRoute(fromLatLng, target) {
                    if (!target || !target.lat || !target.lng) return;

                    var routeKey = [
                        Number(fromLatLng[0]).toFixed(5),
                        Number(fromLatLng[1]).toFixed(5),
                        Number(target.lat).toFixed(5),
                        Number(target.lng).toFixed(5)
                    ].join('|');

                    if (routeKey === remainingRouteKey) return;
                    remainingRouteKey = routeKey;

                    var osrmUrl = 'https://router.project-osrm.org/route/v1/driving/' +
                        fromLatLng[1] + ',' + fromLatLng[0] + ';' + target.lng + ',' + target.lat +
                        '?overview=full&geometries=geojson';

                    fetch(osrmUrl)
                        .then(function(response) {
                            if (!response.ok) throw new Error('Ruta no disponible');
                            return response.json();
                        })
                        .then(function(data) {
                            var route = data.routes && data.routes[0];
                            var coordinates = route && route.geometry && route.geometry.coordinates
                                ? route.geometry.coordinates.map(function(coordinate) {
                                    return [coordinate[1], coordinate[0]];
                                })
                                : [fromLatLng, [target.lat, target.lng]];

                            if (!remainingRouteLine) {
                                remainingRouteLine = L.polyline(coordinates, {
                                    color: '#198754',
                                    weight: 5,
                                    opacity: .9
                                }).addTo(liveLayer);
                            } else {
                                remainingRouteLine.setLatLngs(coordinates);
                            }
                        })
                        .catch(function() {
                            var fallback = [fromLatLng, [target.lat, target.lng]];

                            if (!remainingRouteLine) {
                                remainingRouteLine = L.polyline(fallback, {
                                    color: '#198754',
                                    weight: 4,
                                    opacity: .75,
                                    dashArray: '8, 8'
                                }).addTo(liveLayer);
                            } else {
                                remainingRouteLine.setLatLngs(fallback);
                            }
                        });
                }

                function updateLiveLocation(ubicacion) {
                    if (!ubicacion) {
                        setLiveStatus('Esperando ubicacion del transportista.');
                        return;
                    }

                    var latLng = [ubicacion.latitud, ubicacion.longitud];
                    var liveKey = ubicacion.latitud + ',' + ubicacion.longitud + ',' + ubicacion.fecha;

                    if (!liveMarker) {
                        liveMarker = L.marker(latLng, {
                            icon: currentIcon
                        }).addTo(liveLayer);
                    } else {
                        animateMarkerTo(liveMarker, latLng);
                    }

                    if (liveKey !== lastLiveKey) {
                        lastLiveKey = liveKey;
                        drawRemainingRoute(latLng, targetForLiveState(ubicacion));
                    }

                    var popup = '<strong>Transportista</strong>';
                    if (ubicacion.producto) {
                        popup += '<br>' + ubicacion.producto;
                    }
                    if (ubicacion.fecha_humana) {
                        popup += '<br>Actualizado: ' + ubicacion.fecha_humana;
                    }
                    if (ubicacion.estado_transporte_label) {
                        popup += '<br>Estado: ' + ubicacion.estado_transporte_label;
                    }
                    if (ubicacion.precision_metros) {
                        popup += '<br>Precision: ' + Math.round(ubicacion.precision_metros) + ' m';
                    }
                    liveMarker.bindPopup(popup);

                    var estadoTexto = ubicacion.estado_transporte_label || (ubicacion.tipo_recorrido === 'devolucion' ? 'En devolucion' : 'En entrega');
                    setLiveStatus(estadoTexto + (ubicacion.fecha_humana ? '. Ultima actualizacion: ' + ubicacion.fecha_humana : '.'));
                }

                function refreshLiveLocation() {
                    if (trackingBusy || document.hidden) {
                        scheduleTracking();
                        return;
                    }

                    if (!selectedRoute || !selectedRoute.trackingUrl) {
                        setLiveStatus('Este producto todavia no tiene seguimiento activo.');
                        scheduleTracking();
                        return;
                    }

                    trackingBusy = true;
                    fetch(selectedRoute.trackingUrl, {
                        headers: {
                            'Accept': 'application/json'
                        }
                    })
                        .then(function(response) {
                            if (!response.ok) {
                                throw new Error('No se pudo consultar la ubicacion');
                            }

                            return response.json();
                        })
                        .then(function(data) {
                            updateLiveLocation(data.ubicacion);
                        })
                        .catch(function() {
                            setLiveStatus('No se pudo actualizar la ubicacion en este momento.');
                        })
                        .finally(function() {
                            trackingBusy = false;
                            scheduleTracking();
                        });
                }

                function scheduleTracking() {
                    clearTimeout(trackingTimer);
                    trackingTimer = setTimeout(refreshLiveLocation, 2500);
                }

	                document.addEventListener('visibilitychange', function() {
	                    if (!document.hidden) {
	                        clearTimeout(trackingTimer);
	                        refreshLiveLocation();
	                    }
	                });

                    document.getElementById('pedido-map-expand')?.addEventListener('click', openPedidoMapFullscreen);
                    document.getElementById('pedido-map-close')?.addEventListener('click', closePedidoMapFullscreen);
                    document.addEventListener('keydown', function(event) {
                        if (event.key === 'Escape' && document.body.classList.contains('order-map-fullscreen-open')) {
                            closePedidoMapFullscreen();
                        }
                    });

                document.querySelectorAll('[data-route-option]').forEach(function(button) {
                    button.addEventListener('click', function() {
                        var nextRoute = routeOptions.find(function(option) {
                            return String(option.id) === String(button.dataset.routeOption);
                        });

                        selectedRoute = nextRoute || null;
                        resetLiveTracking();
                        drawSelectedRoute(selectedRoute);
                        refreshLiveLocation();
                    });
                });

                drawSelectedRoute(selectedRoute);
                refreshLiveLocation();
            });
        </script>
    @endif

	    <script>
	        document.addEventListener('DOMContentLoaded', function() {
            function bindSignatureCanvas(canvas) {
                var ctx = canvas.getContext('2d');
                var drawing = false;
                var signed = false;

                ctx.lineWidth = 2.4;
                ctx.lineCap = 'round';
                ctx.strokeStyle = '#172817';

                function point(event) {
                    var rect = canvas.getBoundingClientRect();
                    var source = event.touches ? event.touches[0] : event;
                    return {
                        x: source.clientX - rect.left,
                        y: source.clientY - rect.top
                    };
                }

                function start(event) {
                    event.preventDefault();
                    drawing = true;
                    signed = true;
                    var p = point(event);
                    ctx.beginPath();
                    ctx.moveTo(p.x, p.y);
                }

                function move(event) {
                    if (!drawing) return;
                    event.preventDefault();
                    var p = point(event);
                    ctx.lineTo(p.x, p.y);
                    ctx.stroke();
                }

                function stop() {
                    drawing = false;
                }

                canvas.addEventListener('mousedown', start);
                canvas.addEventListener('mousemove', move);
                window.addEventListener('mouseup', stop);
                canvas.addEventListener('touchstart', start, { passive: false });
                canvas.addEventListener('touchmove', move, { passive: false });
                canvas.addEventListener('touchend', stop);

                return {
                    isSigned: function() { return signed; },
                    clear: function() {
                        signed = false;
                        ctx.clearRect(0, 0, canvas.width, canvas.height);
                    },
                    data: function() { return canvas.toDataURL('image/png'); }
                };
            }

            document.querySelectorAll('form.buyer-signature-form').forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    event.preventDefault();

                    if (form.dataset.submitting === 'true') {
                        return;
                    }

                    var buyerSignaturePad = null;

                    Swal.fire({
                        title: 'Firma de recepcion',
                        html: '<p class="text-muted mb-2">Firma para confirmar que recibiste: <strong>' +
                            (form.dataset.productName || 'tu pedido') +
                            '</strong></p><canvas id="buyer-signature-canvas" width="520" height="180" style="width:100%;max-width:520px;border:1px solid #cfdccc;border-radius:6px;background:#fff"></canvas>' +
                            '<button type="button" id="buyer-signature-clear" class="btn btn-sm btn-light mt-2">Limpiar firma</button>',
                        showCancelButton: true,
                        confirmButtonText: 'Confirmar recepcion',
                        cancelButtonText: 'Aun no',
                        confirmButtonColor: '#238647',
                        cancelButtonColor: '#6c757d',
                        focusConfirm: false,
                        didOpen: function() {
                            buyerSignaturePad = bindSignatureCanvas(document.getElementById('buyer-signature-canvas'));
                            document.getElementById('buyer-signature-clear').addEventListener('click', function() {
                                buyerSignaturePad.clear();
                            });
                        },
                        preConfirm: function() {
                            if (!buyerSignaturePad || !buyerSignaturePad.isSigned()) {
                                Swal.showValidationMessage('Dibuja tu firma antes de confirmar.');
                                return false;
                            }

                            return buyerSignaturePad.data();
                        }
                    }).then(function(result) {
                        if (!result.isConfirmed) return;

                        form.querySelector('input[name="firma_comprador"]').value = result.value;
                        form.dataset.submitting = 'true';
                        HTMLFormElement.prototype.submit.call(form);
                    });
                });
            });

	            var detailStates = @json($liveDetailStates);

            function applyDetailState(config, data) {
                var transportWrap = document.querySelector('[data-live-transport-wrap="' + config.id + '"]');
                var transportLabel = document.querySelector('[data-live-transport-label="' + config.id + '"]');
                var confirmContainer = document.querySelector('[data-live-confirm-container="' + config.id + '"]');
                var recepcion = document.querySelector('[data-live-recepcion="' + config.id + '"]');
                var recepcionDate = document.querySelector('[data-live-recepcion-date="' + config.id + '"]');

                if (transportLabel && data.estado_transporte_label) {
                    transportLabel.textContent = data.estado_transporte_label;
                }

                if (transportWrap) {
                    transportWrap.style.display = data.estado_transporte_label ? '' : 'none';
                }

                if (confirmContainer) {
                    confirmContainer.style.display = data.puede_confirmar_recepcion ? '' : 'none';
                }

                if (recepcion) {
                    recepcion.style.display = data.recepcion_confirmada_at ? '' : 'none';
                }

                if (recepcionDate) {
                    recepcionDate.textContent = data.recepcion_confirmada_at ? 'el ' + data.recepcion_confirmada_at : '';
                }
            }

            function refreshDetailStates() {
                detailStates.forEach(function(config) {
                    fetch(config.url, {
                        headers: {
                            'Accept': 'application/json'
                        }
                    })
                        .then(function(response) {
                            if (!response.ok) {
                                throw new Error('No se pudo consultar estado');
                            }

                            return response.json();
                        })
                        .then(function(data) {
                            if (data.ok) {
                                applyDetailState(config, data);
                            }
                        })
                        .catch(function() {});
                });
            }

            if (detailStates.length) {
                refreshDetailStates();
                setInterval(refreshDetailStates, 5000);
            }
        });
    </script>
@endsection
