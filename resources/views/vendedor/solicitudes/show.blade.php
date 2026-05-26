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
    @endphp

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

                            <dt class="col-sm-5">Fecha solicitud</dt>
                            <dd class="col-sm-7">{{ $solicitud->pedido->created_at->format('d/m/Y H:i') }}</dd>

                            <dt class="col-sm-5">Cantidad</dt>
                            <dd class="col-sm-7">{{ $solicitud->cantidad }}</dd>

                            <dt class="col-sm-5">Precio unitario</dt>
                            <dd class="col-sm-7">Bs {{ number_format($solicitud->precio_unitario, 2) }}</dd>

                            <dt class="col-sm-5">Subtotal</dt>
                            <dd class="col-sm-7"><strong>Bs {{ number_format($solicitud->subtotal, 2) }}</strong></dd>

                            @if ($solicitud->notas)
                                <dt class="col-sm-5">Notas</dt>
                                <dd class="col-sm-7">{{ $solicitud->notas }}</dd>
                            @endif
                        </dl>
                    </div>

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
                                href="https://www.openstreetmap.org/?mlat={{ $solicitud->pedido->destino_latitud }}&mlon={{ $solicitud->pedido->destino_longitud }}#map=16/{{ $solicitud->pedido->destino_latitud }}/{{ $solicitud->pedido->destino_longitud }}">
                                <i class="fas fa-external-link-alt mr-1"></i>Abrir mapa
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

                    var routeLine = L.polyline([
                        [productoLat, productoLng],
                        [destinoLat, destinoLng]
                    ], {
                        color: '#28a745',
                        weight: 4,
                        opacity: 0.85,
                        dashArray: '8, 8'
                    }).addTo(map);

                    routeLine.bindPopup('Distancia aproximada: {{ number_format($solicitud->distancia_destino_km ?? 0, 1) }} km');

                    map.fitBounds(routeLine.getBounds(), {
                        padding: [40, 40],
                        maxZoom: 14
                    });

                    destinoMarker.openPopup();
                } else {
                    destinoMarker.openPopup();
                }
            });
        </script>
    @endif
@endsection
