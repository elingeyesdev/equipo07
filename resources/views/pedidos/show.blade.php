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
        $detalleConUbicacion = $pedido->detalles->first(
            fn($detalle) => $detalle->estado_solicitud === 'aceptada'
                && $detalle->product_latitud
                && $detalle->product_longitud
        ) ?: $pedido->detalles->first(fn($detalle) => $detalle->product_latitud && $detalle->product_longitud);
        $mapsUrl = null;

        if ($pedido->destino_latitud && $pedido->destino_longitud) {
            $mapsUrl = $detalleConUbicacion
                ? 'https://www.google.com/maps/dir/?api=1&origin=' . $detalleConUbicacion->product_latitud . ',' . $detalleConUbicacion->product_longitud . '&destination=' . $pedido->destino_latitud . ',' . $pedido->destino_longitud . '&travelmode=driving'
                : 'https://www.google.com/maps/search/?api=1&query=' . $pedido->destino_latitud . ',' . $pedido->destino_longitud;
        }

        $trackingUrl = $detalleConUbicacion
            ? route('pedidos.detalles.tracking.latest', $detalleConUbicacion, false)
            : route('pedidos.tracking.latest', $pedido, false);

        $liveDetailStates = $pedido->detalles
            ->map(fn($detalle) => [
                'id' => $detalle->id,
                'url' => route('pedidos.detalles.estadoTransporte', $detalle, false),
                'rentalStates' => array_keys(\App\Models\PedidoDetalle::alquilerEstados()),
                'isRental' => $detalle->es_alquiler_maquinaria,
            ])
            ->values();
    @endphp

    <div class="container-fluid orders-page">
        <style>
            .order-rental-tracking {
                min-width: 360px;
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
                grid-template-columns: repeat(7, minmax(86px, 1fr));
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
                .order-rental-tracking {
                    min-width: 0;
                }

                .order-rental-tracking__steps {
                    grid-template-columns: repeat(2, minmax(0, 1fr));
                }
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
                        <div id="pedido-destino-map" class="mt-3"
                            style="height: 320px; width: 100%; border-radius: 8px; overflow: hidden;"></div>
                        <div id="pedido-live-tracking" class="alert alert-light border mt-3 mb-0">
                            <strong><i class="fas fa-location-arrow mr-1"></i>Seguimiento en vivo:</strong>
                            <span id="pedido-live-status">Esperando ubicacion del transportista.</span>
                        </div>
                        @if ($detalleConUbicacion && !is_null($detalleConUbicacion->distancia_destino_km))
                            <div class="small text-muted mt-2">
                                <i class="fas fa-route mr-1"></i>
                                Distancia aproximada desde {{ $detalleConUbicacion->nombre_producto }}:
                                {{ number_format($detalleConUbicacion->distancia_destino_km, 1) }} km.
                            </div>
                        @elseif (!$detalleConUbicacion)
                            <div class="small text-muted mt-2">
                                <i class="fas fa-info-circle mr-1"></i>
                                No se pudo dibujar la ruta porque el producto no tiene coordenadas registradas.
                            </div>
                        @endif
                        <a class="btn btn-sm btn-outline-success mt-2" target="_blank"
                            href="{{ $mapsUrl }}">
                            <i class="fas fa-external-link-alt mr-1"></i>{{ $detalleConUbicacion ? 'Abrir ruta en Google Maps' : 'Abrir mapa' }}
                        </a>
                    @endif
                </div>

                <div class="table-responsive orders-table-wrap">
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
                                    $alquilerEstados = \App\Models\PedidoDetalle::alquilerEstados();
                                    $estadoAlquilerActual = $detalle->estado_alquiler_actual;
                                    $estadoKeys = array_keys($alquilerEstados);
                                    $estadoActualIndex = $estadoAlquilerActual ? array_search($estadoAlquilerActual, $estadoKeys, true) : false;
                                @endphp
                                <tr @if($detalle->estado_solicitud === 'aceptada')
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
                                        @if ($detalle->estado_solicitud === 'aceptada')
                                            @php
                                                $deliveryFlow = ['aceptado', 'preparando', 'en_camino_entrega', 'esperando_confirmacion', 'entregado'];
                                                $deliveryCurrent = $detalle->estado_transporte_actual === 'asignado'
                                                    ? 'aceptado'
                                                    : $detalle->estado_transporte_actual;
                                                $deliveryIndex = array_search($deliveryCurrent, $deliveryFlow, true);
                                            @endphp
                                            <div class="order-rental-tracking">
                                                <div class="order-rental-tracking__title">
                                                    <span><i class="fas fa-truck mr-1"></i>Seguimiento de entrega</span>
                                                    <span class="text-success"
                                                        id="buyer-delivery-status-{{ $detalle->id }}">{{ $detalle->estado_transporte_label }}</span>
                                                </div>
                                                <div class="order-rental-tracking__steps"
                                                    style="grid-template-columns: repeat(5, minmax(86px, 1fr));">
                                                    @foreach ($deliveryFlow as $deliveryStepIndex => $deliveryState)
                                                        @php
                                                            $deliveryClass = '';
                                                            if ($deliveryIndex !== false && $deliveryStepIndex < $deliveryIndex) {
                                                                $deliveryClass = 'is-done';
                                                            } elseif ($deliveryState === $deliveryCurrent) {
                                                                $deliveryClass = 'is-current';
                                                            }
                                                        @endphp
                                                        <div class="order-rental-tracking__step {{ $deliveryClass }}"
                                                            data-delivery-detail="{{ $detalle->id }}"
                                                            data-state="{{ $deliveryState }}">
                                                            {{ \App\Services\TransporteAccesoService::ESTADOS_DELIVERY[$deliveryState] }}
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
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
                                        @if($detalle->product_type === 'organico'
                                            && $detalle->estado_solicitud === 'aceptada'
                                            && in_array($detalle->estado_transporte_actual, ['entregado', 'cancelado'], true))
                                            @include('organicos.partials.postventa', ['detalle' => $detalle, 'modo' => 'comprador'])
                                        @endif
                                        @if ($isMaquinaria)
                                            <div class="order-rental-tracking" data-live-rental-tracking="{{ $detalle->id }}"
                                                style="{{ $detalle->estado_solicitud === 'aceptada' && $detalle->estado_transporte_actual && $detalle->estado_transporte_actual !== 'asignado' ? '' : 'display: none;' }}">
                                                <div class="order-rental-tracking__title">
                                                    <span><i class="fas fa-route mr-1"></i>Seguimiento del alquiler</span>
                                                    <span class="text-success" data-live-rental-label="{{ $detalle->id }}">
                                                        {{ $detalle->estado_alquiler_label ?: 'Pendiente' }}
                                                    </span>
                                                </div>
                                                <div class="order-rental-tracking__steps">
                                                    @foreach ($alquilerEstados as $estadoKey => $estadoLabel)
                                                        @php
                                                            $index = array_search($estadoKey, $estadoKeys, true);
                                                            $stepClass = '';
                                                            if ($estadoActualIndex !== false && $index < $estadoActualIndex) {
                                                                $stepClass = 'is-done';
                                                            } elseif ($estadoKey === $estadoAlquilerActual) {
                                                            $stepClass = 'is-current';
                                                        }
                                                    @endphp
                                                        <div class="order-rental-tracking__step {{ $stepClass }}"
                                                            data-live-rental-step="{{ $detalle->id }}"
                                                            data-rental-state="{{ $estadoKey }}">
                                                            {{ $estadoLabel }}
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
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
            var deliveryFlow = ['aceptado', 'preparando', 'en_camino_entrega', 'esperando_confirmacion', 'entregado'];
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

                var currentIndex = deliveryFlow.indexOf(detail.estado);
                document.querySelectorAll('[data-delivery-detail="' + detail.detalle_id + '"]').forEach(function(step) {
                    var index = deliveryFlow.indexOf(step.dataset.state);
                    step.classList.toggle('is-done', currentIndex >= 0 && index < currentIndex);
                    step.classList.toggle('is-current', step.dataset.state === detail.estado);
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
                var productoLat = @json($detalleConUbicacion?->product_latitud);
                var productoLng = @json($detalleConUbicacion?->product_longitud);
                var googleMapsUrl = @json($mapsUrl);
                var trackingUrl = @json($trackingUrl);
                var trackingDetailId = @json($detalleConUbicacion?->id);
                var currentTrackingState = @json($detalleConUbicacion?->estado_transporte_actual);
                var deliveryFlow = ['aceptado', 'preparando', 'en_camino_entrega', 'esperando_confirmacion', 'entregado'];
                var liveStatus = document.getElementById('pedido-live-status');
                var liveMarker = null;
                var liveLine = null;
                var livePoints = [];
                var lastLiveKey = null;
                var trackingBusy = false;
                var trackingTimer = null;
                var map = L.map('pedido-destino-map').setView([destinoLat, destinoLng], 16);
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

                L.marker([destinoLat, destinoLng], {
                    icon: targetIcon
                })
                    .addTo(map)
                    .bindPopup('<strong>Destino del comprador</strong><br>' + @json($pedido->destino_entrega))
                    .openPopup();

                if (productoLat && productoLng) {
                    L.marker([productoLat, productoLng], {
                        icon: originIcon
                    })
                        .addTo(map)
                        .bindPopup('<strong>Producto</strong><br>' + @json($detalleConUbicacion?->nombre_producto));

                    function drawFallbackLine() {
                        var fallbackLine = L.polyline([
                            [productoLat, productoLng],
                            [destinoLat, destinoLng]
                        ], {
                            color: '#28a745',
                            weight: 4,
                            opacity: 0.85,
                            dashArray: '8, 8'
                        }).addTo(map);

                        fallbackLine.bindPopup('Distancia aproximada: {{ number_format($detalleConUbicacion?->distancia_destino_km ?? 0, 1) }} km');

                        map.fitBounds(fallbackLine.getBounds(), {
                            padding: [40, 40],
                            maxZoom: 14
                        });
                    }

                    var osrmUrl = 'https://router.project-osrm.org/route/v1/driving/' +
                        productoLng + ',' + productoLat + ';' + destinoLng + ',' + destinoLat +
                        '?overview=full&geometries=geojson';

                    fetch(osrmUrl)
                        .then(function(response) {
                            if (!response.ok) {
                                throw new Error('No se pudo obtener la ruta');
                            }

                            return response.json();
                        })
                        .then(function(data) {
                            var route = data.routes && data.routes[0];

                            if (!route || !route.geometry || !route.geometry.coordinates) {
                                drawFallbackLine();
                                return;
                            }

                            var routeCoordinates = route.geometry.coordinates.map(function(coordinate) {
                                return [coordinate[1], coordinate[0]];
                            });

                            var routeLine = L.polyline(routeCoordinates, {
                                color: '#28a745',
                                weight: 5,
                                opacity: 0.9
                            }).addTo(map);

                            var distanceKm = route.distance ? (route.distance / 1000).toFixed(1) : null;
                            var durationMin = route.duration ? Math.round(route.duration / 60) : null;
                            var popup = '<strong>Ruta vehicular estimada</strong>';

                            if (distanceKm) {
                                popup += '<br>Recorrido: ' + distanceKm + ' km';
                            }

                            if (durationMin) {
                                popup += '<br>Tiempo aprox.: ' + durationMin + ' min';
                            }

                            if (googleMapsUrl) {
                                popup += '<br><a href="' + googleMapsUrl + '" target="_blank" rel="noopener">Abrir en Google Maps</a>';
                            }

                            routeLine.bindPopup(popup);

                            map.fitBounds(routeLine.getBounds(), {
                                padding: [40, 40],
                                maxZoom: 14
                            });
                        })
                        .catch(drawFallbackLine);
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
                        }).addTo(map);
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
                        }).addTo(map);
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

                    trackingBusy = true;
                    fetch(trackingUrl, {
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

                refreshLiveLocation();
            });
        </script>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var detailStates = @json($liveDetailStates);

            function updateRentalSteps(detailId, currentState, states) {
                if (!currentState || !states) {
                    return;
                }

                var currentIndex = states.indexOf(currentState);

                document.querySelectorAll('[data-live-rental-step="' + detailId + '"]').forEach(function(step) {
                    var state = step.getAttribute('data-rental-state');
                    var index = states.indexOf(state);

                    step.classList.remove('is-done', 'is-current');

                    if (currentIndex !== -1 && index < currentIndex) {
                        step.classList.add('is-done');
                    } else if (state === currentState) {
                        step.classList.add('is-current');
                    }
                });
            }

            function applyDetailState(config, data) {
                var transportWrap = document.querySelector('[data-live-transport-wrap="' + config.id + '"]');
                var transportLabel = document.querySelector('[data-live-transport-label="' + config.id + '"]');
                var rentalLabel = document.querySelector('[data-live-rental-label="' + config.id + '"]');
                var rentalTracking = document.querySelector('[data-live-rental-tracking="' + config.id + '"]');
                var confirmContainer = document.querySelector('[data-live-confirm-container="' + config.id + '"]');
                var recepcion = document.querySelector('[data-live-recepcion="' + config.id + '"]');
                var recepcionDate = document.querySelector('[data-live-recepcion-date="' + config.id + '"]');

                if (transportLabel && data.estado_transporte_label) {
                    transportLabel.textContent = data.estado_transporte_label;
                }

                if (transportWrap) {
                    transportWrap.style.display = data.estado_transporte_label ? '' : 'none';
                }

                if (rentalLabel && data.estado_alquiler_label) {
                    rentalLabel.textContent = data.estado_alquiler_label;
                }

                if (rentalTracking) {
                    rentalTracking.style.display = config.isRental
                        && data.estado_solicitud === 'aceptada'
                        && data.estado_transporte
                        && data.estado_transporte !== 'asignado'
                            ? ''
                            : 'none';
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

                updateRentalSteps(config.id, data.estado_alquiler, config.rentalStates);
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
