@extends('layouts.adminlte')

@section('title', 'Reporte de Pedidos por Cliente')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <h4 class="mb-0">
                <i class="fas fa-file-invoice-dollar"></i> Reporte de Pedidos por Cliente
            </h4>
            <p class="text-muted">
                Historial de pedidos realizados por los clientes en el periodo seleccionado (exportable a PDF).
            </p>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-filter"></i> Filtros de Búsqueda</h3>
                </div>

                <div class="card-body">
                    <form method="GET" action="{{ route('admin.reportes.pedidos_clientes') }}">
                        <div class="form-row">

                            <div class="form-group col-md-3">
                                <label>Desde</label>
                                <input type="date" name="desde" value="{{ $desde }}" class="form-control">
                            </div>

                            <div class="form-group col-md-3">
                                <label>Hasta</label>
                                <input type="date" name="hasta" value="{{ $hasta }}" class="form-control">
                            </div>

                            <div class="form-group col-md-3">
                                <label>Estado</label>
                                <select name="estado" class="form-control">
                                    <option value="todos" {{ $estado == 'todos' ? 'selected' : '' }}>Todos</option>
                                    <option value="pendiente" {{ $estado == 'pendiente' ? 'selected' : '' }}>Pendiente
                                    </option>
                                    <option value="en_proceso" {{ $estado == 'en_proceso' ? 'selected' : '' }}>En proceso
                                    </option>
                                    <option value="entregado" {{ $estado == 'entregado' ? 'selected' : '' }}>Entregado
                                    </option>
                                    <option value="cancelado" {{ $estado == 'cancelado' ? 'selected' : '' }}>Cancelado
                                    </option>
                                </select>
                            </div>

                            <div class="form-group col-md-3">
                                <label>Cliente</label>
                                <select name="cliente_id" class="form-control">
                                    <option value="todos" {{ $clienteId == 'todos' ? 'selected' : '' }}>Todos</option>
                                    @foreach ($clientes as $c)
                                        <option value="{{ $c->id }}"
                                            {{ (string) $clienteId === (string) $c->id ? 'selected' : '' }}>
                                            {{ $c->name }} ({{ $c->email }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                        </div>

                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-search"></i> Aplicar Filtros
                        </button>

                        <a href="{{ route('admin.reportes.pedidos_clientes') }}" class="btn btn-secondary">
                            <i class="fas fa-eraser"></i> Limpiar
                        </a>

                        <div class="float-right">
                            <a href="{{ route('admin.reportes.pedidos_clientes.export.pdf', request()->all()) }}"
                                class="btn btn-danger">
                                <i class="far fa-file-pdf"></i> Exportar PDF
                            </a>
                        </div>

                    </form>
                </div>
            </div>

            {{-- KPIs --}}
            <div class="row">
                <div class="col-md-4">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $totalPedidos }}</h3>
                            <p>Total de pedidos</p>
                        </div>
                        <div class="icon"><i class="fas fa-receipt"></i></div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>Bs {{ number_format($montoTotal, 2) }}</h3>
                            <p>Monto total (sumatoria)</p>
                        </div>
                        <div class="icon"><i class="fas fa-coins"></i></div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $clientesUnicos }}</h3>
                            <p>Clientes con pedidos</p>
                        </div>
                        <div class="icon"><i class="fas fa-users"></i></div>
                    </div>
                </div>
            </div>

            {{-- Tabla --}}
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title">
                        <i class="fas fa-list"></i> Detalle de pedidos por cliente
                    </h3>
                </div>

                <div class="card-body table-responsive p-0">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th># Pedido</th>
                                <th>Fecha</th>
                                <th>Cliente</th>
                                <th>Email</th>
                                <th>Estado</th>
                                <th>Total</th>
                                <th>Ver</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($pedidos as $p)
                                @php
                                    $estadoNorm = strtolower(str_replace(' ', '_', $p->estado));
                                    $color = match ($estadoNorm) {
                                        'pendiente' => 'warning',
                                        'en_proceso' => 'info',
                                        'entregado', 'completado' => 'success',
                                        'cancelado' => 'danger',
                                        default => 'secondary',
                                    };
                                @endphp

                                <tr>
                                    <td>{{ $p->id }}</td>
                                    <td>{{ \Carbon\Carbon::parse($p->created_at)->format('d/m/Y H:i') }}</td>
                                    <td>{{ $p->cliente_nombre }}</td>
                                    <td>{{ $p->cliente_email }}</td>
                                    <td>
                                        <span class="badge badge-{{ $color }} text-uppercase">
                                            {{ str_replace('_', ' ', $estadoNorm) }}
                                        </span>
                                    </td>
                                    <td>Bs {{ number_format($p->total, 2) }}</td>
                                    <td>
                                        <a href="{{ route('pedidos.show', $p->id) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye mr-1"></i> Ver
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">
                                        No se encontraron pedidos para los filtros seleccionados.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if (method_exists($pedidos, 'links'))
                    <div class="card-footer">
                        {{ $pedidos->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>

        </div>
    </section>
@endsection
