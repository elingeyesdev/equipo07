@extends('layouts.adminlte')

@section('title', 'Reporte de Ventas y Rentabilidad')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1 text-dark">
                    <i class="fas fa-chart-bar text-primary"></i> Reporte de Análisis de Ventas y Rentabilidad
                </h1>
                <p class="text-muted mb-0">
                    Análisis detallado de ventas, ingresos y rendimiento por categoría, vendedor y período.
                </p>
            </div>
            <div>
                <a href="{{ route('admin.reportes.ventas.export.pdf', request()->all()) }}"
                    class="btn btn-outline-success btn-sm">
                    Exportar PDF
                </a>


                <a href="{{ route('admin.reportes.ventas.excel', request()->all()) }}" class="btn btn-info btn-sm">
                    <i class="fas fa-file-excel mr-1"></i> Exportar Excel
                </a>
            </div>
        </div>

        {{-- Filtros --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-filter mr-2"></i>Filtros de Búsqueda</h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('admin.reportes.ventas') }}">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="font-weight-bold">Desde</label>
                            <input type="date" name="desde" value="{{ $desde->format('Y-m-d') }}" class="form-control">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="font-weight-bold">Hasta</label>
                            <input type="date" name="hasta" value="{{ $hasta->format('Y-m-d') }}" class="form-control">
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="font-weight-bold">Categoría</label>
                            <select name="categoria" class="form-control">
                                <option value="">Todas</option>
                                <option value="ganado" {{ $categoria == 'ganado' ? 'selected' : '' }}>Animales</option>
                                <option value="maquinaria" {{ $categoria == 'maquinaria' ? 'selected' : '' }}>Maquinaria
                                </option>
                                <option value="organico" {{ $categoria == 'organico' ? 'selected' : '' }}>Orgánicos
                                </option>
                            </select>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="font-weight-bold">Estado</label>
                            <select name="estado" class="form-control">
                                <option value="">Todos</option>
                                <option value="pendiente" {{ $estado == 'pendiente' ? 'selected' : '' }}>Pendiente
                                </option>
                                <option value="en_proceso" {{ $estado == 'en_proceso' ? 'selected' : '' }}>En Proceso
                                </option>
                                <option value="entregado" {{ $estado == 'entregado' ? 'selected' : '' }}>Entregado
                                </option>
                                <option value="cancelado" {{ $estado == 'cancelado' ? 'selected' : '' }}>Cancelado
                                </option>
                            </select>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="font-weight-bold">Vendedor</label>
                            <select name="vendedor_id" class="form-control">
                                <option value="">Todos</option>
                                @foreach ($vendedores as $vendedor)
                                    <option value="{{ $vendedor->id }}"
                                        {{ $vendedor_id == $vendedor->id ? 'selected' : '' }}>
                                        {{ $vendedor->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search mr-1"></i> Aplicar Filtros
                            </button>
                            <a href="{{ route('admin.reportes.ventas') }}" class="btn btn-secondary">
                                <i class="fas fa-undo mr-1"></i> Limpiar
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Métricas Generales --}}
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card shadow-sm border-left-primary">
                    <div class="card-body">
                        <h6 class="text-muted mb-1">
                            <i class="fas fa-shopping-cart text-primary mr-1"></i> Total de Pedidos
                        </h6>
                        <h2 class="mb-0">{{ number_format($totalPedidos, 0) }}</h2>
                        <small class="text-muted">En el período seleccionado</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-left-success">
                    <div class="card-body">
                        <h6 class="text-muted mb-1">
                            <i class="fas fa-dollar-sign text-success mr-1"></i> Ingresos Totales
                        </h6>
                        <h2 class="mb-0">Bs. {{ number_format($totalIngresos, 2) }}</h2>
                        <small class="text-muted">Suma de todos los pedidos</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-left-warning">
                    <div class="card-body">
                        <h6 class="text-muted mb-1">
                            <i class="fas fa-percentage text-warning mr-1"></i> Tasa de Conversión
                        </h6>
                        <h2 class="mb-0">{{ number_format($tasaConversion, 1) }}%</h2>
                        <small class="text-muted">Pedidos entregados</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Ventas por Categoría --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-chart-pie mr-2"></i>Ventas por Categoría</h5>
                    </div>
                    <div class="card-body">
                        @if ($ventasPorCategoria->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Categoría</th>
                                            <th class="text-right">Pedidos</th>
                                            <th class="text-right">Cantidad</th>
                                            <th class="text-right">Ingresos</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($ventasPorCategoria as $venta)
                                            @php
                                                $categoriaNombre =
                                                    [
                                                        'ganado' => 'Animales',
                                                        'maquinaria' => 'Maquinaria',
                                                        'organico' => 'Orgánicos',
                                                    ][$venta->categoria] ?? ucfirst($venta->categoria);
                                            @endphp
                                            <tr>
                                                <td><strong>{{ $categoriaNombre }}</strong></td>
                                                <td class="text-right">{{ number_format($venta->total_pedidos, 0) }}
                                                </td>
                                                <td class="text-right">{{ number_format($venta->total_cantidad, 0) }}
                                                </td>
                                                <td class="text-right">
                                                    <strong>Bs. {{ number_format($venta->ingresos_totales, 2) }}</strong>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle mr-2"></i>No hay datos de ventas para el período
                                seleccionado.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Top Vendedores --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0"><i class="fas fa-trophy mr-2"></i>Top 10 Vendedores por Volumen de Ventas
                        </h5>
                    </div>
                    <div class="card-body">
                        @if ($topVendedores->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Vendedor</th>
                                            <th>Email</th>
                                            <th class="text-right">Total Pedidos</th>
                                            <th class="text-right">Ingresos Totales</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($topVendedores as $index => $vendedor)
                                            <tr>
                                                <td><strong>{{ $index + 1 }}</strong></td>
                                                <td>{{ $vendedor['nombre'] }}</td>
                                                <td><small class="text-muted">{{ $vendedor['email'] }}</small></td>
                                                <td class="text-right">{{ number_format($vendedor['total_pedidos'], 0) }}
                                                </td>
                                                <td class="text-right">
                                                    <strong>Bs.
                                                        {{ number_format($vendedor['ingresos_totales'], 2) }}</strong>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle mr-2"></i>No hay datos de vendedores para el período
                                seleccionado.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Productos Más Vendidos --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0"><i class="fas fa-star mr-2"></i>Productos Más Vendidos</h5>
                    </div>
                    <div class="card-body">
                        @if ($productosMasVendidos->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Categoría</th>
                                            <th>Producto</th>
                                            <th class="text-right">Cantidad Vendida</th>
                                            <th class="text-right">Ingresos</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($productosMasVendidos as $producto)
                                            @php
                                                $categoriaNombre =
                                                    [
                                                        'ganado' => 'Animales',
                                                        'maquinaria' => 'Maquinaria',
                                                        'organico' => 'Orgánicos',
                                                    ][$producto->product_type] ?? ucfirst($producto->product_type);
                                            @endphp
                                            <tr>
                                                <td>
                                                    <span
                                                        class="badge badge-{{ $producto->product_type == 'ganado' ? 'success' : ($producto->product_type == 'maquinaria' ? 'info' : 'warning') }}">
                                                        {{ $categoriaNombre }}
                                                    </span>
                                                </td>
                                                <td><strong>{{ $producto->nombre_producto }}</strong></td>
                                                <td class="text-right">{{ number_format($producto->total_vendido, 0) }}
                                                </td>
                                                <td class="text-right">
                                                    <strong>Bs. {{ number_format($producto->ingresos, 2) }}</strong>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle mr-2"></i>No hay datos de productos vendidos para el período
                                seleccionado.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
