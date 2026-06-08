@extends('layouts.adminlte')

@section('title', 'GPS Solicitud #' . $solicitud->id)
@section('page_title', 'GPS Solicitud #' . $solicitud->id)

@section('content')
    @php
        $pedido = $solicitud->pedido;
        $transporteEstados = \App\Models\PedidoDetalle::transporteEstados();
        $estadoTransporteActual = $solicitud->estado_transporte_actual;
        $estadoTransporteKeys = array_keys($transporteEstados);
        $estadoTransporteIndex = $estadoTransporteActual ? array_search($estadoTransporteActual, $estadoTransporteKeys, true) : false;
        $estadosDevolucion = [
            'devolucion_solicitada',
            'en_camino_recoger_devolucion',
            'llego_recoger_devolucion',
            'maquinaria_recogida_retorno',
            'en_camino_retorno',
            'llego_retorno',
            'devuelto_vendedor',
        ];
        $tipoRecorrido = in_array($estadoTransporteActual, $estadosDevolucion, true) ? 'devolucion' : 'entrega';
        $destinoEsRetorno = in_array($estadoTransporteActual, ['maquinaria_recogida_retorno', 'en_camino_retorno', 'llego_retorno', 'devuelto_vendedor'], true);
        $destinoLat = $destinoEsRetorno ? $solicitud->product_latitud : $pedido->destino_latitud;
        $destinoLng = $destinoEsRetorno ? $solicitud->product_longitud : $pedido->destino_longitud;
        $destinoLabel = $destinoEsRetorno ? 'Punto de retorno del producto' : 'Destino del comprador';
    @endphp

    <div class="container-fluid">
        <style>
            .driver-gps-card {
                max-width: 980px;
                margin: 0 auto;
            }

            .driver-gps-status {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
                gap: .75rem;
            }

            .driver-gps-status__item {
                padding: .85rem;
                border: 1px solid #e6ece3;
                border-radius: 8px;
                background: #f8fbf6;
            }

            .driver-gps-status__item small {
                display: block;
                color: #6c757d;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: .02em;
            }

            .driver-gps-status__item strong {
                display: block;
                margin-top: .2rem;
                color: #1f2a1b;
                font-size: 1rem;
            }

            .driver-gps-actions {
                display: flex;
                flex-wrap: wrap;
                gap: .6rem;
            }

            .driver-transport-flow {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
                gap: .65rem;
            }

            .driver-transport-flow__step {
                min-height: 76px;
                padding: .65rem;
                border: 1px solid #dfe8dc;
                border-radius: 8px;
                background: #fff;
                color: #687268;
                font-size: .78rem;
                font-weight: 700;
            }

            .driver-transport-flow__step span {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 1.65rem;
                height: 1.65rem;
                margin-bottom: .35rem;
                border-radius: 999px;
                background: #eef4ea;
                color: #3f7e2a;
            }

            .driver-transport-flow__step.is-done,
            .driver-transport-flow__step.is-current {
                border-color: rgba(63, 126, 42, .35);
                color: #1f2a1b;
            }

            .driver-transport-flow__step.is-current {
                background: #eef8ea;
                box-shadow: 0 8px 18px rgba(63, 126, 42, .08);
            }

            .driver-transport-flow__step.is-done span,
            .driver-transport-flow__step.is-current span {
                color: #fff;
                background: #2f7d24;
            }

            #driver-gps-map {
                min-height: 420px;
                border-radius: 8px;
                overflow: hidden;
            }

            .tracking-pin {
                display: flex;
                align-items: center;
                justify-content: center;
                width: 32px;
                height: 32px;
                border: 3px solid #fff;
                border-radius: 999px;
                color: #fff;
                box-shadow: 0 4px 12px rgba(0, 0, 0, .25);
            }

            .tracking-map-marker {
                display: inline-flex;
                align-items: center;
                gap: .35rem;
                padding: .18rem .5rem .18rem .18rem;
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

            .tracking-map-legend {
                display: flex;
                flex-wrap: wrap;
                gap: .5rem;
                margin-bottom: .75rem;
            }

            .tracking-map-legend__item {
                display: inline-flex;
                align-items: center;
                gap: .35rem;
                padding: .35rem .55rem;
                border: 1px solid #dfe8dc;
                border-radius: 999px;
                background: #fff;
                color: #1f2a1b;
                font-size: .78rem;
                font-weight: 700;
            }

            .tracking-map-legend__line {
                display: inline-block;
                width: 1.8rem;
                height: .28rem;
                border-radius: 999px;
                background: #198754;
            }

            .tracking-map-legend__line--actual {
                background: #0d6efd;
            }

            .tracking-pin--current {
                background: #0d6efd;
            }

            .tracking-pin--target {
                background: #198754;
            }

            .tracking-pin--product {
                background: #fd7e14;
            }

            .tracking-pin--customer {
                background: #198754;
            }

            .tracking-pin--return {
                background: #6f42c1;
            }

            @media (max-width: 576px) {
                .driver-gps-actions .btn {
                    width: 100%;
                }

                #driver-gps-map {
                    min-height: 360px;
                }
            }
        </style>

        <div class="card driver-gps-card">
            <div class="card-header">
                <h3 class="card-title mb-0">
                    <i class="fas fa-location-arrow mr-1"></i>Seguimiento GPS
                </h3>
            </div>

            <div class="card-body">
                <div class="alert alert-info">
                    <strong>{{ $solicitud->nombre_producto }}</strong><br>
                    Abre esta pantalla desde el celular del transportista, permite la ubicacion y presiona iniciar.
                    Mientras la pagina siga abierta, el cliente vera el recorrido casi en tiempo real.
                </div>

                <div class="driver-gps-status mb-3">
                    <div class="driver-gps-status__item">
                        <small>Recorrido</small>
                        <strong>{{ $tipoRecorrido === 'devolucion' ? 'Devolucion' : 'Entrega' }}</strong>
                    </div>
                    <div class="driver-gps-status__item">
                        <small>Estado</small>
                        <strong id="gps-status">Detenido</strong>
                    </div>
                    <div class="driver-gps-status__item">
                        <small>Estado transporte</small>
                        <strong id="transport-state-current-label">{{ $solicitud->estado_transporte_label }}</strong>
                    </div>
                    <div class="driver-gps-status__item">
                        <small>Ultimo envio</small>
                        <strong id="gps-last-send">Sin datos</strong>
                    </div>
                    <div class="driver-gps-status__item">
                        <small>Precision</small>
                        <strong id="gps-accuracy">Sin datos</strong>
                    </div>
                </div>

                <div class="driver-gps-actions mb-3">
                    <button type="button" id="gps-start" class="btn btn-success">
                        <i class="fas fa-play mr-1"></i>Iniciar recorrido
                    </button>
                    <button type="button" id="gps-stop" class="btn btn-outline-secondary" disabled>
                        <i class="fas fa-stop mr-1"></i>Detener
                    </button>
                    <a href="{{ auth()->user()->isTransportista() ? route('transportista.envios.show', $solicitud) : route('vendedor.solicitudes.show', $solicitud) }}" class="btn btn-light">
                        <i class="fas fa-arrow-left mr-1"></i>Volver
                    </a>
                </div>

                <div class="card border mb-3">
                    <div class="card-body">
                        <div class="d-flex flex-wrap justify-content-between align-items-start mb-3">
                            <div>
                                <h5 class="mb-1 font-weight-bold">
                                    <i class="fas fa-truck mr-1"></i>Flujo del transportista
                                </h5>
                                <small class="text-muted">Avanza el estado conforme haces la recogida y la entrega.</small>
                            </div>
                            <span class="badge badge-primary mt-2 mt-md-0" id="transport-state-badge">{{ $solicitud->estado_transporte_label }}</span>
                        </div>

                        <div class="driver-transport-flow mb-3">
                            @foreach ($transporteEstados as $estadoKey => $estadoLabel)
                                @php
                                    $index = array_search($estadoKey, $estadoTransporteKeys, true);
                                    $stepClass = '';
                                    if ($estadoTransporteIndex !== false && $index < $estadoTransporteIndex) {
                                        $stepClass = 'is-done';
                                    } elseif ($estadoKey === $estadoTransporteActual) {
                                        $stepClass = 'is-current';
                                    }
                                @endphp
                                <div class="driver-transport-flow__step {{ $stepClass }}" data-transport-step="{{ $estadoKey }}">
                                    <span>
                                        @if ($stepClass === 'is-done')
                                            <i class="fas fa-check"></i>
                                        @else
                                            {{ $index + 1 }}
                                        @endif
                                    </span>
                                    <div>{{ $estadoLabel }}</div>
                                </div>
                            @endforeach
                        </div>

                        <div id="transport-state-control">
                            @if ($solicitud->siguiente_estado_transporte)
                                <form action="{{ route('transportista.envios.tracking.estado', $solicitud) }}" method="POST"
                                    class="mb-0" id="transport-state-form">
                                    @csrf
                                    <button type="submit" class="btn btn-primary" id="transport-state-button">
                                        <i class="fas fa-arrow-right mr-1"></i>
                                        <span>Marcar: {{ $solicitud->siguiente_estado_transporte_label }}</span>
                                    </button>
                                </form>
                            @elseif ($solicitud->estado_transporte_actual === 'esperando_confirmacion')
                                <div class="alert alert-warning mb-0">
                                    <i class="fas fa-user-check mr-1"></i>
                                    Esperando que el comprador confirme la recepcion desde su pedido.
                                </div>
                            @else
                                <div class="alert alert-success mb-0">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    La entrega ya no tiene pasos de transporte pendientes.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div id="gps-message" class="alert alert-light border">
                    Esperando inicio del recorrido.
                </div>

                <div class="tracking-map-legend">
                    <span class="tracking-map-legend__item">
                        <span class="tracking-pin tracking-pin--current"><i class="fas fa-truck"></i></span>
                        Transportista
                    </span>
                    <span class="tracking-map-legend__item">
                        <span class="tracking-pin tracking-pin--product"><i class="fas fa-box"></i></span>
                        Producto / vendedor
                    </span>
                    <span class="tracking-map-legend__item">
                        <span class="tracking-pin tracking-pin--customer"><i class="fas fa-home"></i></span>
                        Destino comprador
                    </span>
                    <span class="tracking-map-legend__item">
                        <span class="tracking-pin tracking-pin--return"><i class="fas fa-warehouse"></i></span>
                        Retorno vendedor
                    </span>
                    <span class="tracking-map-legend__item">
                        <span class="tracking-map-legend__line"></span>
                        Ruta sugerida
                    </span>
                    <span class="tracking-map-legend__item">
                        <span class="tracking-map-legend__line tracking-map-legend__line--actual"></span>
                        Recorrido real
                    </span>
                </div>

                <div id="driver-gps-map"></div>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var startBtn = document.getElementById('gps-start');
            var stopBtn = document.getElementById('gps-stop');
            var statusEl = document.getElementById('gps-status');
            var lastSendEl = document.getElementById('gps-last-send');
            var accuracyEl = document.getElementById('gps-accuracy');
            var messageEl = document.getElementById('gps-message');
            var watchId = null;
            var lastSentAt = 0;
            var lastRouteAt = 0;
            var sendEveryMs = 10000;
            var routeEveryMs = 8000;
            var routePoints = [];
            var currentMarker = null;
            var targetMarker = null;
            var routeLine = null;
            var navigationRouteLine = null;
            var transportStateKeys = @json($estadoTransporteKeys);
            var currentTransportState = @json($estadoTransporteActual);
            var transportStateForm = document.getElementById('transport-state-form');
            var transportStateButton = document.getElementById('transport-state-button');
            var transportStateBadge = document.getElementById('transport-state-badge');
            var transportStateControl = document.getElementById('transport-state-control');
            var transportStateCurrentLabel = document.getElementById('transport-state-current-label');
            var productPoint = {
                lat: @json($solicitud->product_latitud ? (float) $solicitud->product_latitud : null),
                lng: @json($solicitud->product_longitud ? (float) $solicitud->product_longitud : null),
                label: 'Punto de recogida del producto',
                type: 'product'
            };
            var customerPoint = {
                lat: @json($pedido->destino_latitud ? (float) $pedido->destino_latitud : null),
                lng: @json($pedido->destino_longitud ? (float) $pedido->destino_longitud : null),
                label: 'Destino del comprador',
                type: 'customer'
            };
            var lastKnownLatLng = null;
            var mapCenter = productPoint.lat && productPoint.lng
                ? [productPoint.lat, productPoint.lng]
                : (customerPoint.lat && customerPoint.lng ? [customerPoint.lat, customerPoint.lng] : [-17.7833, -63.1821]);
            var map = L.map('driver-gps-map').setView(mapCenter, 14);

            function trackingIcon(type, icon, label) {
                return L.divIcon({
                    className: '',
                    html: '<div class="tracking-map-marker">' +
                        '<span class="tracking-pin tracking-pin--' + type + '"><i class="fas ' + icon + '"></i></span>' +
                        '<small>' + label + '</small>' +
                    '</div>',
                    iconSize: [160, 40],
                    iconAnchor: [18, 18],
                    popupAnchor: [0, -18]
                });
            }

            var currentIcon = L.divIcon({
                className: '',
                html: '<div class="tracking-map-marker">' +
                    '<span class="tracking-pin tracking-pin--current"><i class="fas fa-truck"></i></span>' +
                    '<small>Transportista</small>' +
                '</div>',
                iconSize: [160, 40],
                iconAnchor: [18, 18],
                popupAnchor: [0, -18]
            });
            var productIcon = trackingIcon('product', 'fa-box', 'Producto');
            var customerIcon = trackingIcon('customer', 'fa-home', 'Comprador');
            var returnIcon = trackingIcon('return', 'fa-warehouse', 'Retorno');

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            function routeTypeForState(estado) {
                return ['devolucion_solicitada', 'en_camino_recoger_devolucion', 'llego_recoger_devolucion',
                    'maquinaria_recogida_retorno', 'en_camino_retorno', 'llego_retorno', 'devuelto_vendedor'
                ].indexOf(estado) !== -1 ? 'devolucion' : 'entrega';
            }

            function targetForState(estado) {
                if (['asignado', 'en_camino_recogida'].indexOf(estado) !== -1) {
                    return productPoint;
                }

                if (['maquinaria_recogida_retorno', 'en_camino_retorno', 'llego_retorno', 'devuelto_vendedor'].indexOf(estado) !== -1) {
                    return {
                        lat: productPoint.lat,
                        lng: productPoint.lng,
                        label: 'Punto de retorno del producto',
                        type: 'return'
                    };
                }

                return customerPoint;
            }

            function iconForTarget(target) {
                if (target.type === 'product') {
                    return productIcon;
                }

                if (target.type === 'return') {
                    return returnIcon;
                }

                return customerIcon;
            }

            function setTargetMarker(estado, redrawRoute) {
                var target = targetForState(estado);

                if (!target || !target.lat || !target.lng) {
                    return;
                }

                var latLng = [target.lat, target.lng];

                if (!targetMarker) {
                    targetMarker = L.marker(latLng, {
                        icon: iconForTarget(target)
                    }).addTo(map);
                } else {
                    targetMarker.setLatLng(latLng);
                    targetMarker.setIcon(iconForTarget(target));
                }

                targetMarker.bindPopup('<strong>' + target.label + '</strong>');

                if (lastKnownLatLng && redrawRoute) {
                    drawNavigationRoute(lastKnownLatLng, true);
                }
            }

            function drawNavigationRoute(originLatLng, force) {
                var now = Date.now();
                var target = targetForState(currentTransportState);

                if (!originLatLng || !target || !target.lat || !target.lng) {
                    return;
                }

                if (!force && now - lastRouteAt < routeEveryMs) {
                    return;
                }

                lastRouteAt = now;

                var osrmUrl = 'https://router.project-osrm.org/route/v1/driving/' +
                    originLatLng[1] + ',' + originLatLng[0] + ';' + target.lng + ',' + target.lat +
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
                            throw new Error('Ruta no disponible');
                        }

                        var routeCoordinates = route.geometry.coordinates.map(function(coordinate) {
                            return [coordinate[1], coordinate[0]];
                        });

                        if (!navigationRouteLine) {
                            navigationRouteLine = L.polyline(routeCoordinates, {
                                color: '#198754',
                                weight: 6,
                                opacity: .85
                            }).addTo(map);
                        } else {
                            navigationRouteLine.setLatLngs(routeCoordinates);
                        }

                        var bounds = L.latLngBounds(routeCoordinates);
                        map.fitBounds(bounds, {
                            padding: [35, 35],
                            maxZoom: 15
                        });
                    })
                    .catch(function() {
                        var fallbackCoordinates = [
                            originLatLng,
                            [target.lat, target.lng]
                        ];

                        if (!navigationRouteLine) {
                            navigationRouteLine = L.polyline(fallbackCoordinates, {
                                color: '#198754',
                                weight: 5,
                                opacity: .75,
                                dashArray: '8, 8'
                            }).addTo(map);
                        } else {
                            navigationRouteLine.setLatLngs(fallbackCoordinates);
                        }
                    });
            }

            setTargetMarker(currentTransportState, false);

            function setMessage(type, text) {
                messageEl.className = 'alert alert-' + type;
                messageEl.textContent = text;
            }

            function renderTransportFlow(estadoActual) {
                var currentIndex = transportStateKeys.indexOf(estadoActual);

                document.querySelectorAll('[data-transport-step]').forEach(function(step) {
                    var estado = step.getAttribute('data-transport-step');
                    var index = transportStateKeys.indexOf(estado);
                    var marker = step.querySelector('span');

                    step.classList.remove('is-done', 'is-current');

                    if (currentIndex !== -1 && index < currentIndex) {
                        step.classList.add('is-done');
                        if (marker) {
                            marker.innerHTML = '<i class="fas fa-check"></i>';
                        }
                    } else if (estado === estadoActual) {
                        step.classList.add('is-current');
                        if (marker) {
                            marker.textContent = index + 1;
                        }
                    } else if (marker) {
                        marker.textContent = index + 1;
                    }
                });
            }

            function renderTransportControl(data) {
                currentTransportState = data.estado;

                if (transportStateBadge) {
                    transportStateBadge.textContent = data.estado_label;
                }

                if (transportStateCurrentLabel) {
                    transportStateCurrentLabel.textContent = data.estado_label;
                }

                renderTransportFlow(data.estado);
                setTargetMarker(data.estado, true);

                if (!transportStateControl) {
                    return;
                }

                if (data.siguiente_estado) {
                    transportStateControl.innerHTML =
                        '<form action="{{ route('transportista.envios.tracking.estado', $solicitud, false) }}" method="POST" class="mb-0" id="transport-state-form">' +
                            '<input type="hidden" name="_token" value="{{ csrf_token() }}">' +
                            '<button type="submit" class="btn btn-primary" id="transport-state-button">' +
                                '<i class="fas fa-arrow-right mr-1"></i><span>Marcar: ' + data.siguiente_estado_label + '</span>' +
                            '</button>' +
                        '</form>';
                    bindTransportStateForm();
                    return;
                }

                if (data.estado === 'esperando_confirmacion') {
                    transportStateControl.innerHTML =
                        '<div class="alert alert-warning mb-0">' +
                            '<i class="fas fa-user-check mr-1"></i> Esperando que el comprador confirme la recepcion desde su pedido.' +
                        '</div>';
                    return;
                }

                transportStateControl.innerHTML =
                    '<div class="alert alert-success mb-0">' +
                        '<i class="fas fa-check-circle mr-1"></i> La entrega ya no tiene pasos de transporte pendientes.' +
                    '</div>';
            }

            function bindTransportStateForm() {
                transportStateForm = document.getElementById('transport-state-form');
                transportStateButton = document.getElementById('transport-state-button');

                if (!transportStateForm) {
                    return;
                }

                transportStateForm.addEventListener('submit', function(event) {
                    event.preventDefault();

                    if (transportStateButton) {
                        transportStateButton.disabled = true;
                    }

                    fetch(transportStateForm.action, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': @json(csrf_token())
                        },
                        body: new FormData(transportStateForm)
                    })
                        .then(function(response) {
                            return response.json().then(function(data) {
                                if (!response.ok) {
                                    throw data;
                                }

                                return data;
                            });
                        })
                        .then(function(data) {
                            setMessage('success', data.message || 'Estado actualizado correctamente.');
                            renderTransportControl(data);
                        })
                        .catch(function(error) {
                            setMessage('danger', error.message || 'No se pudo actualizar el estado.');
                            if (transportStateButton) {
                                transportStateButton.disabled = false;
                            }
                        });
                });
            }

            function geolocationErrorMessage(error) {
                if (!window.isSecureContext) {
                    return 'Chrome bloquea el GPS porque la pagina no esta en HTTPS. Usa ngrok o una URL segura para probar desde el celular.';
                }

                if (!error) {
                    return 'No se pudo obtener la ubicacion.';
                }

                if (error.code === error.PERMISSION_DENIED) {
                    return 'Permiso GPS denegado. En Chrome abre ajustes del sitio y permite Ubicacion.';
                }

                if (error.code === error.POSITION_UNAVAILABLE) {
                    return 'Ubicacion no disponible. Activa el GPS del telefono y prueba cerca de una ventana o en exterior.';
                }

                if (error.code === error.TIMEOUT) {
                    return 'El GPS tardo demasiado en responder. Intenta otra vez con buena senal.';
                }

                return error.message || 'No se pudo obtener la ubicacion.';
            }

            function updateMap(lat, lng) {
                var latLng = [lat, lng];
                lastKnownLatLng = latLng;
                routePoints.push(latLng);

                if (!currentMarker) {
                    currentMarker = L.marker(latLng, {
                        icon: currentIcon
                    }).addTo(map).bindPopup('Ubicacion actual del transportista');
                } else {
                    currentMarker.setLatLng(latLng);
                }

                if (!routeLine) {
                    routeLine = L.polyline(routePoints, {
                        color: '#007bff',
                        weight: 5,
                        opacity: .85
                    }).addTo(map);
                } else {
                    routeLine.setLatLngs(routePoints);
                }

                drawNavigationRoute(latLng, false);
            }

            function sendPosition(position, force) {
                var now = Date.now();

                if (!force && now - lastSentAt < sendEveryMs) {
                    return;
                }

                lastSentAt = now;
                var coords = position.coords;
                updateMap(coords.latitude, coords.longitude);
                accuracyEl.textContent = coords.accuracy ? Math.round(coords.accuracy) + ' m' : 'No disponible';

                fetch(@json(route('transportista.envios.tracking.store', $solicitud, false)), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': @json(csrf_token())
                    },
                    body: JSON.stringify({
                        latitud: coords.latitude,
                        longitud: coords.longitude,
                        precision_metros: coords.accuracy,
                        velocidad_m_s: coords.speed,
                        rumbo_grados: coords.heading,
                        tipo_recorrido: routeTypeForState(currentTransportState)
                    })
                })
                    .then(function(response) {
                        if (!response.ok) {
                            throw new Error('No se pudo guardar la ubicacion');
                        }

                        return response.json();
                    })
                    .then(function() {
                        lastSendEl.textContent = new Date().toLocaleTimeString();
                        setMessage('success', 'Ubicacion enviada correctamente.');
                    })
                    .catch(function() {
                        setMessage('danger', 'No se pudo enviar la ubicacion. Revisa internet o vuelve a iniciar.');
                    });
            }

            startBtn.addEventListener('click', function() {
                if (!navigator.geolocation) {
                    setMessage('danger', 'Este navegador no soporta GPS.');
                    return;
                }

                statusEl.textContent = 'Compartiendo ubicacion';
                startBtn.disabled = true;
                stopBtn.disabled = false;
                setMessage('info', 'Solicitando permiso de ubicacion...');

                navigator.geolocation.getCurrentPosition(function(position) {
                    sendPosition(position, true);
                }, function(error) {
                    setMessage('danger', geolocationErrorMessage(error));
                }, {
                    enableHighAccuracy: true,
                    timeout: 15000,
                    maximumAge: 0
                });

                watchId = navigator.geolocation.watchPosition(function(position) {
                    sendPosition(position, false);
                }, function(error) {
                    setMessage('warning', geolocationErrorMessage(error));
                }, {
                    enableHighAccuracy: true,
                    timeout: 20000,
                    maximumAge: 5000
                });
            });

            stopBtn.addEventListener('click', function() {
                if (watchId !== null) {
                    navigator.geolocation.clearWatch(watchId);
                    watchId = null;
                }

                statusEl.textContent = 'Detenido';
                startBtn.disabled = false;
                stopBtn.disabled = true;
                setMessage('light border', 'Recorrido detenido.');
            });

            bindTransportStateForm();
        });
    </script>
@endsection
