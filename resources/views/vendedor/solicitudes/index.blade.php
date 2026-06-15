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
                                <th>Pedido</th>
                                <th>Comprador</th>
                                <th>Total</th>
                                <th>Destino</th>
                                <th>Productos</th>
                                <th>Transporte</th>
                                <th class="text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($solicitudes as $solicitud)
                                @php
                                    $detalles = $solicitud->detallesEnvio;
                                    $pendientes = $detalles->where('estado_solicitud', 'pendiente')->count();
                                    $aceptados = $detalles->where('estado_solicitud', 'aceptada')->count();
                                    $rechazados = $detalles->where('estado_solicitud', 'rechazada')->count();
                                @endphp
                                <tr>
                                    <td>
                                        <strong>#{{ $solicitud->pedido_id }}</strong><br>
                                        <small class="text-muted">{{ $detalles->count() }} producto(s)</small>
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
                                    <td><strong>Bs {{ number_format($detalles->sum('subtotal'), 2) }}</strong></td>
                                    <td>
                                        <small>{{ \Illuminate\Support\Str::limit($solicitud->pedido->destino_entrega, 45) }}</small>
                                    </td>
                                    <td>
                                        @foreach ($detalles as $detalle)
                                            <div class="mb-1">
                                                <strong>{{ $detalle->nombre_producto }}</strong>
                                                <small class="text-muted">· {{ $detalle->cantidad_tiempo_texto }}</small>
                                            </div>
                                        @endforeach
                                        <div class="mt-2">
                                            @if ($pendientes)
                                                <span class="badge badge-warning">{{ $pendientes }} pendiente(s)</span>
                                            @endif
                                            @if ($aceptados)
                                                <span class="badge badge-success">{{ $aceptados }} aceptado(s)</span>
                                            @endif
                                            @if ($rechazados)
                                                <span class="badge badge-secondary">{{ $rechazados }} rechazado(s)</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @if ($aceptados && $solicitud->transporteAcceso?->estaActivo())
                                            <span class="badge badge-success">
                                                <i class="fas fa-qrcode mr-1"></i>QR activo
                                            </span>
                                        @elseif ($aceptados)
                                            <span class="badge badge-warning">Sin QR activo</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        <a href="{{ route('vendedor.solicitudes.show', $solicitud) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye mr-1"></i>Ver pedido
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
