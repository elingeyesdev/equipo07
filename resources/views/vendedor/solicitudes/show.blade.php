@extends('layouts.adminlte')

@section('title', 'Solicitud #' . $solicitud->id)
@section('page_title', 'Solicitud #' . $solicitud->id)

@section('content')
    @php
        $badge = [
            'pendiente' => 'warning',
            'aceptada' => 'success',
            'rechazada' => 'secondary',
            'cancelada_producto_vendido' => 'danger',
        ][$solicitud->estado_solicitud] ?? 'secondary';
        $mapsUrl = null;
        if ($solicitud->pedido->destino_latitud && $solicitud->pedido->destino_longitud) {
            $mapsUrl = $solicitud->product_latitud && $solicitud->product_longitud
                ? 'https://www.google.com/maps/dir/?api=1&origin=' . $solicitud->product_latitud . ',' . $solicitud->product_longitud . '&destination=' . $solicitud->pedido->destino_latitud . ',' . $solicitud->pedido->destino_longitud . '&travelmode=driving'
                : 'https://www.google.com/maps/search/?api=1&query=' . $solicitud->pedido->destino_latitud . ',' . $solicitud->pedido->destino_longitud;
        }
        $isMaquinaria = $solicitud->es_alquiler_maquinaria;
        $cantidadLabel = $solicitud->cantidad_label;
        $precioLabel = $solicitud->precio_label;
        $totalLabel = $isMaquinaria ? 'Total del alquiler' : 'Subtotal';
        $usaTransporteExterno = in_array($solicitud->product_type, ['organico', 'maquinaria'], true);
        $deliveryLabels = $isMaquinaria
            ? \App\Models\PedidoDetalle::transporteFases()
            : \App\Services\TransporteAccesoService::ESTADOS_ORGANICO;
        $deliveryStatePhases = $isMaquinaria ? \App\Models\PedidoDetalle::transporteEstadoFases() : [];
        $deliveryFlow = array_keys($deliveryLabels);
        $deliveryCurrent = $solicitud->estado_transporte_actual === 'asignado' && $solicitud->product_type === 'organico'
            ? 'aceptado'
            : $solicitud->estado_transporte_actual;
        $deliveryCurrentVisible = $deliveryStatePhases[$deliveryCurrent] ?? $deliveryCurrent;
        $deliveryIndex = array_search($deliveryCurrentVisible, $deliveryFlow, true);
    @endphp

    <style>
        .rental-tracking-card {
            border: 1px solid rgba(63, 126, 42, .16);
            border-radius: 12px;
            background: #f7fbf4;
        }

        .rental-tracking-steps {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(110px, 1fr));
            gap: .65rem;
        }

        .rental-tracking-step {
            min-height: 74px;
            padding: .65rem;
            border: 1px solid #dfe8dc;
            border-radius: 10px;
            background: #fff;
            color: #6b776b;
            font-size: .78rem;
            font-weight: 700;
        }

        .rental-tracking-step span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 1.7rem;
            height: 1.7rem;
            margin-bottom: .35rem;
            border-radius: 999px;
            background: #eef4ea;
            color: #3f7e2a;
        }

        .rental-tracking-step.is-done,
        .rental-tracking-step.is-current {
            border-color: rgba(63, 126, 42, .35);
            color: #1f2a1b;
        }

        .rental-tracking-step.is-current {
            background: #eef8ea;
            box-shadow: 0 8px 18px rgba(63, 126, 42, .08);
        }

        .rental-tracking-step.is-done span,
        .rental-tracking-step.is-current span {
            color: #fff;
            background: #2f7d24;
        }

        .solicitud-map-legend {
            display: flex;
            flex-wrap: wrap;
            gap: .5rem;
            margin-bottom: .75rem;
        }

        .solicitud-map-legend__item {
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

        .solicitud-map-dot {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 1.35rem;
            height: 1.35rem;
            border-radius: 999px;
            color: #fff;
            font-size: .72rem;
        }

        .solicitud-map-dot--driver,
        .solicitud-map-pin--driver .solicitud-map-pin__icon {
            background: #0d6efd;
        }

        .solicitud-map-dot--product,
        .solicitud-map-pin--product .solicitud-map-pin__icon {
            background: #fd7e14;
        }

        .solicitud-map-dot--customer,
        .solicitud-map-pin--customer .solicitud-map-pin__icon {
            background: #198754;
        }

        .solicitud-map-pin {
            display: flex;
            align-items: center;
            gap: .35rem;
            padding: .22rem .48rem .22rem .22rem;
            border: 2px solid #fff;
            border-radius: 999px;
            background: rgba(255, 255, 255, .95);
            box-shadow: 0 4px 12px rgba(0, 0, 0, .22);
            white-space: nowrap;
        }

        .solicitud-map-pin__icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 1.75rem;
            height: 1.75rem;
            border-radius: 999px;
            color: #fff;
        }

        .solicitud-map-pin__label {
            color: #1f2a1b;
            font-size: .72rem;
            font-weight: 800;
        }
    </style>

    <div class="container-fluid">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle mr-1"></i>{{ session('success') }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle mr-1"></i>{{ session('error') }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        @endif

        <div class="row">
            <div class="col-lg-5">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-box-open mr-1"></i>{{ $solicitud->nombre_producto }}
                        </h3>
                    </div>
                    <div class="card-body">
                        <dl class="row mb-0">
                            <dt class="col-sm-5">Estado</dt>
                            <dd class="col-sm-7">
                                <span class="badge badge-{{ $badge }}">
                                    {{ $estados[$solicitud->estado_solicitud] ?? ucfirst($solicitud->estado_solicitud) }}
                                </span>
                            </dd>

                            <dt class="col-sm-5">Comprador</dt>
                            <dd class="col-sm-7">
                                {{ $solicitud->pedido->user->name ?? 'No disponible' }}<br>
                                <small class="text-muted">{{ $solicitud->pedido->user->email ?? '' }}</small>
                            </dd>

                            <dt class="col-sm-5">Telefono</dt>
                            <dd class="col-sm-7">
                                @if ($solicitud->pedido->telefono_contacto)
                                    <a href="tel:{{ $solicitud->pedido->telefono_contacto }}">
                                        {{ $solicitud->pedido->telefono_contacto }}
                                    </a>
                                @else
                                    No especificado
                                @endif
                            </dd>

                            <dt class="col-sm-5">Fecha solicitud</dt>
                            <dd class="col-sm-7">{{ $solicitud->pedido->created_at->format('d/m/Y H:i') }}</dd>

                            <dt class="col-sm-5">{{ $cantidadLabel }}</dt>
                            <dd class="col-sm-7">{{ $solicitud->cantidad_tiempo_texto }}</dd>

                            <dt class="col-sm-5">{{ $precioLabel }}</dt>
                            <dd class="col-sm-7">Bs {{ number_format($solicitud->precio_unitario, 2) }}</dd>

                            <dt class="col-sm-5">{{ $totalLabel }}</dt>
                            <dd class="col-sm-7"><strong>Bs {{ number_format($solicitud->subtotal, 2) }}</strong></dd>

                            @if ($solicitud->notas)
                                <dt class="col-sm-5">Notas</dt>
                                <dd class="col-sm-7">{{ $solicitud->notas }}</dd>
                            @endif

                            @if ($solicitud->estado_solicitud === 'aceptada' && $usaTransporteExterno)
                                <dt class="col-sm-5">Acceso transporte</dt>
                                <dd class="col-sm-7">
                                    @if ($solicitud->transporteAcceso?->estaActivo())
                                        <span class="badge badge-success">Codigo/QR activo</span>
                                    @else
                                        <span class="badge badge-secondary">Sin codigo activo</span>
                                    @endif
                                </dd>
                            @endif
                            @if ($solicitud->estado_solicitud === 'aceptada')
                                <dt class="col-sm-5">Transporte</dt>
                                <dd class="col-sm-7">
                                    <span class="badge badge-primary" data-vendor-transport-label>
                                        {{ $solicitud->estado_transporte_label }}
                                    </span>
                                </dd>
                            @endif
                        </dl>
                    </div>

                    @if ($solicitud->estado_solicitud === 'aceptada' && $usaTransporteExterno)
                        <div class="card-body border-top">
                            <div class="rental-tracking-card p-3 mb-3">
                                <div class="d-flex flex-wrap justify-content-between align-items-start mb-3">
                                    <h5 class="mb-1 font-weight-bold">
                                        <i class="fas fa-route mr-1"></i>Seguimiento de entrega
                                    </h5>
                                    <span class="badge badge-success" id="seller-delivery-status">
                                        {{ $solicitud->estado_transporte_label }}
                                    </span>
                                </div>
                                <div class="rental-tracking-steps" id="seller-delivery-steps">
                                    @foreach ($deliveryFlow as $index => $state)
                                        @php
                                            $stepClass = $deliveryIndex !== false && $index < $deliveryIndex
                                                ? 'is-done'
                                                : ($state === $deliveryCurrentVisible ? 'is-current' : '');
                                        @endphp
                                        <div class="rental-tracking-step {{ $stepClass }}" data-state="{{ $state }}">
                                            <span>
                                                @if ($stepClass === 'is-done')
                                                    <i class="fas fa-check"></i>
                                                @else
                                                    {{ $index + 1 }}
                                                @endif
                                            </span>
                                            <div>{{ $deliveryLabels[$state] ?? ucfirst(str_replace('_', ' ', $state)) }}</div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            @if (!$isMaquinaria && in_array($deliveryCurrent, ['aceptado', 'asignado'], true))
                                <form method="POST"
                                    action="{{ route('vendedor.solicitudes.transporte.preparado', $solicitud) }}"
                                    class="mb-3 question-confirm-form"
                                    data-confirm-title="¿Habilitar el transporte?"
                                    data-confirm-text="La persona con el codigo o QR podra activar el GPS y avanzar el recorrido."
                                    data-confirm-button="Sí, habilitar"
                                    data-cancel-button="Todavía no"
                                    data-loading-text="Actualizando el estado...">
                                    @csrf
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-box-open mr-1"></i>Habilitar transporte
                                    </button>
                                    <small class="text-muted d-block mt-2">
                                        Al marcarlo, el acceso por codigo/QR podra activar el GPS e iniciar el recorrido.
                                    </small>
                                </form>
                            @endif

                            <div class="d-flex flex-wrap justify-content-between align-items-start mb-3">
                                <div>
                                    <h5 class="mb-1 font-weight-bold">
                                        <i class="fas fa-key mr-1"></i>Acceso de transporte externo
                                    </h5>
                                    <small class="text-muted">
                                        Comparte este codigo con la persona que realizara la entrega.
                                    </small>
                                </div>
                                @if ($solicitud->transporteAcceso?->estaActivo())
                                    <span class="badge badge-success">Activo</span>
                                @else
                                    <span class="badge badge-secondary">Sin acceso activo</span>
                                @endif
                            </div>

                            @if ($solicitud->transporteAcceso?->estaActivo())
                                <div class="row align-items-center">
                                    <div class="col-md-7">
                                        <div class="input-group mb-2">
                                            <input type="text" class="form-control font-weight-bold"
                                                id="transport-code" value="{{ $solicitud->transporteAcceso->codigo }}" readonly>
                                            <div class="input-group-append">
                                                <button type="button" class="btn btn-outline-success"
                                                    onclick="navigator.clipboard.writeText(document.getElementById('transport-code').value)">
                                                    <i class="fas fa-copy mr-1"></i>Copiar
                                                </button>
                                            </div>
                                        </div>
                                        <small class="text-muted d-block mb-3">
                                            Vigente hasta:
                                            {{ $solicitud->transporteAcceso->expires_at?->format('d/m/Y H:i') ?? 'sin limite' }}.
                                            El transportista puede escribir el codigo o escanear este QR.
                                        </small>
                                    </div>
                                    <div class="col-md-5 text-center mb-3">
                                        <div class="border rounded bg-white p-2 d-inline-block">
                                            <canvas id="transport-qr-canvas" width="220" height="220"
                                                aria-label="Codigo QR de transporte"></canvas>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-success d-block mx-auto mt-2"
                                            id="transport-qr-download">
                                            <i class="fas fa-download mr-1"></i>Descargar QR
                                        </button>
                                    </div>
                                </div>
                            @endif

                            <div class="d-flex flex-wrap">
                                <form method="POST"
                                    action="{{ route('vendedor.solicitudes.transporte.codigo', $solicitud) }}"
                                    class="mr-2 mb-2">
                                    @csrf
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-sync-alt mr-1"></i>
                                        {{ $solicitud->transporteAcceso?->estaActivo() ? 'Regenerar codigo' : 'Generar codigo' }}
                                    </button>
                                </form>

                                @if ($solicitud->transporteAcceso?->estaActivo())
                                    <form method="POST"
                                        action="{{ route('vendedor.solicitudes.transporte.revocar', $solicitud) }}"
                                        class="mb-2"
                                        onsubmit="return confirm('¿Revocar el acceso externo de transporte?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger">
                                            <i class="fas fa-ban mr-1"></i>Revocar acceso
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endif

                    @if(in_array($solicitud->product_type, ['organico', 'maquinaria'], true)
                        && $solicitud->estado_solicitud === 'aceptada'
                        && (
                            $solicitud->recepcion_confirmada_at
                            || in_array($solicitud->estado_transporte_actual, ['entregado', 'cancelado'], true)
                            || $solicitud->pedido->estado === 'finalizado'
                        ))
                        <div class="card-body border-top">
                            @include('organicos.partials.postventa', ['detalle' => $solicitud, 'modo' => 'vendedor'])
                        </div>
                    @endif

                    @if ($solicitud->estado_solicitud === 'pendiente')
                        <div class="card-footer d-flex flex-wrap">
                            <form action="{{ route('vendedor.solicitudes.aceptar', $solicitud) }}" method="POST"
                                class="mr-2 mb-2 question-confirm-form"
                                data-confirm-title="¿Aceptar esta solicitud?"
                                data-confirm-text="Venderás este producto al comprador seleccionado y las demás solicitudes pendientes del mismo producto serán canceladas."
                                data-confirm-button="Sí, aceptar solicitud"
                                data-cancel-button="Revisar nuevamente"
                                data-loading-text="Aceptando la solicitud...">
                                @csrf
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-check mr-1"></i>Vender a este comprador
                                </button>
                            </form>

                            <form action="{{ route('vendedor.solicitudes.cancelar', $solicitud) }}" method="POST"
                                class="mb-2"
                                onsubmit="return confirm('¿Rechazar esta solicitud?')">
                                @csrf
                                <button type="submit" class="btn btn-outline-secondary">
                                    <i class="fas fa-times mr-1"></i>Rechazar
                                </button>
                            </form>
                        </div>
                    @elseif ($solicitud->estado_solicitud === 'aceptada'
                        && $solicitud->pedido->estado !== 'finalizado'
                        && $solicitud->estado_transporte_actual !== 'cancelado')
                        <div class="card-footer">
                            <form action="{{ route('vendedor.solicitudes.finalizar', $solicitud) }}" method="POST"
                                class="question-confirm-form"
                                data-confirm-title="¿Finalizar este pedido?"
                                data-confirm-text="Confirma solo cuando el producto ya cumplió todo el flujo de entrega o devolución."
                                data-confirm-button="Sí, finalizar"
                                data-cancel-button="Todavía no"
                                data-loading-text="Finalizando el pedido...">
                                @csrf
                                <button type="submit" class="btn btn-success" data-vendor-finalize-button
                                    {{ $solicitud->puede_finalizar_desde_vendedor ? '' : 'disabled' }}>
                                    <i class="fas fa-flag-checkered mr-1"></i>Finalizar pedido
                                </button>
                                @if (!$solicitud->puede_finalizar_desde_vendedor && $isMaquinaria)
                                    <small class="text-muted d-block mt-2">
                                        Para finalizar, primero el transportista debe devolver la maquinaria.
                                    </small>
                                @elseif (!$solicitud->puede_finalizar_desde_vendedor)
                                    <small class="text-muted d-block mt-2">
                                        Para finalizar, primero el comprador debe confirmar la recepcion.
                                    </small>
                                @endif
                            </form>
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-lg-7">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-map-marker-alt mr-1"></i>Destino solicitado
                        </h3>
                    </div>
                    <div class="card-body">
                        <p>{{ $solicitud->pedido->destino_entrega }}</p>

                        @if (!is_null($solicitud->distancia_destino_km))
                            <div class="alert alert-info">
                                <strong><i class="fas fa-route mr-1"></i>Distancia aproximada:</strong>
                                {{ number_format($solicitud->distancia_destino_km, 1) }} km entre tu producto y el destino del comprador.
                            </div>
                        @else
                            <div class="alert alert-light border">
                                <i class="fas fa-info-circle mr-1"></i>
                                No se pudo calcular la distancia porque el producto no tiene coordenadas registradas.
                            </div>
                        @endif

                        @if ($solicitud->pedido->destino_latitud && $solicitud->pedido->destino_longitud)
                            <div class="solicitud-map-legend">
                                <span class="solicitud-map-legend__item">
                                    <span class="solicitud-map-dot solicitud-map-dot--driver">
                                        <i class="fas fa-truck"></i>
                                    </span>
                                    Transportista
                                </span>
                                <span class="solicitud-map-legend__item">
                                    <span class="solicitud-map-dot solicitud-map-dot--product">
                                        <i class="fas fa-box"></i>
                                    </span>
                                    Producto / vendedor
                                </span>
                                <span class="solicitud-map-legend__item">
                                    <span class="solicitud-map-dot solicitud-map-dot--customer">
                                        <i class="fas fa-home"></i>
                                    </span>
                                    Destino comprador
                                </span>
                            </div>
                            <div id="solicitud-destino-map"
                                style="height: 460px; width: 100%; border-radius: 8px; overflow: hidden;"></div>
                            <a class="btn btn-sm btn-outline-success mt-3" target="_blank"
                                href="{{ $mapsUrl }}">
                                <i class="fas fa-external-link-alt mr-1"></i>Abrir ruta en Google Maps
                            </a>
                        @else
                            <div class="alert alert-warning mb-0">
                                Esta solicitud no tiene coordenadas de destino.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <a href="{{ route('vendedor.solicitudes.index') }}" class="btn btn-light">
            <i class="fas fa-arrow-left mr-1"></i>Volver
        </a>
    </div>

    @if ($solicitud->pedido->destino_latitud && $solicitud->pedido->destino_longitud)
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var destinoLat = {{ $solicitud->pedido->destino_latitud }};
                var destinoLng = {{ $solicitud->pedido->destino_longitud }};
                var productoLat = @json($solicitud->product_latitud);
                var productoLng = @json($solicitud->product_longitud);
                var transportistaLat = @json($solicitud->ultimaUbicacion?->latitud ? (float) $solicitud->ultimaUbicacion->latitud : null);
                var transportistaLng = @json($solicitud->ultimaUbicacion?->longitud ? (float) $solicitud->ultimaUbicacion->longitud : null);
                var googleMapsUrl = @json($mapsUrl);
                var trackingUrl = @json(route('pedidos.detalles.tracking.latest', $solicitud, false));
                var map = L.map('solicitud-destino-map').setView([destinoLat, destinoLng], 16);
                var transportistaMarker = null;
                var deliveryFlow = @json($deliveryFlow);
                var deliveryStatePhases = @json($deliveryStatePhases);
                var trackingBusy = false;
                var trackingTimer = null;

                function mapIcon(type, icon, label) {
                    return L.divIcon({
                        className: '',
                        html: '<div class="solicitud-map-pin solicitud-map-pin--' + type + '">' +
                            '<span class="solicitud-map-pin__icon"><i class="fas ' + icon + '"></i></span>' +
                            '<span class="solicitud-map-pin__label">' + label + '</span>' +
                        '</div>',
                        iconSize: [150, 36],
                        iconAnchor: [18, 18],
                        popupAnchor: [0, -18]
                    });
                }

                var destinoIcon = mapIcon('customer', 'fa-home', 'Comprador');
                var productoIcon = mapIcon('product', 'fa-box', 'Producto');
                var transportistaIcon = mapIcon('driver', 'fa-truck', 'Transportista');

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(map);

                var destinoMarker = L.marker([destinoLat, destinoLng], {
                        icon: destinoIcon
                    })
                    .addTo(map)
                    .bindPopup('<strong>Destino del comprador</strong><br>' + @json($solicitud->pedido->destino_entrega));

                if (transportistaLat && transportistaLng) {
                    transportistaMarker = L.marker([transportistaLat, transportistaLng], {
                            icon: transportistaIcon
                        })
                        .addTo(map)
                        .bindPopup('<strong>Ultima ubicacion del transportista</strong><br>{{ $solicitud->ultimaUbicacion?->created_at?->format('d/m/Y H:i:s') }}');
                }

                if (productoLat && productoLng) {
                    var productoMarker = L.marker([productoLat, productoLng], {
                            icon: productoIcon
                        })
                        .addTo(map)
                        .bindPopup('<strong>Producto / punto del vendedor</strong><br>' + @json($solicitud->nombre_producto));

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

                        fallbackLine.bindPopup('Distancia aproximada: {{ number_format($solicitud->distancia_destino_km ?? 0, 1) }} km');

                        var fallbackBounds = fallbackLine.getBounds();

                        if (transportistaLat && transportistaLng) {
                            fallbackBounds.extend([transportistaLat, transportistaLng]);
                        }

                        map.fitBounds(fallbackBounds, {
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

                            var routeBounds = routeLine.getBounds();

                            if (transportistaLat && transportistaLng) {
                                routeBounds.extend([transportistaLat, transportistaLng]);
                            }

                            map.fitBounds(routeBounds, {
                                padding: [40, 40],
                                maxZoom: 14
                            });
                        })
                        .catch(function() {
                            drawFallbackLine();
                        })
                        .finally(function() {
                            destinoMarker.openPopup();
                        });
                } else {
                    if (transportistaLat && transportistaLng) {
                        map.fitBounds(L.latLngBounds([
                            [destinoLat, destinoLng],
                            [transportistaLat, transportistaLng]
                        ]), {
                            padding: [40, 40],
                            maxZoom: 14
                        });
                    }

                    destinoMarker.openPopup();
                }

                function refreshTracking() {
                    if (trackingBusy || document.hidden) {
                        scheduleTracking();
                        return;
                    }

                    trackingBusy = true;
                    fetch(trackingUrl, { headers: { 'Accept': 'application/json' } })
                        .then(function(response) {
                            if (!response.ok) throw new Error('Tracking no disponible');
                            return response.json();
                        })
                        .then(function(data) {
                            var status = document.getElementById('seller-delivery-status');
                            if (status) status.textContent = data.estado_label;

                            var currentVisibleState = deliveryStatePhases[data.estado] || data.estado;
                            var currentIndex = deliveryFlow.indexOf(currentVisibleState);
                            document.querySelectorAll('#seller-delivery-steps [data-state]').forEach(function(step) {
                                var index = deliveryFlow.indexOf(step.dataset.state);
                                step.classList.toggle('is-done', currentIndex >= 0 && index < currentIndex);
                                step.classList.toggle('is-current', step.dataset.state === currentVisibleState);
                            });

                            if (!data.ubicacion) return;
                            var position = [data.ubicacion.latitud, data.ubicacion.longitud];

                            if (!transportistaMarker) {
                                transportistaMarker = L.marker(position, { icon: transportistaIcon }).addTo(map);
                            } else {
                                transportistaMarker.setLatLng(position);
                            }

                            transportistaMarker.bindPopup(
                                '<strong>Ubicacion actual del transportista</strong><br>' +
                                (data.ubicacion.fecha_humana || '')
                            );
                        })
                        .catch(function() {})
                        .finally(function() {
                            trackingBusy = false;
                            scheduleTracking();
                        });
                }

                function scheduleTracking() {
                    clearTimeout(trackingTimer);
                    trackingTimer = setTimeout(refreshTracking, 12000);
                }

                document.addEventListener('visibilitychange', function() {
                    if (!document.hidden) {
                        clearTimeout(trackingTimer);
                        refreshTracking();
                    }
                });

                refreshTracking();
            });
        </script>
    @endif
    @if ($usaTransporteExterno && $solicitud->transporteAcceso?->estaActivo())
        <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.4/build/qrcode.min.js"></script>
        <script src="{{ asset('js/transport-qr-seller.js') }}?v={{ filemtime(public_path('js/transport-qr-seller.js')) }}"></script>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var estadoUrl = @json(route('pedidos.detalles.estadoTransporte', $solicitud, false));

            function applyState(data) {
                var transportLabel = document.querySelector('[data-vendor-transport-label]');
                var finalizeButton = document.querySelector('[data-vendor-finalize-button]');

                if (transportLabel && data.estado_transporte_label) {
                    transportLabel.textContent = data.estado_transporte_label;
                }

                if (finalizeButton) {
                    finalizeButton.disabled = !data.puede_finalizar_desde_vendedor;
                }
            }

            function refreshState() {
                fetch(estadoUrl, {
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
                            applyState(data);
                        }
                    })
                    .catch(function() {});
            }

            refreshState();
            setInterval(refreshState, 5000);
        });
    </script>
@endsection
