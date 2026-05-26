@extends('layouts.adminlte')

@section('title', 'Pedido #' . $pedido->id)
@section('page_title', 'Pedido #' . $pedido->id)

@section('content')
    @php
        $estadoNorm = strtolower(str_replace(' ', '_', $pedido->estado));
        $color = match ($estadoNorm) {
            'pendiente' => 'warning',
            'en_proceso' => 'info',
            'entregado', 'completado' => 'success',
            'cancelado' => 'danger',
            default => 'secondary',
        };
    @endphp

    <div class="container-fluid orders-page">
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
                                <th>Cantidad</th>
                                <th>Precio unit.</th>
                                <th>Subtotal</th>
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
                                @endphp
                                <tr>
                                    <td>
                                        <span class="orders-table__id">{{ $detalle->nombre_producto }}</span>
                                        @if ($detalle->notas)
                                            <br><small class="orders-table__muted">{{ $detalle->notas }}</small>
                                        @endif
                                    </td>
                                    <td>{{ ucfirst($detalle->product_type) }}</td>
                                    <td>{{ $detalle->cantidad }}</td>
                                    <td>Bs {{ number_format($detalle->precio_unitario, 2) }}</td>
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

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(map);

                L.marker([lat, lng]).addTo(map).bindPopup(@json($pedido->destino_entrega)).openPopup();
            });
        </script>
    @endif
@endsection
