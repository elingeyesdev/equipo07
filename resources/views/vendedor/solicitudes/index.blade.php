@extends('layouts.adminlte')

@section('title', 'Solicitudes de mis productos')
@section('page_title', 'Solicitudes de mis productos')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <div class="d-flex flex-wrap justify-content-between align-items-center">
                    <h3 class="card-title mb-2 mb-md-0">
                        <i class="fas fa-clipboard-list mr-1"></i>Solicitudes recibidas
                    </h3>

                    <form method="GET" action="{{ route('vendedor.solicitudes.index') }}" class="form-inline">
                        <input type="text" name="q" class="form-control form-control-sm mr-2 mb-2 mb-md-0"
                            placeholder="Producto o comprador" value="{{ request('q') }}">
                        <select name="estado" class="form-control form-control-sm mr-2 mb-2 mb-md-0">
                            <option value="">Todos los estados</option>
                            @foreach ($estados as $key => $label)
                                @if ($key === 'cancelada_producto_vendido')
                                    @continue
                                @endif
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
                                <th>Cantidad/Tiempo</th>
                                <th>Total</th>
                                <th>Destino</th>
                                <th>Estado</th>
                                <th class="text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($solicitudes as $solicitud)
                                @php
                                    $badge = [
                                        'pendiente' => 'warning',
                                        'aceptada' => 'success',
                                        'rechazada' => 'secondary',
                                        'cancelada_producto_vendido' => 'danger',
                                    ][$solicitud->estado_solicitud] ?? 'secondary';
                                    $cantidadTexto = $solicitud->cantidad_tiempo_texto;
                                @endphp
                                <tr>
                                    <td>
                                        <strong>{{ $solicitud->nombre_producto }}</strong><br>
                                        <small class="text-muted">{{ ucfirst($solicitud->product_type) }}</small>
                                    </td>
                                    <td>
                                        {{ $solicitud->pedido->user->name ?? 'Comprador no disponible' }}<br>
                                        @if ($solicitud->pedido->telefono_contacto)
                                            <small>
                                                <i class="fas fa-phone-alt mr-1"></i>{{ $solicitud->pedido->telefono_contacto }}
                                            </small><br>
                                        @endif
                                        <small class="text-muted">{{ $solicitud->pedido->created_at->format('d/m/Y H:i') }}</small>
                                    </td>
                                    <td>{{ $cantidadTexto }}</td>
                                    <td><strong>Bs {{ number_format($solicitud->subtotal, 2) }}</strong></td>
                                    <td>
                                        <small>{{ \Illuminate\Support\Str::limit($solicitud->pedido->destino_entrega, 45) }}</small>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $badge }}">
                                            {{ $estados[$solicitud->estado_solicitud] ?? ucfirst($solicitud->estado_solicitud) }}
                                        </span>
                                    </td>
                                    <td class="text-right">
                                        <a href="{{ route('vendedor.solicitudes.show', $solicitud) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye mr-1"></i>Ver
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">
                                        <i class="fas fa-info-circle mr-1"></i>No tienes solicitudes para tus productos.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if ($solicitudes->hasPages())
                <div class="card-footer">
                    {{ $solicitudes->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
