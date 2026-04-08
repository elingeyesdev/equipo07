@extends('layouts.adminlte')

@section('title', 'Marcas de Maquinaria')

@section('content')
    <style>
        .brands-card {
            border-radius: 15px;
            overflow: hidden;
            border: 0;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.06);
        }

        .brands-header {
            background: var(--agro);
            color: #fff;
            padding: 1.5rem 1.75rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .brands-header h2 {
            font-size: 1.4rem;
            margin: 0;
            display: flex;
            align-items: center;
            gap: .5rem;
        }

        .brands-header h2 i {
            font-size: 1.5rem;
        }

        .brands-body {
            background: #fff;
            padding: 1.5rem 1.75rem 1.25rem;
        }

        .brands-search .input-group {
            border-radius: 999px;
            overflow: hidden;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.06);
        }

        .brands-search input {
            border: 0;
            box-shadow: none !important;
        }

        .brands-search .btn {
            border: 0;
            background: var(--agro);
            color: #fff;
        }

        .brands-search .btn:hover {
            background: var(--agro-700);
        }

        .table-brands thead th {
            border-top: 0;
            font-size: .9rem;
            text-transform: uppercase;
            letter-spacing: .03em;
            color: #6c757d;
            background-color: #f8f9fa;
        }

        .table-brands tbody tr {
            transition: background-color .2s ease, transform .1s ease;
        }

        .table-brands tbody tr:hover {
            background-color: #fdfdfd;
            transform: translateY(-1px);
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.04);
        }

        .table-brands td {
            vertical-align: middle;
            font-size: .95rem;
        }

        .btn-action {
            border-radius: 999px;
            padding: .25rem .6rem;
            font-size: .8rem;
        }

        .brands-footer {
            background: #fff;
            border-top: 1px solid #e9ecef;
            padding: .75rem 1.75rem;
        }
    </style>

    <div class="container-fluid">
        <div class="brands-card card">

            {{-- HEADER --}}
            <div class="brands-header">
                <h2>
                    <i class="fas fa-industry"></i>
                    Marcas de Maquinaria
                </h2>

                <a href="{{ route('admin.marcas_maquinarias.create') }}" class="btn btn-light btn-sm">
                    <i class="fas fa-plus mr-1"></i> Nueva marca
                </a>
            </div>

            {{-- BODY --}}
            <div class="brands-body">
                @if (session('ok'))
                    <div class="alert alert-success alert-dismissible fade show mb-3">
                        <i class="fas fa-check-circle"></i> {{ session('ok') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show mb-3">
                        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <div class="row align-items-center mb-3">
                    <div class="col-md-6 brands-search mb-2 mb-md-0">
                        <form method="GET" action="{{ route('admin.marcas_maquinarias.index') }}">
                            <div class="input-group">
                                <input type="text" name="q" class="form-control"
                                    placeholder="Buscar por nombre o descripción..." value="{{ $q ?? request('q') }}">
                                <div class="input-group-append">
                                    <button class="btn" type="submit">
                                        <i class="fas fa-search mr-1"></i> Buscar
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="col-md-6 text-md-right text-muted small">
                        Listado de marcas de maquinaria registradas
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover table-brands mb-0">
                        <thead>
                            <tr>
                                <th style="width: 70px;">#</th>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th class="text-right" style="width: 170px;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($items as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td><strong>{{ $item->nombre }}</strong></td>
                                    <td>{{ $item->descripcion ?? '—' }}</td>
                                    <td class="text-right">
                                        <a href="{{ route('admin.marcas_maquinarias.edit', $item) }}"
                                            class="btn btn-primary btn-action" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.marcas_maquinarias.destroy', $item) }}" method="post"
                                            class="d-inline"
                                            onsubmit="return confirm('¿Eliminar esta marca de maquinaria?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-action" title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">
                                        <i class="fas fa-info-circle"></i> No hay marcas de maquinaria registradas.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- FOOTER / PAGINACIÓN --}}
            <div class="brands-footer d-flex justify-content-between align-items-center">
                <span class="text-muted small">
                    Mostrando {{ $items->count() }} de {{ $items->total() }} registros
                </span>
                <div class="mb-0">
                    {{ $items->links() }}
                </div>
            </div>

        </div>
    </div>
@endsection
