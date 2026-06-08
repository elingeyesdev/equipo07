@extends('layouts.adminlte')

@section('title', 'Mis envíos')
@section('page_title', 'Mis envíos')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <div class="d-flex flex-wrap justify-content-between align-items-center">
                    <h3 class="card-title mb-2 mb-md-0">
                        <i class="fas fa-truck mr-1"></i>Envíos asignados
                    </h3>

                    <form method="GET" action="{{ route('transportista.envios.index') }}" class="form-inline">
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
                <div class="table-responsive">
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
                                        <a href="{{ route('transportista.envios.tracking', $envio) }}"
                                            class="btn btn-sm btn-success">
                                            <i class="fas fa-location-arrow mr-1"></i>GPS
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        <i class="fas fa-info-circle mr-1"></i>No tienes envíos asignados.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
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
