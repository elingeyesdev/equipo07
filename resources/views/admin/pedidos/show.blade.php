@extends('layouts.adminlte')

@section('title', 'Pedido #' . $pedido->id)
@section('page_title', 'Pedido #' . $pedido->id)

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
    @endphp

    <div class="container-fluid orders-page">
        <style>
            .admin-delivery-tracking {
                min-width: 360px;
                margin-top: .75rem;
                padding: .8rem;
                border: 1px solid rgba(63, 126, 42, .16);
                border-radius: 8px;
                background: #f7fbf4;
            }

            .admin-delivery-tracking__header {
                display: flex;
                justify-content: space-between;
                gap: .75rem;
                margin-bottom: .65rem;
                font-size: .78rem;
                font-weight: 800;
            }

            .admin-delivery-tracking__steps {
                display: grid;
                grid-template-columns: repeat(5, minmax(84px, 1fr));
                gap: .4rem;
            }

            .admin-delivery-tracking__step {
                padding: .45rem;
                border: 1px solid #dfe8dc;
                border-radius: 6px;
                color: #6b776b;
                background: #fff;
                font-size: .68rem;
                font-weight: 700;
                text-align: center;
            }

            .admin-delivery-tracking__step.is-done,
            .admin-delivery-tracking__step.is-current {
                border-color: rgba(63, 126, 42, .38);
                color: #245c1c;
            }

            .admin-delivery-tracking__step.is-current {
                background: #eaf6e6;
            }

            @media (max-width: 768px) {
                .admin-delivery-tracking { min-width: 0; }
                .admin-delivery-tracking__steps { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            }
        </style>
        <div class="orders-card orders-detail-card">
            <div class="orders-detail-header">
                <div class="orders-detail-header__title">
                    <span><i class="fas fa-receipt"></i></span>
                    <div>
                        <h2>Pedido #{{ $pedido->id }}</h2>
                        <small>
                            Fecha: {{ $pedido->created_at->format('d/m/Y H:i') }} |
                            Cliente: {{ $pedido->user->name }} ({{ $pedido->user->email }})
                        </small>
                    </div>
                </div>

                <form action="{{ route('admin.pedidos.updateEstado', $pedido) }}" method="POST" class="orders-status-form">
                    @csrf
                    @method('PUT')
                    <label>Estado:</label>
                    <select name="estado" class="form-control">
                        @foreach ($estados as $key => $label)
                            <option value="{{ $key }}" {{ $pedido->estado == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    <button class="btn btn-success" type="submit">
                        <i class="fas fa-save mr-1"></i>Actualizar
                    </button>
                </form>
            </div>

            <div class="orders-card__body">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show orders-alert" role="alert">
                        <i class="fas fa-check-circle mr-1"></i>{{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                    </div>
                @endif

                <div class="orders-table-heading">
                    <h3>
                        <i class="fas fa-box-open"></i>
                        Productos del pedido
                    </h3>
                    <span>
                        <span class="orders-status orders-status--{{ $color }}">
                            {{ str_replace('_', ' ', $estadoNorm) }}
                        </span>
                    </span>
                </div>

                <div class="alert alert-light border mb-4">
                    <strong><i class="fas fa-map-marker-alt mr-1"></i>Destino solicitado:</strong>
                    <div class="mt-1">{{ $pedido->destino_entrega ?: 'No especificado' }}</div>
                    <div class="mt-2">
                        <strong><i class="fas fa-phone-alt mr-1"></i>Telefono de contacto:</strong>
                        @if ($pedido->telefono_contacto)
                            <a href="tel:{{ $pedido->telefono_contacto }}">{{ $pedido->telefono_contacto }}</a>
                        @else
                            No especificado
                        @endif
                    </div>
                    @if ($pedido->destino_latitud && $pedido->destino_longitud)
                        <div id="pedido-destino-map" class="mt-3"
                            style="height: 320px; width: 100%; border-radius: 8px; overflow: hidden;"></div>
                        <a class="btn btn-sm btn-outline-success mt-2" target="_blank"
                            href="https://www.openstreetmap.org/?mlat={{ $pedido->destino_latitud }}&mlon={{ $pedido->destino_longitud }}#map=16/{{ $pedido->destino_latitud }}/{{ $pedido->destino_longitud }}">
                            <i class="fas fa-external-link-alt mr-1"></i>Abrir mapa
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
                                    $cantidadTexto = $detalle->cantidad_tiempo_texto;
                                    $precioLabel = $detalle->precio_corto_label;
                                @endphp
                                <tr>
                                    <td>
                                        <span class="orders-table__id">{{ $detalle->nombre_producto }}</span>
                                        @if ($detalle->notas)
                                            <br><small class="orders-table__muted">{{ $detalle->notas }}</small>
                                        @endif
                                    </td>
                                    <td>{{ ucfirst($detalle->product_type) }}</td>
                                    <td>{{ $cantidadTexto }}</td>
                                    <td>
                                        Bs {{ number_format($detalle->precio_unitario, 2) }}
                                        <br><small class="orders-table__muted">{{ $precioLabel }}</small>
                                    </td>
                                    <td class="orders-table__total">Bs {{ number_format($detalle->subtotal, 2) }}</td>
                                    <td>
                                        <span class="badge badge-{{ $colorSolicitud }}">{{ $labelSolicitud }}</span>
                                        @if ($detalle->product_type === 'organico' && $detalle->estado_solicitud === 'aceptada')
                                            @php
                                                $adminDeliveryFlow = ['aceptado', 'preparando', 'en_camino_entrega', 'esperando_confirmacion', 'entregado'];
                                                $adminDeliveryCurrent = $detalle->estado_transporte_actual === 'asignado'
                                                    ? 'aceptado'
                                                    : $detalle->estado_transporte_actual;
                                                $adminDeliveryIndex = array_search($adminDeliveryCurrent, $adminDeliveryFlow, true);
                                            @endphp
                                            <div class="mt-2">
                                                <span class="badge badge-info" id="admin-delivery-badge-{{ $detalle->id }}">
                                                    <i class="fas fa-truck mr-1"></i>
                                                    {{ $detalle->estado_transporte_label }}
                                                </span>
                                            </div>
                                            <div class="admin-delivery-tracking"
                                                data-admin-delivery
                                                data-detail-id="{{ $detalle->id }}"
                                                data-tracking-url="{{ route('pedidos.detalles.tracking.latest', $detalle, false) }}">
                                                <div class="admin-delivery-tracking__header">
                                                    <span>Seguimiento de entrega</span>
                                                    <span class="text-success"
                                                        id="admin-delivery-status-{{ $detalle->id }}">{{ $detalle->estado_transporte_label }}</span>
                                                </div>
                                                <div class="admin-delivery-tracking__steps">
                                                    @foreach ($adminDeliveryFlow as $index => $state)
                                                        @php
                                                            $stepClass = $adminDeliveryIndex !== false && $index < $adminDeliveryIndex
                                                                ? 'is-done'
                                                                : ($state === $adminDeliveryCurrent ? 'is-current' : '');
                                                        @endphp
                                                        <div class="admin-delivery-tracking__step {{ $stepClass }}"
                                                            data-state="{{ $state }}">
                                                            {{ \App\Services\TransporteAccesoService::ESTADOS_ORGANICO[$state] }}
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            <small class="d-block mt-2 text-muted">
                                                Acceso externo:
                                                {{ $detalle->transporteAcceso?->estaActivo() ? 'activo' : 'inactivo' }}
                                            </small>
                                            @if($detalle->estado_transporte_actual === 'cancelado')
                                                <div class="alert alert-warning mt-2 mb-0 py-2">
                                                    <strong>Motivo:</strong>
                                                    {{ $detalle->cancelacion_motivo ?: 'No registrado' }}
                                                </div>
                                            @endif
                                            @if($detalle->resenaProducto)
                                                <div class="small mt-2">
                                                    <span class="text-warning">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            <i class="{{ $i <= $detalle->resenaProducto->estrellas ? 'fas' : 'far' }} fa-star"></i>
                                                        @endfor
                                                    </span>
                                                    {{ $detalle->resenaProducto->comentario }}
                                                </div>
                                            @endif
                                            @if($detalle->reclamos->isNotEmpty())
                                                <a href="{{ route('reclamos.index') }}" class="btn btn-sm btn-outline-warning mt-2">
                                                    <i class="fas fa-flag mr-1"></i>{{ $detalle->reclamos->count() }} reclamo(s)
                                                </a>
                                            @endif
                                        @endif
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
                <a href="{{ route('admin.pedidos.index') }}" class="btn orders-back-btn">
                    <i class="fas fa-arrow-left mr-1"></i> Volver a la lista
                </a>
            </div>
        </div>
    </div>

    @if ($pedido->destino_latitud && $pedido->destino_longitud)
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var lat = {{ $pedido->destino_latitud }};
                var lng = {{ $pedido->destino_longitud }};
                var map = L.map('pedido-destino-map').setView([lat, lng], 16);
                var liveMarker = null;
                var trackingUrl = @json(route('pedidos.tracking.latest', $pedido, false));

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(map);

                L.marker([lat, lng]).addTo(map).bindPopup(@json($pedido->destino_entrega)).openPopup();

                var trackingBusy = false;
                var trackingTimer = null;

                function refreshTracking() {
                    if (trackingBusy || document.hidden) {
                        scheduleTracking();
                        return;
                    }

                    trackingBusy = true;
                    fetch(trackingUrl, {
                        headers: { 'Accept': 'application/json' }
                    })
                        .then(function(response) {
                            if (!response.ok) throw new Error('Tracking no disponible');
                            return response.json();
                        })
                        .then(function(data) {
                            if (!data.ubicacion) return;
                            var position = [data.ubicacion.latitud, data.ubicacion.longitud];

                            if (!liveMarker) {
                                liveMarker = L.marker(position).addTo(map);
                            } else {
                                liveMarker.setLatLng(position);
                            }

                            liveMarker.bindPopup(
                                '<strong>Transportista</strong><br>' +
                                (data.ubicacion.producto || 'Envio') + '<br>' +
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var trackers = Array.from(document.querySelectorAll('[data-admin-delivery]'));
            var flow = ['aceptado', 'preparando', 'en_camino_entrega', 'esperando_confirmacion', 'entregado'];
            var busy = false;
            var timer = null;

            if (!trackers.length) return;

            async function refreshAdminDeliveries() {
                if (busy || document.hidden) {
                    scheduleAdminDeliveries();
                    return;
                }

                busy = true;

                for (const tracker of trackers) {
                    try {
                        const response = await fetch(tracker.dataset.trackingUrl, {
                            headers: { 'Accept': 'application/json' }
                        });
                        if (!response.ok) continue;

                        const data = await response.json();
                        const detailId = tracker.dataset.detailId;
                        const currentIndex = flow.indexOf(data.estado);
                        const status = document.getElementById('admin-delivery-status-' + detailId);
                        const badge = document.getElementById('admin-delivery-badge-' + detailId);

                        if (status) status.textContent = data.estado_label;
                        if (badge) {
                            badge.innerHTML = '<i class="fas fa-truck mr-1"></i>' + data.estado_label;
                        }

                        tracker.querySelectorAll('[data-state]').forEach(function(step) {
                            const index = flow.indexOf(step.dataset.state);
                            step.classList.toggle('is-done', currentIndex >= 0 && index < currentIndex);
                            step.classList.toggle('is-current', step.dataset.state === data.estado);
                        });
                    } catch {
                        // Keep the last known state if a refresh fails.
                    }
                }

                busy = false;
                scheduleAdminDeliveries();
            }

            function scheduleAdminDeliveries() {
                clearTimeout(timer);
                timer = setTimeout(refreshAdminDeliveries, 12000);
            }

            document.addEventListener('visibilitychange', function() {
                if (!document.hidden) {
                    clearTimeout(timer);
                    refreshAdminDeliveries();
                }
            });

            refreshAdminDeliveries();
        });
    </script>
@endsection
