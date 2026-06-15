@extends('layouts.adminlte')

@section('title', $modoHistorial ? 'Historial de envíos' : 'Mis envíos')
@section('page_title', $modoHistorial ? 'Historial de envíos' : 'Mis envíos')

@section('content')
    <style>
        .driver-envios-mobile {
            display: none;
        }

        .driver-envio-card {
            border: 1px solid #e4eadf;
            border-radius: 10px;
            background: #fff;
            box-shadow: 0 10px 24px rgba(31, 42, 27, .06);
        }

        .driver-envio-card + .driver-envio-card {
            margin-top: .85rem;
        }

        .driver-envio-card__body {
            padding: .9rem;
        }

        .driver-envio-card__title {
            margin: 0;
            color: #1f2a1b;
            font-size: 1rem;
            font-weight: 800;
        }

        .driver-envio-card__meta {
            display: grid;
            grid-template-columns: 1fr;
            gap: .45rem;
            margin-top: .75rem;
            color: #53605a;
            font-size: .88rem;
        }

        .driver-envio-card__actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: .55rem;
            padding: .75rem .9rem .9rem;
            border-top: 1px solid #eef2ea;
        }

        .driver-envio-card__actions--single {
            grid-template-columns: 1fr;
        }

        @media (max-width: 767.98px) {
            .content-wrapper .content {
                padding: 0 .25rem;
            }

            .driver-envios-table {
                display: none;
            }

            .driver-envios-mobile {
                display: block;
                padding: .9rem;
                background: #f5f8f2;
            }

            .driver-envios-filter {
                width: 100%;
            }

            .driver-envios-filter .form-control,
            .driver-envios-filter .btn {
                width: 100%;
                margin-right: 0 !important;
                margin-bottom: .5rem;
            }
        }
    </style>

    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <div class="d-flex flex-wrap justify-content-between align-items-center">
                    <h3 class="card-title mb-2 mb-md-0">
                        <i class="fas {{ $modoHistorial ? 'fa-history' : 'fa-truck' }} mr-1"></i>
                        {{ $modoHistorial ? 'Historial de envíos' : 'Envíos asignados' }}
                    </h3>

                    <div class="btn-group btn-group-sm mb-2 mb-md-0 mr-md-2" role="group">
                        <a href="{{ route('transportista.envios.index') }}"
                            class="btn {{ $modoHistorial ? 'btn-outline-success' : 'btn-success' }}">
                            <i class="fas fa-list mr-1"></i>Activos
                        </a>
                        <a href="{{ route('transportista.envios.historial') }}"
                            class="btn {{ $modoHistorial ? 'btn-success' : 'btn-outline-success' }}">
                            <i class="fas fa-history mr-1"></i>Historial
                        </a>
                    </div>

                    <form method="GET" action="{{ $modoHistorial ? route('transportista.envios.historial') : route('transportista.envios.index') }}" class="form-inline driver-envios-filter">
                        <input type="text" name="q" class="form-control form-control-sm mr-2 mb-2 mb-md-0"
                            placeholder="Producto o comprador" value="{{ request('q') }}">
                        <select name="estado" class="form-control form-control-sm mr-2 mb-2 mb-md-0">
                            <option value="">Todos los estados</option>
                            @foreach ($estados as $key => $label)
                                <option value="{{ $key }}" {{ request('estado') === $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-success btn-sm mb-2 mb-md-0">
                            <i class="fas fa-search mr-1"></i>Filtrar
                        </button>
                    </form>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive driver-envios-table">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Comprador</th>
                                <th>Vendedor</th>
                                <th>Destino</th>
                                <th>Estado</th>
                                <th class="text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($envios as $envio)
                                <tr>
                                    <td>
                                        <strong>{{ $envio->nombre_producto }}</strong><br>
                                        <small class="text-muted">{{ ucfirst($envio->product_type) }}</small>
                                    </td>
                                    <td>
                                        {{ $envio->pedido->user->name ?? 'Comprador no disponible' }}<br>
                                        @if ($envio->pedido->telefono_contacto)
                                            <small><i class="fas fa-phone-alt mr-1"></i>{{ $envio->pedido->telefono_contacto }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $envio->vendedor->name ?? 'No disponible' }}<br>
                                        <small class="text-muted">{{ $envio->vendedor->email ?? '' }}</small>
                                    </td>
                                    <td>
                                        <small>{{ \Illuminate\Support\Str::limit($envio->pedido->destino_entrega, 45) }}</small>
                                    </td>
                                    <td>
                                        <span class="badge badge-primary">{{ $envio->estado_transporte_label }}</span>
                                    </td>
                                    <td class="text-right">
                                        <a href="{{ route('transportista.envios.show', $envio) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye mr-1"></i>Ver
                                        </a>
                                        @unless ($modoHistorial)
                                            <a href="{{ route('transportista.envios.tracking', $envio) }}"
                                                class="btn btn-sm btn-success">
                                                <i class="fas fa-location-arrow mr-1"></i>GPS
                                            </a>
                                        @endunless
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        {{ $modoHistorial ? 'Todavía no tienes envíos terminados.' : 'No tienes envíos activos asignados.' }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="driver-envios-mobile">
                    @forelse ($envios as $envio)
                        <article class="driver-envio-card">
                            <div class="driver-envio-card__body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h4 class="driver-envio-card__title">{{ $envio->nombre_producto }}</h4>
                                        <small class="text-muted">{{ ucfirst($envio->product_type) }}</small>
                                    </div>
                                    <span class="badge badge-primary">{{ $envio->estado_transporte_label }}</span>
                                </div>
                                <div class="driver-envio-card__meta">
                                    <span>
                                        <i class="fas fa-user mr-1"></i>
                                        {{ $envio->pedido->user->name ?? 'Comprador no disponible' }}
                                    </span>
                                    @if ($envio->pedido->telefono_contacto)
                                        <a href="tel:{{ $envio->pedido->telefono_contacto }}">
                                            <i class="fas fa-phone-alt mr-1"></i>{{ $envio->pedido->telefono_contacto }}
                                        </a>
                                    @endif
                                    <span>
                                        <i class="fas fa-map-marker-alt mr-1"></i>
                                        {{ \Illuminate\Support\Str::limit($envio->pedido->destino_entrega, 72) }}
                                    </span>
                                </div>
                            </div>
                            <div class="driver-envio-card__actions {{ $modoHistorial ? 'driver-envio-card__actions--single' : '' }}">
                                <a href="{{ route('transportista.envios.show', $envio) }}"
                                    class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-eye mr-1"></i>Ver
                                </a>
                                @unless ($modoHistorial)
                                    <a href="{{ route('transportista.envios.tracking', $envio) }}"
                                        class="btn btn-success btn-sm">
                                        <i class="fas fa-location-arrow mr-1"></i>GPS
                                    </a>
                                @endunless
                            </div>
                        </article>
                    @empty
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-info-circle mr-1"></i>
                            {{ $modoHistorial ? 'Todavía no tienes envíos terminados.' : 'No tienes envíos activos asignados.' }}
                        </div>
                    @endforelse
                </div>
            </div>

            @if ($envios->hasPages())
                <div class="card-footer">
                    {{ $envios->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
