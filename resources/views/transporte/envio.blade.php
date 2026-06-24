<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
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
        .brand img { width: 38px; height: 38px; border-radius: 50%; object-fit: cover; }
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
        .map-panel-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }
        .map-panel-header__title { font-weight: 800; }
        .map-expand-btn {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            min-height: 34px;
            padding: 0 11px;
            border: 1px solid #2f7d24;
            border-radius: 6px;
            color: #2f7d24;
            background: #fff;
            font-size: .82rem;
            font-weight: 800;
            cursor: pointer;
        }
        .map-expand-btn:hover { background: #edf7ea; }
        .map-fullscreen {
            position: fixed;
            inset: 0;
            z-index: 3000;
            display: none;
            grid-template-rows: auto 1fr auto;
            background: #101810;
        }
        body.map-fullscreen-open { overflow: hidden; }
        body.map-fullscreen-open .map-fullscreen { display: grid; }
        .map-fullscreen__header,
        .map-fullscreen__footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 12px 16px;
            color: #fff;
            background: rgba(16, 24, 16, .96);
        }
        .map-fullscreen__header strong { display: block; font-size: 1rem; }
        .map-fullscreen__header small { display: block; color: #d9e8d5; }
        .map-fullscreen__close {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 42px;
            height: 42px;
            border: 1px solid rgba(255,255,255,.28);
            border-radius: 999px;
            color: #fff;
            background: transparent;
            cursor: pointer;
        }
        .map-fullscreen__map {
            min-height: 0;
        }
        .map-fullscreen__map .map {
            height: 100%;
            min-height: 100%;
            border-radius: 0;
        }
        .map-fullscreen__footer {
            align-items: flex-start;
            flex-direction: column;
            font-size: .86rem;
        }
        .map-fullscreen__footer span {
            color: #d9e8d5;
        }
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
        .gps-demo {
            display: grid;
            gap: 8px;
            margin-top: 10px;
            padding: 12px;
            border: 1px solid #cfe0ca;
            border-radius: 6px;
            background: #fbfdf9;
        }
        .gps-demo__title { margin: 0; color: var(--ink); font-size: .92rem; font-weight: 800; }
        .gps-demo__text { margin: 0; color: var(--muted); font-size: .84rem; }
        .gps-demo__bar { height: 7px; overflow: hidden; border-radius: 999px; background: #e7f0e3; }
        .gps-demo__bar span { display: block; width: 0; height: 100%; background: #0d6efd; transition: width .25s ease; }
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
            border-radius: 50%;
            object-fit: cover;
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
            .map-panel-header { align-items: flex-start; flex-direction: column; }
            .map-expand-btn { width: 100%; justify-content: center; }
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
            <img src="{{ asset('img/brand/logo-agrovida.jpeg') }}" alt="AgroVida">
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
                        <button type="button" class="btn btn-outline" id="gps-demo-start"
                            {{ $puedeActivarGps ? '' : 'disabled' }}>
                            <i class="fas fa-route"></i> Simular recorrido
                        </button>
                        <button type="button" class="btn btn-outline" id="gps-demo-stop" disabled>
                            <i class="fas fa-pause"></i> Pausar simulacion
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
                    <div class="gps-demo">
                        <p class="gps-demo__title"><i class="fas fa-vial"></i> Simulacion de recorrido</p>
                        <p class="gps-demo__text" id="gps-demo-text">
                            Recorrido listo para enviar ubicaciones durante el trayecto.
                        </p>
                        <div class="gps-demo__bar" aria-hidden="true"><span id="gps-demo-progress"></span></div>
                    </div>
                </div>
            </section>

            <section class="panel">
                <div class="panel-header map-panel-header">
                    <span class="map-panel-header__title"><i class="fas fa-map-marked-alt"></i> Ruta de entrega</span>
                    <button type="button" class="map-expand-btn" id="map-expand">
                        <i class="fas fa-expand"></i> Ampliar mapa
                    </button>
                </div>
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
                <div id="transport-map-shell">
                    <div id="transport-map" class="map"></div>
                </div>
            </section>
        </div>
    </main>

    <div class="map-fullscreen" id="map-fullscreen" aria-hidden="true">
        <div class="map-fullscreen__header">
            <div>
                <strong><i class="fas fa-map-marked-alt"></i> Ruta de entrega</strong>
                <small id="map-fullscreen-route">Esperando GPS</small>
            </div>
            <button type="button" class="map-fullscreen__close" id="map-close" aria-label="Cerrar mapa ampliado">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="map-fullscreen__map" id="map-fullscreen-map"></div>
        <div class="map-fullscreen__footer">
            <strong id="map-fullscreen-driver">Transportista: esperando GPS</strong>
            <span>{{ $detalle->pedido->destino_entrega ?: 'Destino no registrado' }}</span>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        if (window.history && window.history.replaceState) {
            window.history.replaceState({ transporteEnvio: true }, document.title, @json(route('transporte.envio')));
        }

        window.addEventListener('pageshow', function(event) {
            if (event.persisted) {
                window.location.reload();
            }
        });

        document.addEventListener('DOMContentLoaded', function () {
            const csrf = document.querySelector('meta[name="csrf-token"]').content;
            const originLat = @json($detalle->product_latitud ? (float) $detalle->product_latitud : null);
            const originLng = @json($detalle->product_longitud ? (float) $detalle->product_longitud : null);
            const targetLat = @json($detalle->pedido->destino_latitud ? (float) $detalle->pedido->destino_latitud : null);
            const targetLng = @json($detalle->pedido->destino_longitud ? (float) $detalle->pedido->destino_longitud : null);
            const locationUrl = @json(route('transporte.ubicacion'));
            const stateUrl = @json(route('transporte.estado'));
            const updateUrl = @json(route('transporte.actualizacion'));
            const landingUrl = @json(route('landing'));
            const gpsInterval = {{ config('transporte.gps_intervalo_segundos', 10) * 1000 }};
            const gpsStart = document.getElementById('gps-start');
            const gpsStop = document.getElementById('gps-stop');
            const gpsDemoStart = document.getElementById('gps-demo-start');
            const gpsDemoStop = document.getElementById('gps-demo-stop');
            const gpsDemoText = document.getElementById('gps-demo-text');
            const gpsDemoProgress = document.getElementById('gps-demo-progress');
            const gpsStatus = document.getElementById('gps-status');
            const notice = document.getElementById('notice');
            const mapShell = document.getElementById('transport-map-shell');
            const mapFullscreen = document.getElementById('map-fullscreen');
            const mapFullscreenTarget = document.getElementById('map-fullscreen-map');
            const mapFullscreenRoute = document.getElementById('map-fullscreen-route');
            const mapFullscreenDriver = document.getElementById('map-fullscreen-driver');
            let watchId = null;
            let demoTimer = null;
            let demoStep = 0;
            const demoTotalSteps = 18;
            let lastSentAt = 0;
            let liveMarker = null;
            let originMarker = null;
            let targetMarker = null;
            let routeLine = null;
            let lastRouteKey = null;
            let activeRouteCoordinates = [];
            let currentState = @json($actual);
            let currentNextState = @json($siguienteEstado);
            let canActivateGps = @json($puedeActivarGps);
            let terminalRedirectShown = false;
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
                const label = target ? target.label : 'Ruta no disponible';
                document.getElementById('map-route-target').textContent = label;
                if (mapFullscreenRoute) mapFullscreenRoute.textContent = label;
            }

            function setDriverStatus(text) {
                document.getElementById('map-driver-status').textContent = text;
                if (mapFullscreenDriver) mapFullscreenDriver.textContent = 'Transportista: ' + text;
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

            function setVisibleRoute(coordinates, options) {
                clearRoute();
                activeRouteCoordinates = coordinates;
                routeLine = L.polyline(coordinates, {
                    color: '#2f7d24',
                    weight: options.weight,
                    opacity: options.opacity,
                    dashArray: options.dashArray || null
                }).addTo(map);
                map.fitBounds(routeLine.getBounds(), { padding: [35, 35], maxZoom: 14 });
                return routeLine;
            }

            function drawFallbackRoute(points) {
                setVisibleRoute(points, {
                    weight: 4,
                    opacity: .85,
                    dashArray: '8,8'
                });
            }

            function fetchRoadRoute(fromLat, fromLng, toLat, toLng) {
                const osrmUrl = 'https://router.project-osrm.org/route/v1/driving/' +
                    fromLng + ',' + fromLat + ';' + toLng + ',' + toLat +
                    '?overview=full&geometries=geojson';

                return fetch(osrmUrl)
                    .then(function (response) {
                        if (!response.ok) throw new Error('Ruta no disponible');
                        return response.json();
                    })
                    .then(function (data) {
                        const route = data.routes && data.routes[0];
                        if (!route || !route.geometry) {
                            throw new Error('Ruta no disponible');
                        }

                        return {
                            route: route,
                            coordinates: route.geometry.coordinates.map(function (coordinate) {
                                return [coordinate[1], coordinate[0]];
                            })
                        };
                    });
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

                fetchRoadRoute(fromLat, fromLng, toLat, toLng)
                    .then(function (result) {
                        setVisibleRoute(result.coordinates, {
                            weight: 5,
                            opacity: .9
                        });
                        const route = result.route;
                        const distance = (route.distance / 1000).toFixed(1);
                        const minutes = Math.round(route.duration / 60);
                        routeLine.bindPopup('<strong>' + label + '</strong><br>' + distance +
                            ' km · ' + minutes + ' min aprox.');
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

            function stopRealGps() {
                if (watchId !== null) {
                    navigator.geolocation.clearWatch(watchId);
                }
                watchId = null;
            }

            function setDemoProgress(percent) {
                if (gpsDemoProgress) {
                    gpsDemoProgress.style.width = Math.max(0, Math.min(100, percent)) + '%';
                }
            }

            function setDemoRunning(running) {
                if (gpsDemoStart) gpsDemoStart.disabled = running || !canActivateGps || !routeTargetForState(currentState);
                if (gpsDemoStop) gpsDemoStop.disabled = !running;
            }

            function stopDemo(message) {
                if (demoTimer !== null) {
                    clearInterval(demoTimer);
                    demoTimer = null;
                }

                setDemoRunning(false);

                if (gpsDemoText && message) {
                    gpsDemoText.textContent = message;
                }

                if (watchId === null) {
                    gpsStart.disabled = !canActivateGps;
                }
            }

            function demoStartPoint(target) {
                if (liveMarker) {
                    const latLng = liveMarker.getLatLng();
                    return [latLng.lat, latLng.lng];
                }

                if (target.lat === targetLat && target.lng === targetLng && originLat && originLng) {
                    return [originLat, originLng];
                }

                if (target.lat === originLat && target.lng === originLng && targetLat && targetLng) {
                    return [targetLat, targetLng];
                }

                return [target.lat - 0.008, target.lng - 0.008];
            }

            function distanceMeters(from, to) {
                const earthRadius = 6371000;
                const latDelta = (to[0] - from[0]) * Math.PI / 180;
                const lngDelta = (to[1] - from[1]) * Math.PI / 180;
                const fromLat = from[0] * Math.PI / 180;
                const toLat = to[0] * Math.PI / 180;
                const a = Math.sin(latDelta / 2) ** 2 +
                    Math.cos(fromLat) * Math.cos(toLat) * Math.sin(lngDelta / 2) ** 2;

                return earthRadius * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
            }

            function pointAtRouteProgress(routeCoordinates, progress) {
                if (!routeCoordinates.length) {
                    return null;
                }

                if (routeCoordinates.length === 1 || progress <= 0) {
                    return routeCoordinates[0];
                }

                if (progress >= 1) {
                    return routeCoordinates[routeCoordinates.length - 1];
                }

                const segmentDistances = [];
                let totalDistance = 0;

                for (let index = 1; index < routeCoordinates.length; index += 1) {
                    const segmentDistance = distanceMeters(routeCoordinates[index - 1], routeCoordinates[index]);
                    segmentDistances.push(segmentDistance);
                    totalDistance += segmentDistance;
                }

                let targetDistance = totalDistance * progress;

                for (let index = 1; index < routeCoordinates.length; index += 1) {
                    const segmentDistance = segmentDistances[index - 1];

                    if (targetDistance <= segmentDistance || index === routeCoordinates.length - 1) {
                        const start = routeCoordinates[index - 1];
                        const end = routeCoordinates[index];
                        const segmentProgress = segmentDistance > 0 ? targetDistance / segmentDistance : 0;

                        return [
                            start[0] + ((end[0] - start[0]) * segmentProgress),
                            start[1] + ((end[1] - start[1]) * segmentProgress)
                        ];
                    }

                    targetDistance -= segmentDistance;
                }

                return routeCoordinates[routeCoordinates.length - 1];
            }

            function sendPosition(position, force) {
                const now = Date.now();
                if (!force && now - lastSentAt < gpsInterval) return;
                lastSentAt = now;

                const coords = position.coords;
                gpsStatus.textContent = 'GPS activo. Precision: ' + Math.round(coords.accuracy || 0) + ' m.';
                setDriverStatus('GPS activo · ' + Math.round(coords.accuracy || 0) + ' m');

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

            function sendDemoPoint(latLng) {
                sendPosition({
                    coords: {
                        latitude: latLng[0],
                        longitude: latLng[1],
                        accuracy: 8,
                        speed: 9,
                        heading: null
                    }
                }, true);
            }

            function isTerminalState(state, nextState) {
                return !nextState && [
                    'entregado',
                    'devuelto_vendedor',
                    'cancelado',
                    'finalizado',
                    'devuelto'
                ].includes(state);
            }

            function showTerminalRedirect(stateLabel) {
                if (terminalRedirectShown) {
                    return;
                }

                terminalRedirectShown = true;
                stopRealGps();
                stopDemo();
                gpsStart.disabled = true;
                gpsStop.disabled = true;
                if (gpsDemoStart) gpsDemoStart.disabled = true;
                if (gpsDemoStop) gpsDemoStop.disabled = true;

                Swal.fire({
                    title: 'Envio finalizado',
                    text: 'El recorrido quedo en estado: ' + (stateLabel || 'finalizado') + '. Seras redirigido al inicio.',
                    icon: 'success',
                    confirmButtonText: 'Ir al inicio',
                    confirmButtonColor: '#2f7d24',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    timer: 4500,
                    timerProgressBar: true
                }).then(function () {
                    window.location.href = landingUrl;
                });
            }

            function startDemo() {
                const target = routeTargetForState(currentState);

                if (!canActivateGps || !target) {
                    showNotice('error', 'No hay coordenadas suficientes para simular este tramo.');
                    return;
                }

                stopRealGps();
                stopDemo();
                demoStep = 0;
                lastSentAt = 0;
                const origin = demoStartPoint(target);

                gpsStart.disabled = true;
                gpsStop.disabled = true;
                setDemoRunning(true);
                setDemoProgress(0);
                gpsStatus.textContent = 'Calculando ruta por calles hacia: ' + target.label + '.';
                if (gpsDemoText) {
                    gpsDemoText.textContent = 'Buscando la ruta del mapa para mover el transportista por las calles.';
                }

                fetchRoadRoute(origin[0], origin[1], target.lat, target.lng)
                    .catch(function () {
                        return {
                            route: null,
                            coordinates: [origin, [target.lat, target.lng]]
                        };
                    })
                    .then(function (result) {
                        const demoRoute = result.coordinates && result.coordinates.length
                            ? result.coordinates
                            : [origin, [target.lat, target.lng]];

                        setVisibleRoute(demoRoute, {
                            weight: result.route ? 5 : 4,
                            opacity: .9,
                            dashArray: result.route ? null : '8,8'
                        });

                        gpsStatus.textContent = 'Simulando recorrido por la ruta marcada.';
                        if (gpsDemoText) {
                            gpsDemoText.textContent = result.route
                                ? 'El transportista sigue la ruta calculada por calles.'
                                : 'No se pudo calcular ruta por calles; usando linea de respaldo.';
                        }

                        sendDemoPoint(demoRoute[0]);

                        demoTimer = setInterval(function () {
                            demoStep += 1;
                            const progress = demoStep / demoTotalSteps;
                            const latLng = pointAtRouteProgress(demoRoute, progress);

                            if (!latLng) {
                                stopDemo('No se pudo continuar la simulacion.');
                                showNotice('error', 'No se pudo continuar la simulacion.');
                                return;
                            }

                            sendDemoPoint(latLng);
                            setDemoProgress(progress * 100);

                            if (demoStep >= demoTotalSteps) {
                                stopDemo('Llegaste al objetivo. Ahora presiona el boton Marcar para cambiar el estado.');
                                setDemoProgress(100);
                                gpsStatus.textContent = 'Simulacion completada. Ultima ubicacion enviada cerca del objetivo.';
                                showNotice('success', 'Simulacion completada. Ya puedes avanzar el estado del envio.');
                            }
                        }, 1200);
                    });
            }

            function renderState(data) {
                const previousState = currentState;
                currentState = data.estado;
                currentNextState = data.siguiente_estado;
                canActivateGps = !!data.puede_activar_gps;
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

                if (isTerminalState(currentState, currentNextState)) {
                    showTerminalRedirect(data.estado_label);
                    return;
                }

                if (watchId === null) {
                    gpsStart.disabled = !canActivateGps;
                    if (gpsDemoStart && demoTimer === null) {
                        gpsDemoStart.disabled = !canActivateGps;
                    }
                    if (!canActivateGps && ['aceptado', 'esperando_confirmacion'].includes(currentState)) {
                        gpsStatus.textContent = 'Esperando que el transporte sea habilitado para el siguiente paso.';
                    } else if (canActivateGps && gpsStatus.textContent.includes('Esperando')) {
                        gpsStatus.textContent = 'Ya puedes activar el GPS y continuar el recorrido.';
                    }
                }

                if (previousState !== currentState) {
                    stopDemo('Estado actualizado. Puedes simular el siguiente tramo cuando corresponda.');
                    setDemoProgress(0);
                }

                if (data.ubicacion) {
                    const latLng = [data.ubicacion.latitud, data.ubicacion.longitud];
                    if (!liveMarker) {
                        liveMarker = L.marker(latLng, { icon: driverIcon }).addTo(map).bindPopup('Ubicacion del transporte');
                    } else {
                        liveMarker.setLatLng(latLng);
                    }
                    updateRouteFromDriver(data.ubicacion.latitud, data.ubicacion.longitud);
                    setDriverStatus(data.ubicacion.fecha_humana ? 'Actualizado ' + data.ubicacion.fecha_humana : 'GPS activo');
                }
            }

            function resizeMapSoon() {
                setTimeout(function () {
                    map.invalidateSize();
                    if (routeLine) {
                        map.fitBounds(routeLine.getBounds(), { padding: [35, 35], maxZoom: 15 });
                    } else if (bounds.length > 1) {
                        map.fitBounds(bounds, { padding: [35, 35], maxZoom: 15 });
                    } else if (liveMarker) {
                        map.setView(liveMarker.getLatLng(), 16);
                    }
                }, 80);
            }

            function openLargeMap() {
                mapFullscreenTarget.appendChild(document.getElementById('transport-map'));
                document.body.classList.add('map-fullscreen-open');
                mapFullscreen.setAttribute('aria-hidden', 'false');
                resizeMapSoon();
            }

            function closeLargeMap() {
                mapShell.appendChild(document.getElementById('transport-map'));
                document.body.classList.remove('map-fullscreen-open');
                mapFullscreen.setAttribute('aria-hidden', 'true');
                resizeMapSoon();
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
                stopDemo('GPS real activo. La simulacion queda pausada.');
                setDemoProgress(0);
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
                stopRealGps();
                gpsStart.disabled = false;
                gpsStop.disabled = true;
                gpsStatus.textContent = 'GPS detenido.';
            });

            gpsDemoStart?.addEventListener('click', startDemo);
            gpsDemoStop?.addEventListener('click', function () {
                stopDemo('Simulacion pausada. Puedes retomarla con el mismo boton.');
                gpsStatus.textContent = 'Simulacion pausada.';
            });

            function updateState(action, cancellationReason, driverSignature) {
                fetch(stateUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrf
                    },
                    body: JSON.stringify({
                        accion: action,
                        motivo_cancelacion: cancellationReason || null,
                        firma_transportista: driverSignature || null
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

            function bindSignatureCanvas(canvas) {
                const ctx = canvas.getContext('2d');
                let drawing = false;
                let signed = false;

                ctx.lineWidth = 2.4;
                ctx.lineCap = 'round';
                ctx.strokeStyle = '#172817';

                function point(event) {
                    const rect = canvas.getBoundingClientRect();
                    const source = event.touches ? event.touches[0] : event;
                    return { x: source.clientX - rect.left, y: source.clientY - rect.top };
                }

                function start(event) {
                    event.preventDefault();
                    drawing = true;
                    signed = true;
                    const p = point(event);
                    ctx.beginPath();
                    ctx.moveTo(p.x, p.y);
                }

                function move(event) {
                    if (!drawing) return;
                    event.preventDefault();
                    const p = point(event);
                    ctx.lineTo(p.x, p.y);
                    ctx.stroke();
                }

                function stop() { drawing = false; }

                canvas.addEventListener('mousedown', start);
                canvas.addEventListener('mousemove', move);
                window.addEventListener('mouseup', stop);
                canvas.addEventListener('touchstart', start, { passive: false });
                canvas.addEventListener('touchmove', move, { passive: false });
                canvas.addEventListener('touchend', stop);

                return {
                    isSigned: function () { return signed; },
                    clear: function () {
                        signed = false;
                        ctx.clearRect(0, 0, canvas.width, canvas.height);
                    },
                    data: function () { return canvas.toDataURL('image/png'); }
                };
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
                    text: isWaitingBuyer ? undefined : text,
                    html: isWaitingBuyer
                        ? '<p>' + text + '</p><p class="text-muted">Firma como transportista para dejar constancia de la entrega.</p>' +
                            '<canvas id="driver-signature-canvas" width="520" height="180" style="width:100%;max-width:520px;border:1px solid #cfdccc;border-radius:6px;background:#fff"></canvas>' +
                            '<button type="button" id="driver-signature-clear" class="btn btn-sm btn-light mt-2">Limpiar firma</button>'
                        : undefined,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: confirmText,
                    cancelButtonText: 'Todavía no',
                    confirmButtonColor: '#2f7d24',
                    cancelButtonColor: '#6c757d',
                    reverseButtons: true,
                    focusCancel: true,
                    allowOutsideClick: false,
                    didOpen: function () {
                        if (!isWaitingBuyer) return;

                        window.driverSignaturePad = bindSignatureCanvas(document.getElementById('driver-signature-canvas'));
                        document.getElementById('driver-signature-clear').addEventListener('click', function () {
                            window.driverSignaturePad.clear();
                        });
                    },
                    preConfirm: function () {
                        if (isWaitingBuyer && (!window.driverSignaturePad || !window.driverSignaturePad.isSigned())) {
                            Swal.showValidationMessage('Dibuja tu firma antes de confirmar la entrega.');
                            return false;
                        }

                        return isWaitingBuyer ? window.driverSignaturePad.data() : null;
                    }
                }).then(function (result) {
                    if (!result.isConfirmed) return;

                    Swal.fire({
                        title: 'Actualizando el recorrido...',
                        html: '<div class="transport-loading">' +
                            '<img src="{{ asset('img/brand/logo-agrovida.jpeg') }}" alt="AgroVida">' +
                            '</div>',
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        allowEscapeKey: false
                    });

                    updateState('avanzar', null, result.value);
                });
            }

            document.getElementById('state-next')?.addEventListener('click', function () {
                confirmStateAdvance();
            });

            document.getElementById('map-expand')?.addEventListener('click', openLargeMap);
            document.getElementById('map-close')?.addEventListener('click', closeLargeMap);
            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape' && document.body.classList.contains('map-fullscreen-open')) {
                    closeLargeMap();
                }
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
                            '<img src="{{ asset('img/brand/logo-agrovida.jpeg') }}" alt="AgroVida">' +
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
