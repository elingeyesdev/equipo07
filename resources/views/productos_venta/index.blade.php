@extends('layouts.adminlte')

@section('title', $esAdmin ? 'Productos en venta' : 'Mis productos')
@section('page_title', $esAdmin ? 'Productos en venta' : 'Mis productos')

@section('content')
    <div class="container-fluid">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex flex-wrap align-items-center justify-content-between">
                <div>
                    <h3 class="card-title font-weight-bold mb-1">
                        <i class="fas fa-store text-success mr-2"></i>
                        {{ $esAdmin ? 'Catalogo publicado' : 'Productos publicados' }}
                    </h3>
                    <div class="text-muted small">{{ $productos->total() }} productos encontrados</div>
                </div>
                @unless($esAdmin)
                    <div class="btn-group mt-2 mt-md-0">
                        <a href="{{ route('ganados.create') }}" class="btn btn-sm btn-outline-success">Ganado</a>
                        <a href="{{ route('maquinarias.create') }}" class="btn btn-sm btn-outline-success">Maquinaria</a>
                        <a href="{{ route('organicos.create') }}" class="btn btn-sm btn-success">Organico</a>
                    </div>
                @endunless
            </div>

            <div class="card-body border-bottom">
                <form method="GET" action="{{ route('productos-venta.index') }}" class="form-row align-items-end">
                    <div class="col-md-5 mb-2">
                        <label for="q" class="small text-muted">Buscar producto</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-white"><i class="fas fa-search"></i></span>
                            </div>
                            <input id="q" name="q" value="{{ $q }}" class="form-control"
                                placeholder="Nombre del producto">
                        </div>
                    </div>
                    <div class="col-md-4 mb-2">
                        <label for="tipo" class="small text-muted">Categoria</label>
                        <select id="tipo" name="tipo" class="form-control">
                            <option value="">Todas</option>
                            <option value="ganado" @selected($tipo === 'ganado')>Ganado</option>
                            <option value="maquinaria" @selected($tipo === 'maquinaria')>Maquinaria</option>
                            <option value="organico" @selected($tipo === 'organico')>Organicos</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-2 d-flex">
                        <button class="btn btn-success flex-fill"><i class="fas fa-filter mr-1"></i>Filtrar</button>
                        @if($q || $tipo)
                            <a href="{{ route('productos-venta.index') }}" class="btn btn-light ml-2" title="Limpiar filtros">
                                <i class="fas fa-times"></i>
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>Producto</th>
                            <th>Tipo</th>
                            @if($esAdmin)<th>Vendedor</th>@endif
                            <th>Precio</th>
                            <th>Disponibilidad</th>
                            <th class="text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($productos as $producto)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center" style="min-width:220px">
                                        @if($producto['imagen'])
                                            <img src="{{ asset('storage/' . $producto['imagen']) }}"
                                                alt="{{ $producto['nombre'] }}"
                                                class="mr-3 border"
                                                style="width:52px;height:52px;object-fit:cover;border-radius:6px">
                                        @else
                                            <span class="mr-3 bg-light border d-inline-flex align-items-center justify-content-center"
                                                style="width:52px;height:52px;border-radius:6px">
                                                <i class="fas fa-image text-muted"></i>
                                            </span>
                                        @endif
                                        <div>
                                            <strong>{{ $producto['nombre'] }}</strong>
                                            <div class="small text-muted">#{{ $producto['id'] }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="badge badge-light border">{{ $producto['tipo_label'] }}</span></td>
                                @if($esAdmin)<td>{{ $producto['propietario'] ?: 'Sin vendedor' }}</td>@endif
                                <td>
                                    Bs {{ number_format((float) $producto['precio'], 2) }}
                                    @if($producto['tipo'] === 'maquinaria')<small class="text-muted">/dia</small>@endif
                                </td>
                                <td>
                                    <span class="badge badge-{{ $producto['estado_color'] }}">{{ $producto['estado'] }}</span>
                                    @if(!is_null($producto['stock']))
                                        <div class="small text-muted mt-1">{{ $producto['stock'] }} disponibles</div>
                                    @endif
                                </td>
                                <td class="text-right text-nowrap">
                                    <a href="{{ $producto['show_url'] }}" class="btn btn-sm btn-outline-secondary"
                                        title="Ver producto"><i class="fas fa-eye"></i></a>
                                    <a href="{{ $producto['edit_url'] }}" class="btn btn-sm btn-outline-success"
                                        title="Editar producto"><i class="fas fa-edit"></i></a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ $esAdmin ? 6 : 5 }}" class="text-center py-5 text-muted">
                                    <i class="fas fa-box-open fa-2x mb-3 d-block"></i>
                                    No hay productos que coincidan con la busqueda.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($productos->hasPages())
                <div class="card-footer bg-white">{{ $productos->links() }}</div>
            @endif
        </div>
    </div>
@endsection
