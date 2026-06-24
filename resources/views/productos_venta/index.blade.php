@extends('layouts.adminlte')

@section('title', $esAdmin ? 'Productos en venta' : 'Mis productos')
@section('page_title', $esAdmin ? 'Productos en venta' : 'Mis productos')

@section('content')
    @php
        $deleteMessage = $esAdmin
            ? '¿Eliminar esta publicación del mercado?'
            : '¿Eliminar tu publicación del mercado?';
    @endphp

    <style>
        .product-type-filters {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: flex-end;
            gap: .4rem;
        }

        .product-type-filter {
            position: relative;
            margin: 0;
        }

        .product-type-filter input {
            position: absolute;
            width: 1px;
            height: 1px;
            opacity: 0;
            pointer-events: none;
        }

        .product-type-filter span {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            min-height: 36px;
            padding: .45rem .8rem;
            border: 1px solid var(--agro);
            border-radius: .65rem;
            background: #fff;
            color: var(--agro-700);
            cursor: pointer;
            font-weight: 650;
            transition: background-color .18s ease, color .18s ease, box-shadow .18s ease, transform .18s ease;
        }

        .product-type-filter span::before {
            content: '';
            width: .7rem;
            height: .7rem;
            border: 2px solid currentColor;
            border-radius: .2rem;
            background: transparent;
        }

        .product-type-filter input:checked + span {
            background: var(--agro);
            color: #fff;
            box-shadow: 0 7px 16px rgba(46, 171, 91, .2);
        }

        .product-type-filter input:checked + span::before {
            border-color: #fff;
            background: #fff;
            box-shadow: inset 0 0 0 2px var(--agro);
        }

        .product-type-filter:hover span {
            transform: translateY(-1px);
        }

        .product-type-filter input:focus-visible + span {
            box-shadow: 0 0 0 .2rem rgba(46, 171, 91, .2);
        }

        .product-type-filter__hint {
            width: 100%;
            color: var(--app-muted);
            font-size: .72rem;
            text-align: right;
        }

        @media (max-width: 767.98px) {
            .product-type-filters {
                justify-content: flex-start;
                margin-top: .75rem;
            }

            .product-type-filter__hint {
                text-align: left;
            }
        }
    </style>

    <div class="container-fluid">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex flex-wrap align-items-center justify-content-between">
                <div>
                    <h3 class="card-title font-weight-bold mb-1">
                        <i class="fas fa-store text-success mr-2"></i>
                        {{ $esAdmin ? 'Panel rapido de publicaciones' : 'Productos publicados' }}
                    </h3>
                    <div class="text-muted small">
                        {{ $productos->total() }} productos encontrados
                        @if($esAdmin)
                            para revisar o eliminar
                        @endif
                    </div>
                </div>
                <div class="product-type-filters">
                    @foreach([
                        'ganado' => ['Ganado', 'fa-horse'],
                        'maquinaria' => ['Maquinaria', 'fa-tractor'],
                        'organico' => ['Orgánico', 'fa-leaf'],
                    ] as $typeValue => [$typeLabel, $typeIcon])
                        <label class="product-type-filter">
                            <input type="checkbox" name="tipos[]" value="{{ $typeValue }}"
                                form="product-filter-form" @checked(in_array($typeValue, $tipos, true))
                                onchange="document.getElementById('product-filter-form').requestSubmit()">
                            <span><i class="fas {{ $typeIcon }}"></i>{{ $typeLabel }}</span>
                        </label>
                    @endforeach
                    <small class="product-type-filter__hint">Sin selección se muestran todos</small>
                </div>
            </div>

            <div class="card-body border-bottom">
                <form id="product-filter-form" method="GET" action="{{ route('productos-venta.index') }}"
                    class="form-row align-items-end">
                    <div class="col-md-8 mb-2">
                        <label for="q" class="small text-muted">Buscar producto</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-white"><i class="fas fa-search"></i></span>
                            </div>
                            <input id="q" name="q" value="{{ $q }}" class="form-control"
                                placeholder="Nombre del producto">
                        </div>
                    </div>
                    <div class="col-md-4 mb-2 d-flex">
                        <button class="btn btn-success flex-fill"><i class="fas fa-filter mr-1"></i>Filtrar</button>
                        @if($q || $tipos !== [])
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
                                    <form action="{{ $producto['delete_url'] }}" method="POST" class="d-inline delete-form"
                                        data-message="{{ $deleteMessage }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                            title="Eliminar publicación">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
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
