@extends('layouts.adminlte')

@section('title', 'Categorías')

@section('content')
    <style>
        .categories-card {
            border-radius: 15px;
            overflow: hidden;
            border: 0;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.06);
        }

        /* === HEADER con tu verde principal === */
        .categories-header {
            background: var(--agro);
            color: #fff;
            padding: 1.5rem 1.75rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .categories-header h2 {
            font-size: 1.4rem;
            margin: 0;
            display: flex;
            align-items: center;
            gap: .5rem;
        }

        .categories-header h2 i {
            font-size: 1.5rem;
        }

        .categories-body {
            background: #fff;
            padding: 1.5rem 1.75rem 1.25rem;
        }

        .categories-search .input-group {
            border-radius: 999px;
            overflow: hidden;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.06);
        }

        .categories-search input {
            border: 0;
            box-shadow: none !important;
        }

        .categories-search .btn {
            border: 0;
            background: var(--agro);
            color: white;
        }

        .categories-search .btn:hover {
            background: var(--agro-700);
        }

        .table-categories thead th {
            border-top: 0;
            font-size: .9rem;
            text-transform: uppercase;
            letter-spacing: .03em;
            color: #6c757d;
            background-color: #f8f9fa;
        }

        .table-categories tbody tr:hover {
            background-color: #fdfdfd;
            transform: translateY(-1px);
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.04);
        }

        .btn-action {
            border-radius: 999px;
            padding: .25rem .6rem;
            font-size: .8rem;
        }
    </style>

    <div class="container-fluid">
        <div class="categories-card card">

            <!-- Header sin botón Orgánicos -->
            <div class="categories-header">
                <h2>
                    <i class="fas fa-tags"></i>
                    Categorías
                </h2>

                <a href="{{ route('admin.categorias.create') }}" class="btn btn-light btn-sm">
                    <i class="fas fa-plus mr-1"></i> Nueva categoría
                </a>
            </div>

            <!-- Body -->
            <div class="categories-body">
                <div class="row align-items-center mb-3">
                    <div class="col-md-6 categories-search mb-2 mb-md-0">
                        <form action="{{ route('admin.categorias.index') }}" method="GET">
                            <div class="input-group">
                                <input type="text" name="buscar" class="form-control"
                                    placeholder="Buscar por nombre o descripción..." value="{{ request('buscar') }}">
                                <div class="input-group-append">
                                    <button class="btn" type="submit">
                                        <i class="fas fa-search mr-1"></i> Buscar
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="col-md-6 text-md-right text-muted small">
                        Panel de gestión de categorías
                    </div>
                </div>

                <!-- Tabla -->
                <div class="table-responsive">
                    <table class="table table-hover table-categories mb-0">
                        <thead>
                            <tr>
                                <th style="width: 70px;">#</th>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th class="text-right" style="width: 160px;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($categorias as $categoria)
                                <tr>
                                    <td>{{ $categoria->id }}</td>
                                    <td>{{ $categoria->nombre }}</td>
                                    <td>{{ $categoria->descripcion }}</td>
                                    <td class="text-right">
                                        <a href="{{ route('admin.categorias.edit', $categoria) }}"
                                            class="btn btn-primary btn-action">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.categorias.destroy', $categoria) }}" method="POST"
                                            class="d-inline" onsubmit="return confirm('¿Deseas eliminar esta categoría?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-action">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">
                                        No hay categorías registradas aún.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

@endsection
