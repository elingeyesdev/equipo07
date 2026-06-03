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
        $detalleConUbicacion = $pedido->detalles->first(fn($detalle) => $detalle->product_latitud && $detalle->product_longitud);
        $mapsUrl = null;

        if ($pedido->destino_latitud && $pedido->destino_longitud) {
            $mapsUrl = $detalleConUbicacion
                ? 'https://www.google.com/maps/dir/?api=1&origin=' . $detalleConUbicacion->product_latitud . ',' . $detalleConUbicacion->product_longitud . '&destination=' . $pedido->destino_latitud . ',' . $pedido->destino_longitud . '&travelmode=driving'
                : 'https://www.google.com/maps/search/?api=1&query=' . $pedido->destino_latitud . ',' . $pedido->destino_longitud;
        }
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
                                <tr>
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
                                        @if ($isMaquinaria && $detalle->estado_solicitud === 'aceptada')
                                            <div class="order-rental-tracking">
                                                <div class="order-rental-tracking__title">
                                                    <span><i class="fas fa-route mr-1"></i>Seguimiento del alquiler</span>
                                                    <span class="text-success">{{ $detalle->estado_alquiler_label }}</span>
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
                                                        <div class="order-rental-tracking__step {{ $stepClass }}">
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
                var map = L.map('pedido-destino-map').setView([destinoLat, destinoLng], 16);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(map);

                L.marker([destinoLat, destinoLng])
                    .addTo(map)
                    .bindPopup('<strong>Destino del comprador</strong><br>' + @json($pedido->destino_entrega))
                    .openPopup();

                if (productoLat && productoLng) {
                    L.marker([productoLat, productoLng])
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
            });
        </script>
    @endif
@endsection
