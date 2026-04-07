@extends('layouts.adminlte')

@section('title', 'Reporte de Rendimiento de Vendedores')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1 text-dark">
                    <i class="fas fa-users text-primary"></i> Reporte de Rendimiento de Vendedores
                </h1>
                <p class="text-muted mb-0">
                    Análisis del desempeño de vendedores: productos publicados, ventas y tasa de conversión.
                </p>
            </div>
            <div>
                <a href="{{ route('admin.reportes.vendedores.export.pdf', request()->all()) }}"
                    class="btn btn-outline-success btn-sm">
                    Exportar PDF
                </a>


                <a href="{{ route('admin.reportes.vendedores.excel', request()->all()) }}" class="btn btn-info btn-sm">
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
                <form method="GET" action="{{ route('admin.reportes.vendedores') }}">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="font-weight-bold">Desde</label>
                            <input type="date" name="desde" value="{{ $desde->format('Y-m-d') }}" class="form-control">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="font-weight-bold">Hasta</label>
                            <input type="date" name="hasta" value="{{ $hasta->format('Y-m-d') }}" class="form-control">
                        </div>
                        <div class="col-md-3 mb-3">
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
                        <div class="col-md-3 mb-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary mr-2">
                                <i class="fas fa-search mr-1"></i> Aplicar Filtros
                            </button>
                            <a href="{{ route('admin.reportes.vendedores') }}" class="btn btn-secondary">
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
                            <i class="fas fa-users text-primary mr-1"></i> Total Vendedores
                        </h6>
                        <h2 class="mb-0">{{ count($rendimientoVendedores) }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-left-info">
                    <div class="card-body">
                        <h6 class="text-muted mb-1">
                            <i class="fas fa-box text-info mr-1"></i> Total Productos
                        </h6>
                        <h2 class="mb-0">{{ array_sum(array_column($rendimientoVendedores, 'total_productos')) }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-left-warning">
                    <div class="card-body">
                        <h6 class="text-muted mb-1">
                            <i class="fas fa-chart-line text-warning mr-1"></i> Ventas Totales
                        </h6>
                        <h2 class="mb-0">Bs.
                            {{ number_format(array_sum(array_column($rendimientoVendedores, 'total_ventas')), 2) }}</h2>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabla de Rendimiento --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-trophy mr-2"></i>Rendimiento de Vendedores</h5>
                    </div>
                    <div class="card-body">
                        @if (count($rendimientoVendedores) > 0)
                            <div class="table-responsive">
                                <table class="table table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Vendedor</th>
                                            <th>Email</th>
                                            <th class="text-right">Total Productos</th>
                                            <th class="text-right">Animales</th>
                                            <th class="text-right">Maquinaria</th>
                                            <th class="text-right">Orgánicos</th>
                                            <th class="text-right">Ventas Totales</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($rendimientoVendedores as $index => $vendedor)
                                            <tr>
                                                <td><strong>{{ $index + 1 }}</strong></td>
                                                <td>{{ $vendedor['nombre'] }}</td>
                                                <td><small class="text-muted">{{ $vendedor['email'] }}</small></td>
                                                <td class="text-right">{{ $vendedor['total_productos'] }}</td>
                                                <td class="text-right">{{ $vendedor['total_ganados'] }}</td>
                                                <td class="text-right">{{ $vendedor['total_maquinarias'] }}</td>
                                                <td class="text-right">{{ $vendedor['total_organicos'] }}</td>
                                                <td class="text-right">
                                                    <strong>Bs. {{ number_format($vendedor['total_ventas'], 2) }}</strong>
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
    </div>
@endsection
