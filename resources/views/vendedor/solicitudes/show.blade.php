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
        $alquilerEstados = \App\Models\PedidoDetalle::alquilerEstados();
        $estadoAlquilerActual = $solicitud->estado_alquiler_actual;
        $estadoKeys = array_keys($alquilerEstados);
        $estadoActualIndex = $estadoAlquilerActual ? array_search($estadoAlquilerActual, $estadoKeys, true) : false;
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
                        </dl>
                    </div>

                    @if ($isMaquinaria && $solicitud->estado_solicitud === 'aceptada')
                        <div class="card-body border-top">
                            <div class="rental-tracking-card p-3">
                                <div class="d-flex flex-wrap justify-content-between align-items-start mb-3">
                                    <div>
                                        <h5 class="mb-1 font-weight-bold">
                                            <i class="fas fa-route mr-1"></i>Seguimiento del alquiler
                                        </h5>
                                        <small class="text-muted">Avanza el estado conforme se mueve la maquinaria.</small>
                                    </div>
                                    <span class="badge badge-success mt-2 mt-md-0">
                                        {{ $solicitud->estado_alquiler_label }}
                                    </span>
                                </div>

                                <div class="rental-tracking-steps mb-3">
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
                                        <div class="rental-tracking-step {{ $stepClass }}">
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

                                @if ($solicitud->siguiente_estado_alquiler)
                                    <form action="{{ route('vendedor.solicitudes.alquiler.avanzar', $solicitud) }}"
                                        method="POST" class="mb-0">
                                        @csrf
                                        <button type="submit" class="btn btn-success">
                                            <i class="fas fa-arrow-right mr-1"></i>
                                            Marcar como {{ strtolower($solicitud->siguiente_estado_alquiler_label) }}
                                        </button>
                                    </form>
                                @elseif ($solicitud->estado_alquiler_actual === 'devuelto')
                                    <div class="alert alert-success mb-0">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        La maquinaria fue devuelta. Ya puedes finalizar el alquiler.
                                    </div>
                                @else
                                    <div class="alert alert-light border mb-0">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        El seguimiento de este alquiler ya no tiene pasos pendientes.
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    @if ($solicitud->estado_solicitud === 'pendiente')
                        <div class="card-footer d-flex flex-wrap">
                            <form action="{{ route('vendedor.solicitudes.aceptar', $solicitud) }}" method="POST"
                                class="mr-2 mb-2"
                                onsubmit="return confirm('Aceptar esta solicitud cancelara las demas solicitudes pendientes de este producto. ¿Continuar?')">
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
                    @elseif ($solicitud->estado_solicitud === 'aceptada' && $solicitud->pedido->estado !== 'finalizado')
                        <div class="card-footer">
                            <form action="{{ route('vendedor.solicitudes.finalizar', $solicitud) }}" method="POST"
                                onsubmit="return confirm('¿Finalizar este pedido?')">
                                @csrf
                                <button type="submit" class="btn btn-success"
                                    {{ $solicitud->puede_finalizar_desde_vendedor ? '' : 'disabled' }}>
                                    <i class="fas fa-flag-checkered mr-1"></i>Finalizar pedido
                                </button>
                                @if (!$solicitud->puede_finalizar_desde_vendedor && $isMaquinaria)
                                    <small class="text-muted d-block mt-2">
                                        Para finalizar, primero marca el alquiler como devuelto.
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
                var googleMapsUrl = @json($mapsUrl);
                var map = L.map('solicitud-destino-map').setView([destinoLat, destinoLng], 16);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(map);

                var destinoMarker = L.marker([destinoLat, destinoLng])
                    .addTo(map)
                    .bindPopup('<strong>Destino del comprador</strong><br>' + @json($solicitud->pedido->destino_entrega));

                if (productoLat && productoLng) {
                    var productoMarker = L.marker([productoLat, productoLng])
                        .addTo(map)
                        .bindPopup('<strong>Tu producto</strong><br>' + @json($solicitud->nombre_producto));

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
                        .catch(function() {
                            drawFallbackLine();
                        })
                        .finally(function() {
                            destinoMarker.openPopup();
                        });
                } else {
                    destinoMarker.openPopup();
                }
            });
        </script>
    @endif
@endsection
