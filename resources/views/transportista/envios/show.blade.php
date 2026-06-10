@extends('layouts.adminlte')

@section('title', 'Envío #' . $envio->id)
@section('page_title', 'Envío #' . $envio->id)

@section('content')
    <style>
        .driver-detail-list dt {
            color: #6c757d;
            font-size: .78rem;
            text-transform: uppercase;
        }

        .driver-detail-list dd {
            color: #1f2a1b;
            font-weight: 600;
        }

        @media (max-width: 767.98px) {
            .driver-detail-actions {
                display: grid;
                grid-template-columns: 1fr;
                gap: .55rem;
            }

            .driver-detail-actions .btn {
                width: 100%;
                padding: .78rem 1rem;
                font-weight: 800;
            }

            .driver-detail-list dt,
            .driver-detail-list dd {
                flex: 0 0 100%;
                max-width: 100%;
            }

            .driver-detail-list dd {
                margin-bottom: .85rem;
                padding-bottom: .75rem;
                border-bottom: 1px solid #edf2ea;
            }
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
                            <i class="fas fa-box-open mr-1"></i>{{ $envio->nombre_producto }}
                        </h3>
                    </div>
                    <div class="card-body">
                        <dl class="row mb-0 driver-detail-list">
                            <dt class="col-sm-5">Estado transporte</dt>
                            <dd class="col-sm-7">
                                <span class="badge badge-primary" data-driver-state-label>{{ $envio->estado_transporte_label }}</span>
                                <div class="small text-success mt-2" data-driver-recepcion
                                    style="{{ $envio->recepcion_confirmada_at ? '' : 'display: none;' }}">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Recepcion confirmada
                                    <span data-driver-recepcion-date>
                                        {{ $envio->recepcion_confirmada_at ? 'el ' . $envio->recepcion_confirmada_at->format('d/m/Y H:i') : '' }}
                                    </span>
                                </div>
                            </dd>

                            <dt class="col-sm-5">Comprador</dt>
                            <dd class="col-sm-7">
                                {{ $envio->pedido->user->name ?? 'No disponible' }}<br>
                                <small class="text-muted">{{ $envio->pedido->user->email ?? '' }}</small>
                            </dd>

                            <dt class="col-sm-5">Teléfono</dt>
                            <dd class="col-sm-7">
                                @if ($envio->pedido->telefono_contacto)
                                    <a href="tel:{{ $envio->pedido->telefono_contacto }}">{{ $envio->pedido->telefono_contacto }}</a>
                                @else
                                    No especificado
                                @endif
                            </dd>

                            <dt class="col-sm-5">Vendedor</dt>
                            <dd class="col-sm-7">
                                {{ $envio->vendedor->name ?? 'No disponible' }}<br>
                                <small class="text-muted">{{ $envio->vendedor->email ?? '' }}</small>
                            </dd>

                            <dt class="col-sm-5">Cantidad/Tiempo</dt>
                            <dd class="col-sm-7">{{ $envio->cantidad_tiempo_texto }}</dd>
                        </dl>
                    </div>
                    <div class="card-footer driver-detail-actions">
                        <a href="{{ route('transportista.envios.tracking', $envio) }}" class="btn btn-success">
                            <i class="fas fa-location-arrow mr-1"></i>Abrir GPS
                        </a>
                        <a href="{{ route('transportista.envios.index') }}" class="btn btn-light">
                            <i class="fas fa-arrow-left mr-1"></i>Volver
                        </a>
                    </div>
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
                        <p>{{ $envio->pedido->destino_entrega }}</p>
                        @if ($envio->pedido->destino_latitud && $envio->pedido->destino_longitud)
                            <a class="btn btn-sm btn-outline-success" target="_blank"
                                href="https://www.google.com/maps/search/?api=1&query={{ $envio->pedido->destino_latitud }},{{ $envio->pedido->destino_longitud }}">
                                <i class="fas fa-external-link-alt mr-1"></i>Abrir destino en mapa
                            </a>
                        @else
                            <div class="alert alert-warning mb-0">Este envío no tiene coordenadas de destino.</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var stateUrl = @json(route('pedidos.detalles.estadoTransporte', $envio, false));
            var stateLabel = document.querySelector('[data-driver-state-label]');
            var recepcion = document.querySelector('[data-driver-recepcion]');
            var recepcionDate = document.querySelector('[data-driver-recepcion-date]');

            function refreshDriverState() {
                fetch(stateUrl, {
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                    .then(function(response) {
                        if (!response.ok) {
                            throw new Error('No se pudo consultar el estado.');
                        }

                        return response.json();
                    })
                    .then(function(data) {
                        if (stateLabel) {
                            stateLabel.textContent = data.estado_transporte_label || 'Sin estado';
                        }

                        if (recepcion) {
                            recepcion.style.display = data.recepcion_confirmada_at ? '' : 'none';
                        }

                        if (recepcionDate) {
                            recepcionDate.textContent = data.recepcion_confirmada_at ? 'el ' + data.recepcion_confirmada_at : '';
                        }
                    })
                    .catch(function() {
                        // Si hay un corte momentaneo, el siguiente intento vuelve a sincronizar la vista.
                    });
            }

            refreshDriverState();
            setInterval(refreshDriverState, 5000);
        });
    </script>
@endsection
