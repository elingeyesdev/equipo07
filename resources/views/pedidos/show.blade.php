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
                        <div id="pedido-destino-map" class="mt-3"
                            style="height: 320px; width: 100%; border-radius: 8px; overflow: hidden;"></div>
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
                                                        method="POST" class="mt-2 question-confirm-form"
                                                        id="buyer-receive-form-{{ $detalle->id }}"
                                                        style="{{ $detalle->estado_transporte_actual === 'esperando_confirmacion' ? '' : 'display:none' }}"
                                                        data-confirm-title="¿Recibiste tu pedido?"
                                                        data-confirm-text="Confirma únicamente cuando el producto ya esté contigo. Al continuar, la entrega quedará registrada como recibida."
                                                        data-confirm-button="Sí, recibí mi pedido"
                                                        data-cancel-button="Aún no"
                                                        data-loading-text="Confirmando la recepción...">
                                                        @csrf
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var statesUrl = @json(route('pedidos.tracking.estados', $pedido, false));
            var transportStatePhases = @json(\App\Models\PedidoDetalle::transporteEstadoFases());
            var statusBusy = false;
            var statusTimer = null;

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
                var routeSummary = document.getElementById('pedido-route-summary');
                var routeSummaryText = document.getElementById('pedido-route-summary-text');
                var mapsLink = document.getElementById('pedido-google-maps-link');
                var liveMarker = null;
                var liveLine = null;
                var livePoints = [];
                var lastLiveKey = null;
                var trackingBusy = false;
                var trackingTimer = null;
                var routeRequestId = 0;
                var map = L.map('pedido-destino-map').setView([destinoLat, destinoLng], 16);
                var routeLayer = L.layerGroup().addTo(map);
                var liveLayer = L.layerGroup().addTo(map);
                var currentIcon = L.divIcon({
                    className: '',
                    html: '<span class="tracking-pin tracking-pin--current"><i class="fas fa-location-arrow"></i></span>',
                    iconSize: [30, 30],
                    iconAnchor: [15, 15],
                    popupAnchor: [0, -16]
                });
                var targetIcon = L.divIcon({
                    className: '',
                    html: '<span class="tracking-pin tracking-pin--target"><i class="fas fa-home"></i></span>',
                    iconSize: [30, 30],
                    iconAnchor: [15, 15],
                    popupAnchor: [0, -16]
                });
                var originIcon = L.divIcon({
                    className: '',
                    html: '<span class="tracking-pin tracking-pin--origin"><i class="fas fa-box"></i></span>',
                    iconSize: [30, 30],
                    iconAnchor: [15, 15],
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
                    liveLine = null;
                    livePoints = [];
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
                }

                function updateLiveLocation(ubicacion) {
                    if (!ubicacion) {
                        setLiveStatus('Esperando ubicacion del transportista.');
                        return;
                    }

                    var latLng = [ubicacion.latitud, ubicacion.longitud];
                    var liveKey = ubicacion.latitud + ',' + ubicacion.longitud + ',' + ubicacion.fecha;

                    if (liveKey !== lastLiveKey) {
                        livePoints.push(latLng);
                        lastLiveKey = liveKey;
                    }

                    if (!liveMarker) {
                        liveMarker = L.marker(latLng, {
                            icon: currentIcon
                        }).addTo(liveLayer);
                    } else {
                        liveMarker.setLatLng(latLng);
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

                    if (!liveLine) {
                        liveLine = L.polyline(livePoints, {
                            color: '#007bff',
                            weight: 5,
                            opacity: .85
                        }).addTo(liveLayer);
                    } else {
                        liveLine.setLatLngs(livePoints);
                    }

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
                    trackingTimer = setTimeout(refreshLiveLocation, 12000);
                }

                document.addEventListener('visibilitychange', function() {
                    if (!document.hidden) {
                        clearTimeout(trackingTimer);
                        refreshLiveLocation();
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
