@extends('layouts.adminlte')

@section('title', 'Pedidos')
@section('page_title', 'Pedidos')

@section('content')
    <div class="container-fluid orders-page">
        <div class="orders-card">
            <div class="orders-card__body orders-card__body--admin">
                <div class="orders-admin-toolbar">
                    <h3>
                        <i class="fas fa-receipt"></i>
                        Pedidos
                    </h3>

                    <form class="orders-filter orders-filter--admin" method="GET">
                        <div class="orders-filter__field orders-filter__field--search">
                            <i class="fas fa-search"></i>
                            <input type="text" name="q" class="form-control" placeholder="Buscar cliente"
                                value="{{ request('q') }}">
                        </div>

                        <div class="orders-filter__field">
                            <select name="estado" class="form-control">
                                <option value="">Todos los estados</option>
                                @foreach ($estados as $key => $label)
                                    <option value="{{ $key }}" {{ request('estado') == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="orders-filter__field">
                            <input type="date" name="fecha_desde" class="form-control"
                                value="{{ request('fecha_desde') }}">
                        </div>

                        <div class="orders-filter__field">
                            <input type="date" name="fecha_hasta" class="form-control"
                                value="{{ request('fecha_hasta') }}">
                        </div>

                        <div class="orders-filter__actions">
                            <button class="btn btn-success" type="submit" title="Buscar pedidos">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <div class="table-responsive orders-table-wrap">
                    <table class="table table-hover orders-table mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Fecha</th>
                                <th>Cliente</th>
                                <th>Total</th>
                                <th>Estado</th>
                                <th class="text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pedidos as $pedido)
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
                                <tr>
                                    <td class="orders-table__id">{{ $pedido->id }}</td>
                                    <td>{{ $pedido->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        {{ $pedido->user->name }}<br>
                                        <small class="orders-table__muted">{{ $pedido->user->email }}</small>
                                    </td>
                                    <td class="orders-table__total">Bs {{ number_format($pedido->total, 2) }}</td>
                                    <td>
                                        <span class="orders-status orders-status--{{ $color }}">
                                            {{ ucfirst(str_replace('_', ' ', $pedido->estado)) }}
                                        </span>
                                    </td>
                                    <td class="text-right">
                                        <a href="{{ route('admin.pedidos.show', $pedido) }}"
                                            class="btn orders-action-btn">
                                            <i class="fas fa-eye"></i>
                                            <span>Ver</span>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">
                                        <div class="orders-empty orders-empty--table">
                                            <i class="fas fa-info-circle"></i>
                                            <div>
                                                <strong>No hay pedidos registrados.</strong>
                                                <span>Los pedidos aparecerán aquí cuando los clientes compren.</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($pedidos->hasPages())
                    <div class="orders-card__footer">
                        <span>Mostrando {{ $pedidos->count() }} pedidos</span>
                        <div>
                            {{ $pedidos->links() }}
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>
@endsection
