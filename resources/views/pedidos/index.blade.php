@extends('layouts.adminlte')

@section('title', 'Mis Pedidos')
@section('page_title', 'Mis Pedidos')

@section('content')
    <div class="container-fluid orders-page">
        <div class="orders-card">
            <div class="orders-card__header">
                <div>
                    <h2>
                        <i class="fas fa-receipt"></i>
                        Mis Pedidos
                    </h2>
                    <span>Consulta el estado y el detalle de tus compras.</span>
                </div>
            </div>

            <div class="orders-card__body">
                <form method="GET" action="{{ route('pedidos.index') }}" class="orders-filter">
                    <div class="orders-filter__field orders-filter__field--search">
                        <i class="fas fa-search"></i>
                        <input type="text" name="pedido_id" class="form-control" placeholder="Buscar por # pedido"
                            value="{{ request('pedido_id') }}">
                    </div>

                    <div class="orders-filter__field">
                        <select name="estado" class="form-control">
                            <option value="">Todos los estados</option>
                            <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente
                            </option>
                            <option value="en_proceso" {{ request('estado') == 'en_proceso' ? 'selected' : '' }}>En
                                proceso</option>
                            <option value="entregado" {{ request('estado') == 'entregado' ? 'selected' : '' }}>
                                Entregado</option>
                            <option value="cancelado" {{ request('estado') == 'cancelado' ? 'selected' : '' }}>Cancelado
                            </option>
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

                        <a href="{{ route('pedidos.index') }}" class="btn btn-light" title="Limpiar filtros">
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
                                            'entregado' => 'success',
                                            'cancelado' => 'danger',
                                            default => 'secondary',
                                        };
                                    @endphp

                                    <tr>
                                        <td class="orders-table__id">{{ $pedido->id }}</td>
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
                            <span>Cuando realices una compra, aparecerá en este listado.</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
