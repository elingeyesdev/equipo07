@extends('layouts.adminlte')

@section('title', $modoHistorial ? 'Historial de Pedidos' : 'Mis Pedidos')
@section('page_title', $modoHistorial ? 'Historial de Pedidos' : 'Mis Pedidos')

@section('content')
    <div class="container-fluid orders-page">
        <div class="orders-card">
            <div class="orders-card__header">
                <div>
                    <h2>
                        <i class="fas {{ $modoHistorial ? 'fa-history' : 'fa-receipt' }}"></i>
                        {{ $modoHistorial ? 'Historial de Pedidos' : 'Mis Pedidos' }}
                    </h2>
                    <span>{{ $modoHistorial ? 'Revisa los pedidos que ya finalizaron.' : 'Consulta el estado y el detalle de tus compras activas.' }}</span>
                </div>
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('pedidos.index') }}"
                        class="btn {{ $modoHistorial ? 'btn-outline-success' : 'btn-success' }}">
                        <i class="fas fa-list mr-1"></i>Activos
                    </a>
                    <a href="{{ route('pedidos.historial') }}"
                        class="btn {{ $modoHistorial ? 'btn-success' : 'btn-outline-success' }}">
                        <i class="fas fa-history mr-1"></i>Historial
                    </a>
                </div>
            </div>

            <div class="orders-card__body">
                <form method="GET" action="{{ $modoHistorial ? route('pedidos.historial') : route('pedidos.index') }}" class="orders-filter">
                    <div class="orders-filter__field orders-filter__field--search">
                        <i class="fas fa-search"></i>
                        <input type="text" name="pedido_id" class="form-control" placeholder="Buscar por # pedido o producto"
                            value="{{ request('pedido_id') }}">
                    </div>

                    <div class="orders-filter__field">
                        <select name="estado" class="form-control">
                            <option value="">Todos los estados</option>
                            @if ($modoHistorial)
                                <option value="finalizado" {{ request('estado') == 'finalizado' ? 'selected' : '' }}>
                                    Finalizado</option>
                            @else
                                <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente
                                </option>
                                <option value="en_proceso" {{ request('estado') == 'en_proceso' ? 'selected' : '' }}>En
                                    proceso</option>
                                <option value="entregado" {{ request('estado') == 'entregado' ? 'selected' : '' }}>
                                    Entregado</option>
                                <option value="cancelado" {{ request('estado') == 'cancelado' ? 'selected' : '' }}>Cancelado
                                </option>
                            @endif
                        </select>
                    </div>

                    <div class="orders-filter__field">
                        <input type="date" name="desde" class="form-control" value="{{ request('desde') }}">
                    </div>

                    <div class="orders-filter__field">
                        <input type="date" name="hasta" class="form-control" value="{{ request('hasta') }}">
                    </div>

                    <div class="orders-filter__actions">
                        <button class="btn btn-success" type="submit" title="Filtrar pedidos">
                            <i class="fas fa-search"></i>
                            <span>Filtrar</span>
                        </button>

                        <a href="{{ $modoHistorial ? route('pedidos.historial') : route('pedidos.index') }}" class="btn btn-light" title="Limpiar filtros">
                            <i class="fas fa-sync"></i>
                            <span>Limpiar</span>
                        </a>
                    </div>
                </form>

                @if ($pedidos->count())
                    <div class="table-responsive orders-table-wrap">
                        <table class="table table-hover orders-table mb-0">
                            <thead>
                                <tr>
                                    <th style="width: 80px;">#</th>
                                    <th>Productos</th>
                                    <th>Fecha</th>
                                    <th>Total</th>
                                    <th>Estado</th>
                                    <th class="text-right" style="width: 130px;">Acciones</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($pedidos as $pedido)
                                    @php
                                        $estado = strtolower($pedido->estado);

                                        // si en tu BD viene "EN PROCESO" con espacio, lo normalizamos:
                                        $estado = str_replace(' ', '_', $estado);

                                        $color = match ($estado) {
                                            'pendiente' => 'warning',
                                            'en_proceso' => 'info',
                                            'entregado', 'finalizado' => 'success',
                                            'cancelado' => 'danger',
                                            default => 'secondary',
                                        };
                                        $primerDetalle = $pedido->detalles->first();
                                        $productosExtra = max($pedido->detalles->count() - 1, 0);
                                    @endphp

                                    <tr>
                                        <td class="orders-table__id">{{ $pedido->id }}</td>
                                        <td>
                                            @if ($primerDetalle)
                                                <strong>{{ $primerDetalle->nombre_producto }}</strong>
                                                @if ($productosExtra > 0)
                                                    <span class="badge badge-light ml-1">+{{ $productosExtra }}</span>
                                                @endif
                                                <br>
                                                <small class="text-muted">
                                                    {{ ucfirst($primerDetalle->product_type) }}
                                                    @if ($primerDetalle->cantidad_tiempo_texto)
                                                        · {{ $primerDetalle->cantidad_tiempo_texto }}
                                                    @endif
                                                </small>
                                                @if ($productosExtra > 0)
                                                    <div class="small text-muted mt-1">
                                                        {{ $pedido->detalles->skip(1)->take(2)->pluck('nombre_producto')->implode(', ') }}
                                                        @if ($productosExtra > 2)
                                                            ...
                                                        @endif
                                                    </div>
                                                @endif
                                            @else
                                                <span class="text-muted">Sin productos</span>
                                            @endif
                                        </td>
                                        <td>{{ $pedido->created_at->format('d/m/Y H:i') }}</td>
                                        <td class="orders-table__total">Bs {{ number_format($pedido->total, 2) }}</td>

                                        <td>
                                            <span class="orders-status orders-status--{{ $color }}">
                                                {{ str_replace('_', ' ', $estado) }}
                                            </span>
                                        </td>

                                        <td class="text-right">
                                            <a href="{{ route('pedidos.show', $pedido) }}" class="btn orders-action-btn">
                                                <i class="fas fa-eye"></i>
                                                <span>Ver</span>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="orders-card__footer">
                        <span>Mostrando {{ $pedidos->count() }} de {{ $pedidos->total() }} pedidos</span>
                        <div>
                            {{ $pedidos->appends(request()->query())->links() }}
                        </div>
                    </div>
                @else
                    <div class="orders-empty">
                        <i class="fas fa-info-circle"></i>
                        <div>
                            <strong>Aún no tienes pedidos.</strong>
                            <span>{{ $modoHistorial ? 'Cuando un pedido finalice, aparecerá en este historial.' : 'Cuando realices una compra activa, aparecerá en este listado.' }}</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
