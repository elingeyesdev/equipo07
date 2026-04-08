@extends('layouts.adminlte')

@section('title', 'Mis Pedidos')
@section('page_title', 'Mis Pedidos')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h3 class="card-title mb-0">
                    <i class="fas fa-receipt mr-2"></i> Mis Pedidos
                </h3>
            </div>

            <div class="card-body">

                {{-- =========================
                FILTROS
            ========================== --}}
                <form method="GET" action="{{ route('pedidos.index') }}" class="mb-3">
                    <div class="row">
                        {{-- Buscar por # Pedido --}}
                        <div class="col-12 col-md-3 mb-2">
                            <input type="text" name="pedido_id" class="form-control" placeholder="Buscar por # pedido"
                                value="{{ request('pedido_id') }}">
                        </div>

                        {{-- Estado --}}
                        <div class="col-12 col-md-3 mb-2">
                            <select name="estado" class="form-control">
                                <option value=""> Todos </option>
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

                        {{-- Desde --}}
                        <div class="col-12 col-md-2 mb-2">
                            <input type="date" name="desde" class="form-control" value="{{ request('desde') }}">
                        </div>

                        {{-- Hasta --}}
                        <div class="col-12 col-md-2 mb-2">
                            <input type="date" name="hasta" class="form-control" value="{{ request('hasta') }}">
                        </div>

                        {{-- Botones --}}
                        <div class="col-12 col-md-2 mb-2 d-flex">
                            <button class="btn btn-success mr-2 flex-fill" type="submit">
                                <i class="fas fa-filter mr-1"></i> Filtrar
                            </button>

                            <a href="{{ route('pedidos.index') }}" class="btn btn-secondary">
                                <i class="fas fa-sync mr-1"></i> Limpiar
                            </a>
                        </div>
                    </div>
                </form>

                {{-- =========================
                TABLA
            ========================== --}}
                @if ($pedidos->count())
                    <div class="table-responsive">
                        <table class="table table-hover mb-2">
                            <thead class="thead-light">
                                <tr>
                                    <th style="width: 80px;">#</th>
                                    <th>Fecha</th>
                                    <th>Total</th>
                                    <th>Estado</th>
                                    <th style="width: 120px;"></th>
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
                                        <td>{{ $pedido->id }}</td>
                                        <td>{{ $pedido->created_at->format('d/m/Y H:i') }}</td>
                                        <td>Bs {{ number_format($pedido->total, 2) }}</td>

                                        <td>
                                            <span class="badge badge-{{ $color }} text-uppercase">
                                                {{ str_replace('_', ' ', $estado) }}
                                            </span>
                                        </td>

                                        <td class="text-right">
                                            <a href="{{ route('pedidos.show', $pedido) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye mr-1"></i> Ver
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Mantener filtros al paginar --}}
                    <div class="d-flex justify-content-end">
                        {{ $pedidos->appends(request()->query())->links() }}
                    </div>
                @else
                    <div class="alert alert-light mb-0">
                        <i class="fas fa-info-circle mr-1"></i>
                        Aún no tienes pedidos.
                    </div>
                @endif

            </div>
        </div>
    </div>
@endsection
