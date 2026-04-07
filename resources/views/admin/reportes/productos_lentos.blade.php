@extends('layouts.adminlte')

@section('title', 'Reporte de Productos con Bajo Movimiento')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <h4 class="mb-0">
                <i class="fas fa-box-open"></i> Reporte de Productos con Bajo Movimiento
            </h4>
            <p class="text-muted">
                Identificación de productos con pocas o ninguna venta en el periodo seleccionado.
            </p>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">

            {{-- Filtros --}}
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-filter"></i> Filtros de Búsqueda</h3>
                </div>
                <div class="card-body">
                    <form method="get">
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
                                <label>Categoría</label>
                                <select name="categoria" class="form-control">
                                    <option value="todas" {{ $categoria == 'todas' ? 'selected' : '' }}>Todas</option>
                                    <option value="Animales" {{ $categoria == 'Animales' ? 'selected' : '' }}>Animales
                                    </option>
                                    <option value="Maquinaria" {{ $categoria == 'Maquinaria' ? 'selected' : '' }}>Maquinaria
                                    </option>
                                    <option value="Orgánicos" {{ $categoria == 'Orgánicos' ? 'selected' : '' }}>Orgánicos
                                    </option>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Máx. ventas para considerar lento</label>
                                <input type="number" min="0" name="min_ventas" value="{{ $minVentas }}"
                                    class="form-control">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-search"></i> Aplicar Filtros
                        </button>
                        <a href="{{ route('admin.productos_lentos') }}" class="btn btn-secondary">
                            <i class="fas fa-eraser"></i> Limpiar
                        </a>

                        <div class="float-right">
                            <a href="{{ route('admin.productos_lentos.export', ['tipo' => 'pdf'] + request()->all()) }}"
                                class="btn btn-danger">
                                <i class="far fa-file-pdf"></i> Exportar PDF
                            </a>
                            <a href="{{ route('admin.productos_lentos.export', ['tipo' => 'excel'] + request()->all()) }}"
                                class="btn btn-success">
                                <i class="far fa-file-excel"></i> Exportar Excel
                            </a>

                        </div>
                    </form>
                </div>
            </div>

            {{-- Tarjetas KPI --}}
            <div class="row">
                <div class="col-md-4">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $totalProductos }}</h3>
                            <p>Productos con bajo movimiento</p>
                        </div>
                        <div class="icon"><i class="fas fa-boxes"></i></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>{{ $sinVentas }}</h3>
                            <p>Sin ninguna venta</p>
                        </div>
                        <div class="icon"><i class="fas fa-times-circle"></i></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $conPocasVentas }}</h3>
                            <p>Con pocas ventas (1 a {{ $minVentas }})</p>
                        </div>
                        <div class="icon"><i class="fas fa-exclamation-triangle"></i></div>
                    </div>
                </div>
            </div>

            {{-- Tabla de detalle --}}
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h3 class="card-title">
                        <i class="fas fa-list"></i> Detalle de productos con bajo movimiento
                    </h3>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Categoría</th>
                                <th>Producto</th>
                                <th>Vendedor</th>
                                <th>Precio</th>
                                <th>Total vendido</th>
                                <th>Ingresos</th>
                                <th>Última venta</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($productos as $p)
                                <tr>
                                    <td>{{ $p->categoria }}</td>
                                    <td>{{ $p->producto }}</td>
                                    <td>{{ $p->vendedor }}</td>
                                    <td>Bs. {{ number_format($p->precio, 2) }}</td>
                                    <td>{{ $p->total_vendido }}</td>
                                    <td>Bs. {{ number_format($p->ingresos, 2) }}</td>
                                    <td>{{ $p->ultima_venta ? \Carbon\Carbon::parse($p->ultima_venta)->format('d/m/Y') : 'Sin ventas' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">
                                        No se encontraron productos con bajo movimiento para los filtros seleccionados.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </section>
@endsection
