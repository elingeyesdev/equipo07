@extends('layouts.adminlte')

@section('title', 'Unidades de Orgánico')

@section('content')
<style>
    .organic-units-card {
        border-radius: 15px;
        overflow: hidden;
        border: 0;
        box-shadow: 0 4px 15px rgba(0,0,0,0.06);
    }

    .organic-units-header {
        background: var(--agro);
        color: #fff;
        padding: 1.5rem 1.75rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .organic-units-header h2 {
        font-size: 1.4rem;
        margin: 0;
        display: flex;
        align-items: center;
        gap: .5rem;
    }

    .organic-units-header h2 i {
        font-size: 1.5rem;
    }

    .organic-units-body {
        background: #fff;
        padding: 1.5rem 1.75rem 1.25rem;
    }

    .organic-units-search .input-group {
        border-radius: 999px;
        overflow: hidden;
        box-shadow: 0 2px 6px rgba(0,0,0,0.06);
    }

    .organic-units-search input {
        border: 0;
        box-shadow: none !important;
    }

    .organic-units-search .btn {
        border: 0;
        background: var(--agro);
        color: #fff;
    }

    .organic-units-search .btn:hover {
        background: var(--agro-700);
    }

    .table-organic-units thead th {
        border-top: 0;
        font-size: .9rem;
        text-transform: uppercase;
        letter-spacing: .03em;
        color: #6c757d;
        background-color: #f8f9fa;
    }

    .table-organic-units tbody tr {
        transition: background-color .2s ease, transform .1s ease;
    }

    .table-organic-units tbody tr:hover {
        background-color: #fdfdfd;
        transform: translateY(-1px);
        box-shadow: 0 3px 10px rgba(0,0,0,0.04);
    }

    .table-organic-units td {
        vertical-align: middle;
        font-size: .95rem;
    }

    .btn-action {
        border-radius: 999px;
        padding: .25rem .6rem;
        font-size: .8rem;
    }

    .organic-units-footer {
        background: #fff;
        border-top: 1px solid #e9ecef;
        padding: .75rem 1.75rem;
    }
</style>

<div class="container-fluid">
    <div class="organic-units-card card">

        {{-- HEADER --}}
        <div class="organic-units-header">
            <h2>
                <i class="fas fa-balance-scale"></i>
                Unidades de Orgánico
            </h2>

            <a href="{{ route('admin.unidades_organicos.create') }}" class="btn btn-light btn-sm">
                <i class="fas fa-plus mr-1"></i> Nueva unidad
            </a>
        </div>

        {{-- BODY --}}
        <div class="organic-units-body">
            @if(session('ok'))
                <div class="alert alert-success alert-dismissible fade show mb-3">
                    <i class="fas fa-check-circle"></i> {{ session('ok') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-3">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="row align-items-center mb-3">
                <div class="col-md-6 organic-units-search mb-2 mb-md-0">
                    <form method="GET" action="{{ route('admin.unidades_organicos.index') }}">
                        <div class="input-group">
                            <input
                                type="text"
                                name="q"
                                class="form-control"
                                placeholder="Buscar por nombre o descripción..."
                                value="{{ $q ?? request('q') }}"
                            >
                            <div class="input-group-append">
                                <button class="btn" type="submit">
                                    <i class="fas fa-search mr-1"></i> Buscar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="col-md-6 text-md-right text-muted small">
                    Listado de unidades de orgánico registradas
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-organic-units mb-0">
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
                                    <a href="{{ route('admin.unidades_organicos.edit', $item) }}"
                                       class="btn btn-primary btn-action"
                                       title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.unidades_organicos.destroy', $item) }}"
                                          method="post"
                                          class="d-inline"
                                          onsubmit="return confirm('¿Eliminar esta unidad de orgánico?');">
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
                                    <i class="fas fa-info-circle"></i> No hay unidades de orgánico registradas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- FOOTER / PAGINACIÓN --}}
        <div class="organic-units-footer d-flex justify-content-between align-items-center">
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
