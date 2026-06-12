<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Envio {{ $detalle->grupo_envio }} | AgroVida</title>
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <style>
        :root { --green: #2f7d24; --ink: #172817; --muted: #667363; --line: #dbe6d7; }
        * { box-sizing: border-box; }
        body { margin: 0; color: var(--ink); background: #edf3ea; font-family: Arial, sans-serif; }
        button, a { font: inherit; }
        .topbar {
            position: sticky;
            top: 0;
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            min-height: 64px;
            padding: 10px max(16px, calc((100vw - 1120px) / 2));
            border-bottom: 1px solid var(--line);
            background: rgba(255,255,255,.96);
        }
        .brand { display: flex; align-items: center; gap: 10px; font-weight: 800; }
        .brand img { width: 38px; height: 38px; object-fit: contain; }
        .exit { border: 0; color: #596658; background: transparent; cursor: pointer; }
        .page { width: min(1120px, 100%); margin: 0 auto; padding: 18px; }
        .status-band {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 18px;
            border-radius: 8px;
            color: #fff;
            background: #234f1e;
        }
        .status-band small { display: block; margin-bottom: 5px; color: #dcebd8; }
        .status-band strong { font-size: 1.35rem; }
        .status-pill { padding: 8px 12px; border-radius: 999px; background: rgba(255,255,255,.16); font-weight: 700; }
        .layout { display: grid; grid-template-columns: minmax(0, .85fr) minmax(0, 1.15fr); gap: 16px; margin-top: 16px; }
        .panel { border: 1px solid var(--line); border-radius: 8px; background: #fff; overflow: hidden; }
        .panel-header { padding: 14px 16px; border-bottom: 1px solid var(--line); font-weight: 800; }
        .panel-body { padding: 16px; }
        .facts { margin: 0; }
        .facts div { display: grid; grid-template-columns: 130px 1fr; gap: 12px; padding: 10px 0; border-bottom: 1px solid #eef2ec; }
        .facts div:last-child { border-bottom: 0; }
        .facts dt { color: var(--muted); font-size: .86rem; font-weight: 700; }
        .facts dd { margin: 0; font-weight: 600; overflow-wrap: anywhere; }
        .map { width: 100%; height: 390px; }
        .map-summary {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 1px;
            border-bottom: 1px solid var(--line);
            background: var(--line);
        }
        .map-summary__item { min-width: 0; padding: 12px 14px; background: #fff; }
        .map-summary__item small { display: block; margin-bottom: 4px; color: var(--muted); font-weight: 700; }
        .map-summary__item strong { display: block; overflow: hidden; font-size: .82rem; text-overflow: ellipsis; white-space: nowrap; }
        .map-pin {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 4px 9px 4px 4px;
            border: 2px solid #fff;
            border-radius: 999px;
            background: rgba(255,255,255,.96);
            box-shadow: 0 4px 14px rgba(0,0,0,.24);
            white-space: nowrap;
        }
        .map-pin__icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            border-radius: 999px;
            color: #fff;
        }
        .map-pin__label { max-width: 130px; overflow: hidden; color: #172817; font-size: .72rem; font-weight: 800; text-overflow: ellipsis; }
        .map-pin--origin .map-pin__icon { background: #d97706; }
        .map-pin--driver .map-pin__icon { background: #0d6efd; }
        .map-pin--target .map-pin__icon { background: #198754; }
        .actions { display: grid; gap: 10px; margin-top: 16px; }
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-height: 48px;
            padding: 0 16px;
            border: 1px solid transparent;
            border-radius: 6px;
            font-weight: 800;
            cursor: pointer;
        }
        .btn-primary { color: #fff; background: var(--green); }
        .btn-outline { color: var(--green); border-color: var(--green); background: #fff; }
        .btn-danger { color: #a52620; border-color: #e7b9b6; background: #fff; }
        .btn:disabled { opacity: .55; cursor: not-allowed; }
        .gps-status { margin-top: 12px; padding: 12px; border: 1px solid var(--line); border-radius: 6px; color: var(--muted); background: #f8faf7; }
        .timeline { display: grid; grid-template-columns: repeat(auto-fit, minmax(112px, 1fr)); gap: 6px; margin-top: 16px; }
        .step { min-height: 62px; padding: 8px; border: 1px solid var(--line); border-radius: 6px; color: #748073; font-size: .72rem; font-weight: 700; text-align: center; }
        .step i { display: block; margin-bottom: 5px; }
        .step.current, .step.done { color: #245c1c; border-color: #82b879; background: #edf7ea; }
        .notice { display: none; margin-top: 12px; padding: 12px; border-radius: 6px; }
        .notice.show { display: block; }
        .notice.success { color: #155724; background: #d4edda; }
        .notice.error { color: #842029; background: #f8d7da; }
        .transport-loading {
            position: relative;
            width: min(280px, 100%);
            height: 82px;
            margin: 8px auto 0;
            overflow: hidden;
        }
        .transport-loading::after {
            content: '';
            position: absolute;
            right: 0;
            bottom: 17px;
            left: 0;
            height: 3px;
            border-radius: 999px;
            background: #dce8d8;
        }
        .transport-loading img {
            position: absolute;
            z-index: 1;
            bottom: 22px;
            left: 0;
            width: 52px;
            height: 52px;
            object-fit: contain;
            animation: agrovida-delivery-run 1.8s ease-in-out infinite;
        }
        @keyframes agrovida-delivery-run {
            0% { left: 0; transform: translateY(0) rotate(-3deg); }
            25% { transform: translateY(-5px) rotate(3deg); }
            50% { left: calc(100% - 52px); transform: translateY(0) rotate(-3deg); }
            75% { transform: translateY(-5px) rotate(3deg); }
            100% { left: 0; transform: translateY(0) rotate(-3deg); }
        }
        @media (prefers-reduced-motion: reduce) {
            .transport-loading img { animation: none; left: calc(50% - 26px); }
        }
        @media (max-width: 760px) {
            .layout { grid-template-columns: 1fr; }
            .map { height: 330px; }
            .timeline { grid-template-columns: repeat(2, 1fr); }
            .status-band { align-items: flex-start; flex-direction: column; }
            .facts div { grid-template-columns: 105px 1fr; }
            .map-summary { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    @php
        $actual = $detalle->estado_transporte_actual === 'asignado' && in_array($detalle->product_type, ['organico', 'ganado'], true)
            ? 'aceptado'
            : $detalle->estado_transporte_actual;
        $actualVisible = $estadoFases[$actual] ?? $actual;
        $actualIndex = array_search($actualVisible, $flujo, true);
        $productoUbicacion = $detalle->origen_direccion_actual
            ?? 'Ubicacion no registrada';
        $tipoEnvio = match ($detalle->product_type) {
            'ganado' => 'ganado',
            'maquinaria' => 'maquinaria',
            default => 'organicos',
        };
        $resumenProductos = $detallesEnvio->pluck('nombre_producto')->join(', ');
    @endphp

    <header class="topbar">
        <div class="brand">
            <img src="{{ asset('img/logo-agrovida.png') }}" alt="AgroVida">
            <span>Transporte AgroVida</span>
        </div>
        <form method="POST" action="{{ route('transporte.salir') }}">
            @csrf
            <button class="exit" type="submit"><i class="fas fa-sign-out-alt"></i> Salir</button>
        </form>
    </header>

    <main class="page">
        <section class="status-band">
            <div>
                <small>Envio de {{ $tipoEnvio }} · {{ $detallesEnvio->count() }} producto(s)</small>
                <strong>{{ $resumenProductos }}</strong>
            </div>
            <span class="status-pill" id="estado-label">{{ $detalle->estado_transporte_label }}</span>
        </section>

        <div class="timeline" id="timeline">
            @foreach ($flujo as $index => $estado)
                @php
                    $label = $faseLabels[$estado] ?? ucfirst(str_replace('_', ' ', $estado));
                    $class = $actualIndex !== false && $index < $actualIndex
                        ? 'done'
                        : ($estado === $actualVisible ? 'current' : '');
                @endphp
                <div class="step {{ $class }}" data-state="{{ $estado }}">
                    <i class="fas {{ $class === 'done' ? 'fa-check' : 'fa-circle' }}"></i>
                    {{ $label }}
                </div>
            @endforeach
        </div>

        <div id="notice" class="notice"></div>

        <div class="layout">
            <section class="panel">
                <div class="panel-header"><i class="fas fa-box-open"></i> Datos de la entrega</div>
                <div class="panel-body">
                    <dl class="facts">
                        <div>
                            <dt>Productos</dt>
                            <dd>
                                @foreach ($detallesEnvio as $itemEnvio)
                                    <div>
                                        <strong>{{ $itemEnvio->nombre_producto }}</strong>
                                        · {{ $itemEnvio->cantidad_tiempo_texto }}
                                        @if ($itemEnvio->notas)
                                            <small>({{ $itemEnvio->notas }})</small>
                                        @endif
                                    </div>
                                @endforeach
                            </dd>
                        </div>
                        <div><dt>Recojo</dt><dd>{{ $productoUbicacion }}</dd></div>
                        <div><dt>Entrega</dt><dd>{{ $detalle->pedido->destino_entrega ?: 'Destino no registrado' }}</dd></div>
                        <div><dt>Comprador</dt><dd>{{ $detalle->pedido->user->name ?? 'No disponible' }}</dd></div>
                        <div>
                            <dt>Contacto</dt>
                            <dd>
                                @if ($detalle->pedido->telefono_contacto)
                                    <a href="tel:{{ $detalle->pedido->telefono_contacto }}">{{ $detalle->pedido->telefono_contacto }}</a>
                                @else
                                    No disponible
                                @endif
                            </dd>
                        </div>
                    </dl>

                    <div class="actions">
                        <button type="button" class="btn btn-primary" id="gps-start"
                            {{ $puedeActivarGps ? '' : 'disabled' }}>
                            <i class="fas fa-location-arrow"></i> Activar GPS
                        </button>
                        <button type="button" class="btn btn-outline" id="gps-stop" disabled>
                            <i class="fas fa-stop"></i> Detener GPS
                        </button>
                        <button type="button" class="btn btn-primary" id="state-next"
                            style="{{ $siguienteEstado ? '' : 'display:none' }}">
                            <i class="fas fa-arrow-right"></i>
                            <span id="state-next-label">
                                @if ($siguienteEstado)
                                    Marcar: {{ $siguienteEstadoLabel }}
                                @endif
                            </span>
                        </button>
                        <button type="button" class="btn btn-danger" id="state-cancel"
                            style="{{ in_array($actual, ['preparando', 'en_camino_entrega', 'asignado', 'en_camino_recogida'], true) ? '' : 'display:none' }}">
                            <i class="fas fa-times"></i> Cancelar envio
                        </button>
                    </div>

                    <div class="gps-status" id="gps-status">
                        {{ $puedeActivarGps ? 'GPS detenido.' : 'Esperando que el transporte sea habilitado.' }}
                    </div>
                </div>
            </section>

            <section class="panel">
                <div class="panel-header"><i class="fas fa-map-marked-alt"></i> Ruta de entrega</div>
                <div class="map-summary">
                    <div class="map-summary__item">
                        <small><i class="fas fa-route mr-1"></i>Ruta actual</small>
                        <strong id="map-route-target">Esperando GPS</strong>
                    </div>
                    <div class="map-summary__item">
                        <small><i class="fas fa-truck mr-1"></i>Transportista</small>
                        <strong id="map-driver-status">Esperando GPS</strong>
                    </div>
                    <div class="map-summary__item">
                        <small><i class="fas fa-home mr-1"></i>Comprador</small>
                        <strong>{{ $detalle->pedido->user->name ?? 'No disponible' }}</strong>
                    </div>
                </div>
                <div id="transport-map" class="map"></div>
            </section>
        </div>
    </main>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const csrf = document.querySelector('meta[name="csrf-token"]').content;
            const originLat = @json($detalle->product_latitud ? (float) $detalle->product_latitud : null);
            const originLng = @json($detalle->product_longitud ? (float) $detalle->product_longitud : null);
            const targetLat = @json($detalle->pedido->destino_latitud ? (float) $detalle->pedido->destino_latitud : null);
            const targetLng = @json($detalle->pedido->destino_longitud ? (float) $detalle->pedido->destino_longitud : null);
            const locationUrl = @json(route('transporte.ubicacion'));
            const stateUrl = @json(route('transporte.estado'));
            const updateUrl = @json(route('transporte.actualizacion'));
            const gpsInterval = {{ config('transporte.gps_intervalo_segundos', 10) * 1000 }};
            const gpsStart = document.getElementById('gps-start');
            const gpsStop = document.getElementById('gps-stop');
            const gpsStatus = document.getElementById('gps-status');
            const notice = document.getElementById('notice');
            let watchId = null;
            let lastSentAt = 0;
            let liveMarker = null;
            let originMarker = null;
            let targetMarker = null;
            let routeLine = null;
            let lastRouteKey = null;
            let currentState = @json($actual);
            let currentNextState = @json($siguienteEstado);
            let updateBusy = false;
            let updateTimer = null;
            const stateLabels = @json($estadoLabels);
            const statePhases = @json($estadoFases);
            const flow = @json($flujo);

            const center = targetLat && targetLng
                ? [targetLat, targetLng]
                : (originLat && originLng ? [originLat, originLng] : [-17.7833, -63.1821]);
            const map = L.map('transport-map').setView(center, targetLat ? 13 : 6);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap'
            }).addTo(map);

            const bounds = [];
            function mapIcon(type, icon, label) {
                return L.divIcon({
                    className: '',
                    html: '<div class="map-pin map-pin--' + type + '">' +
                        '<span class="map-pin__icon"><i class="fas ' + icon + '"></i></span>' +
                        '<span class="map-pin__label">' + label + '</span>' +
                    '</div>',
                    iconSize: [175, 38],
                    iconAnchor: [18, 19],
                    popupAnchor: [0, -20]
                });
            }
            const originIcon = mapIcon('origin', 'fa-box', @json($detallesEnvio->count() . ' producto(s)'));
            const targetIcon = mapIcon('target', 'fa-home', @json($detalle->pedido->user->name ?? 'Comprador'));
            const driverIcon = mapIcon('driver', 'fa-truck', 'Mi ubicacion');

            if (originLat && originLng) {
                originMarker = L.marker([originLat, originLng], { icon: originIcon })
                    .addTo(map)
                    .bindPopup('<strong>Punto de recojo</strong><br>' + @json($resumenProductos) +
                        '<br>' + @json($productoUbicacion));
                bounds.push([originLat, originLng]);
            }
            if (targetLat && targetLng) {
                targetMarker = L.marker([targetLat, targetLng], { icon: targetIcon })
                    .addTo(map)
                    .bindPopup('<strong>Destino del comprador</strong><br>' +
                        @json($detalle->pedido->user->name ?? 'Comprador') + '<br>' +
                        @json($detalle->pedido->destino_entrega ?: 'Destino registrado'));
                bounds.push([targetLat, targetLng]);
            }

            function routeTargetForState(state) {
                const pickupTarget = originLat && originLng ? {
                    lat: originLat,
                    lng: originLng,
                    label: 'Hacia la maquinaria',
                    popup: 'Ruta al punto de recojo',
                    marker: originMarker
                } : null;
                const deliveryTarget = targetLat && targetLng ? {
                    lat: targetLat,
                    lng: targetLng,
                    label: 'Hacia el comprador',
                    popup: 'Ruta al destino del comprador',
                    marker: targetMarker
                } : null;

                if (['asignado', 'en_camino_recogida', 'llego_recogida'].includes(state)) {
                    return pickupTarget;
                }

                if (['producto_recogido', 'en_camino_entrega', 'llego_destino', 'esperando_confirmacion'].includes(state)) {
                    return deliveryTarget;
                }

                if ([
                    'entregado',
                    'devolucion_solicitada',
                    'en_camino_recoger_devolucion',
                    'llego_recoger_devolucion',
                    'maquinaria_recogida_retorno',
                    'en_camino_retorno',
                    'llego_retorno'
                ].includes(state)) {
                    return pickupTarget ? {
                        ...pickupTarget,
                        label: 'Retorno al punto de recojo',
                        popup: 'Ruta de devolucion'
                    } : null;
                }

                return deliveryTarget || pickupTarget;
            }

            function updateRouteSummary() {
                const target = routeTargetForState(currentState);
                document.getElementById('map-route-target').textContent = target ? target.label : 'Ruta no disponible';
            }

            function setActiveTargetMarker() {
                const target = routeTargetForState(currentState);
                if (originMarker) originMarker.setOpacity(!target || target.marker === originMarker ? 1 : .38);
                if (targetMarker) targetMarker.setOpacity(!target || target.marker === targetMarker ? 1 : .38);
            }

            function clearRoute() {
                if (routeLine) {
                    map.removeLayer(routeLine);
                    routeLine = null;
                }
            }

            function drawFallbackRoute(points) {
                clearRoute();
                routeLine = L.polyline(points, {
                    color: '#2f7d24',
                    weight: 4,
                    opacity: .85,
                    dashArray: '8,8'
                }).addTo(map);
                map.fitBounds(routeLine.getBounds(), { padding: [35, 35], maxZoom: 14 });
            }

            function drawRoadRoute(fromLat, fromLng, toLat, toLng, label) {
                const points = [[fromLat, fromLng], [toLat, toLng]];
                const routeKey = [
                    currentState,
                    Number(fromLat).toFixed(5),
                    Number(fromLng).toFixed(5),
                    Number(toLat).toFixed(5),
                    Number(toLng).toFixed(5)
                ].join('|');
                if (routeKey === lastRouteKey) return;
                lastRouteKey = routeKey;

                const osrmUrl = 'https://router.project-osrm.org/route/v1/driving/' +
                    fromLng + ',' + fromLat + ';' + toLng + ',' + toLat +
                    '?overview=full&geometries=geojson';

                fetch(osrmUrl)
                    .then(function (response) {
                        if (!response.ok) throw new Error('Ruta no disponible');
                        return response.json();
                    })
                    .then(function (data) {
                        const route = data.routes && data.routes[0];
                        if (!route || !route.geometry) {
                            drawFallbackRoute(points);
                            return;
                        }
                        const coordinates = route.geometry.coordinates.map(function (coordinate) {
                            return [coordinate[1], coordinate[0]];
                        });
                        clearRoute();
                        routeLine = L.polyline(coordinates, {
                            color: '#2f7d24',
                            weight: 5,
                            opacity: .9
                        }).addTo(map);
                        const distance = (route.distance / 1000).toFixed(1);
                        const minutes = Math.round(route.duration / 60);
                        routeLine.bindPopup('<strong>' + label + '</strong><br>' + distance +
                            ' km · ' + minutes + ' min aprox.');
                        map.fitBounds(routeLine.getBounds(), { padding: [35, 35], maxZoom: 14 });
                    })
                    .catch(function () { drawFallbackRoute(points); });
            }

            function updateRouteFromDriver(lat, lng) {
                const target = routeTargetForState(currentState);
                updateRouteSummary();
                setActiveTargetMarker();

                if (!target) {
                    clearRoute();
                    return;
                }

                drawRoadRoute(lat, lng, target.lat, target.lng, target.popup);
            }

            updateRouteSummary();
            setActiveTargetMarker();

            if (!liveMarker && bounds.length > 1) {
                const initialTarget = routeTargetForState(currentState);
                if (initialTarget && originLat && originLng && initialTarget.lat !== originLat && initialTarget.lng !== originLng) {
                    drawRoadRoute(originLat, originLng, initialTarget.lat, initialTarget.lng, initialTarget.popup);
                } else {
                    map.fitBounds(bounds, { padding: [35, 35], maxZoom: 14 });
                }
            }

            function showNotice(type, message) {
                notice.className = 'notice show ' + type;
                notice.textContent = message;
            }

            function gpsError(error) {
                if (!window.isSecureContext) {
                    return 'El GPS requiere HTTPS. Abre la URL segura de ngrok.';
                }
                return error && error.message ? error.message : 'No se pudo obtener la ubicacion.';
            }

            function sendPosition(position, force) {
                const now = Date.now();
                if (!force && now - lastSentAt < gpsInterval) return;
                lastSentAt = now;

                const coords = position.coords;
                gpsStatus.textContent = 'GPS activo. Precision: ' + Math.round(coords.accuracy || 0) + ' m.';
                document.getElementById('map-driver-status').textContent =
                    'GPS activo · ' + Math.round(coords.accuracy || 0) + ' m';

                const latLng = [coords.latitude, coords.longitude];
                if (!liveMarker) {
                    liveMarker = L.marker(latLng, { icon: driverIcon }).addTo(map).bindPopup('Tu ubicacion actual');
                } else {
                    liveMarker.setLatLng(latLng);
                }
                updateRouteFromDriver(coords.latitude, coords.longitude);

                fetch(locationUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrf
                    },
                    body: JSON.stringify({
                        latitud: coords.latitude,
                        longitud: coords.longitude,
                        precision_metros: coords.accuracy,
                        velocidad_m_s: coords.speed,
                        rumbo_grados: coords.heading
                    })
                }).catch(function () {
                    showNotice('error', 'No se pudo enviar la ubicacion. Revisa tu conexion.');
                });
            }

            function renderState(data) {
                currentState = data.estado;
                currentNextState = data.siguiente_estado;
                document.getElementById('estado-label').textContent = data.estado_label;
                const currentVisibleState = statePhases[currentState] || currentState;
                const currentIndex = flow.indexOf(currentVisibleState);
                updateRouteSummary();
                setActiveTargetMarker();

                if (liveMarker) {
                    const latLng = liveMarker.getLatLng();
                    updateRouteFromDriver(latLng.lat, latLng.lng);
                }

                document.querySelectorAll('#timeline .step').forEach(function (step) {
                    const index = flow.indexOf(step.dataset.state);
                    const done = currentIndex >= 0 && index < currentIndex;
                    step.classList.toggle('done', done);
                    step.classList.toggle('current', step.dataset.state === currentVisibleState);
                    step.querySelector('i').className = 'fas ' + (done ? 'fa-check' : 'fa-circle');
                });

                const nextButton = document.getElementById('state-next');
                if (data.siguiente_estado) {
                    document.getElementById('state-next-label').textContent =
                        'Marcar: ' + (data.siguiente_estado_label || stateLabels[data.siguiente_estado]);
                    nextButton.style.display = '';
                } else {
                    nextButton.style.display = 'none';
                }

                document.getElementById('state-cancel').style.display =
                    ['preparando', 'en_camino_entrega', 'asignado', 'en_camino_recogida'].includes(currentState) ? '' : 'none';

                if (watchId === null) {
                    gpsStart.disabled = !data.puede_activar_gps;
                    if (!data.puede_activar_gps && ['aceptado', 'esperando_confirmacion'].includes(currentState)) {
                        gpsStatus.textContent = 'Esperando que el transporte sea habilitado para el siguiente paso.';
                    } else if (data.puede_activar_gps && gpsStatus.textContent.includes('Esperando')) {
                        gpsStatus.textContent = 'Ya puedes activar el GPS y continuar el recorrido.';
                    }
                }

                if (data.ubicacion) {
                    const latLng = [data.ubicacion.latitud, data.ubicacion.longitud];
                    if (!liveMarker) {
                        liveMarker = L.marker(latLng, { icon: driverIcon }).addTo(map).bindPopup('Ubicacion del transporte');
                    } else {
                        liveMarker.setLatLng(latLng);
                    }
                    updateRouteFromDriver(data.ubicacion.latitud, data.ubicacion.longitud);
                    document.getElementById('map-driver-status').textContent =
                        data.ubicacion.fecha_humana ? 'Actualizado ' + data.ubicacion.fecha_humana : 'GPS activo';
                }
            }

            function refreshState() {
                if (updateBusy || document.hidden) {
                    scheduleStateRefresh();
                    return;
                }

                updateBusy = true;
                fetch(updateUrl, { headers: { 'Accept': 'application/json' } })
                    .then(function (response) {
                        if (!response.ok) throw new Error('No se pudo actualizar');
                        return response.json();
                    })
                    .then(renderState)
                    .catch(function () {})
                    .finally(function () {
                        updateBusy = false;
                        scheduleStateRefresh();
                    });
            }

            function scheduleStateRefresh() {
                clearTimeout(updateTimer);
                updateTimer = setTimeout(refreshState, 10000);
            }

            gpsStart.addEventListener('click', function () {
                if (!navigator.geolocation) {
                    showNotice('error', 'Este navegador no permite obtener GPS.');
                    return;
                }

                gpsStatus.textContent = 'Solicitando permiso de ubicacion...';
                navigator.geolocation.getCurrentPosition(
                    function (position) { sendPosition(position, true); },
                    function (error) { showNotice('error', gpsError(error)); },
                    { enableHighAccuracy: true, timeout: 15000, maximumAge: 0 }
                );

                watchId = navigator.geolocation.watchPosition(
                    function (position) { sendPosition(position, false); },
                    function (error) { showNotice('error', gpsError(error)); },
                    { enableHighAccuracy: true, timeout: 20000, maximumAge: 3000 }
                );
                gpsStart.disabled = true;
                gpsStop.disabled = false;
            });

            gpsStop.addEventListener('click', function () {
                if (watchId !== null) navigator.geolocation.clearWatch(watchId);
                watchId = null;
                gpsStart.disabled = false;
                gpsStop.disabled = true;
                gpsStatus.textContent = 'GPS detenido.';
            });

            function updateState(action, cancellationReason) {
                fetch(stateUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrf
                    },
                    body: JSON.stringify({
                        accion: action,
                        motivo_cancelacion: cancellationReason || null
                    })
                })
                    .then(async function (response) {
                        const data = await response.json();
                        if (!response.ok) throw new Error(data.message || Object.values(data.errors || {})[0]?.[0]);
                        Swal.close();
                        renderState(data);
                        showNotice('success', data.message || 'Estado actualizado correctamente.');

                        if (watchId !== null && data.puede_activar_gps && navigator.geolocation) {
                            navigator.geolocation.getCurrentPosition(
                                function (position) { sendPosition(position, true); },
                                function () {},
                                { enableHighAccuracy: true, timeout: 12000, maximumAge: 0 }
                            );
                        }
                    })
                    .catch(function (error) {
                        Swal.close();
                        showNotice('error', error.message || 'No se pudo actualizar el estado.');
                    });
            }

            function confirmStateAdvance() {
                if (!currentNextState) return;

                const nextLabel = stateLabels[currentNextState] || currentNextState;
                const isWaitingBuyer = currentNextState === 'esperando_confirmacion';
                const title = isWaitingBuyer
                    ? '¿Confirmar llegada al comprador?'
                    : '¿Cambiar estado a ' + nextLabel + '?';
                const text = isWaitingBuyer
                    ? 'Despues de esto el comprador debe confirmar que recibio el producto.'
                    : 'El estado se actualizara y las otras pantallas podran ver el avance.';
                const confirmText = isWaitingBuyer
                    ? 'Si, llegue al destino'
                    : 'Si, continuar';

                Swal.fire({
                    title: title,
                    text: text,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: confirmText,
                    cancelButtonText: 'Todavía no',
                    confirmButtonColor: '#2f7d24',
                    cancelButtonColor: '#6c757d',
                    reverseButtons: true,
                    focusCancel: true,
                    allowOutsideClick: false
                }).then(function (result) {
                    if (!result.isConfirmed) return;

                    Swal.fire({
                        title: 'Actualizando el recorrido...',
                        html: '<div class="transport-loading">' +
                            '<img src="{{ asset('img/logo-agrovida.png') }}" alt="AgroVida">' +
                            '</div>',
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        allowEscapeKey: false
                    });

                    updateState('avanzar');
                });
            }

            document.getElementById('state-next')?.addEventListener('click', function () {
                confirmStateAdvance();
            });
            refreshState();
            document.addEventListener('visibilitychange', function () {
                if (!document.hidden) {
                    clearTimeout(updateTimer);
                    refreshState();
                }
            });

            document.getElementById('state-cancel')?.addEventListener('click', function () {
                Swal.fire({
                    title: '¿Cancelar este envío?',
                    text: 'Indica con claridad por qué no se pudo completar la entrega.',
                    icon: 'question',
                    input: 'textarea',
                    inputLabel: 'Motivo de la cancelación',
                    inputPlaceholder: 'Ejemplo: el producto presentó daños durante el traslado...',
                    inputAttributes: {
                        maxlength: 1000,
                        minlength: 10,
                        autocapitalize: 'sentences'
                    },
                    showCancelButton: true,
                    confirmButtonText: 'Confirmar cancelación',
                    cancelButtonText: 'Volver',
                    confirmButtonColor: '#b7791f',
                    cancelButtonColor: '#6c757d',
                    reverseButtons: true,
                    focusCancel: true,
                    allowOutsideClick: false,
                    inputValidator: function(value) {
                        if (!value || value.trim().length < 10) {
                            return 'Escribe una explicación de al menos 10 caracteres.';
                        }
                    }
                }).then(function(result) {
                    if (!result.isConfirmed) return;

                    Swal.fire({
                        title: 'Registrando la cancelación...',
                        html: '<div class="transport-loading">' +
                            '<img src="{{ asset('img/logo-agrovida.png') }}" alt="AgroVida">' +
                            '</div>',
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        allowEscapeKey: false
                    });

                    updateState('cancelar', result.value.trim());
                });
            });
        });
    </script>
</body>
</html>
