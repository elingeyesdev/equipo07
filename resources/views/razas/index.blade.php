@extends('layouts.adminlte')

@section('title', 'Razas')

@section('content')
<style>
    .breeds-card {
        border-radius: 15px;
        overflow: hidden;
        border: 0;
        box-shadow: 0 4px 15px rgba(0,0,0,0.06);
    }

    .breeds-header {
        background: var(--agro);
        color: #fff;
        padding: 1.5rem 1.75rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .breeds-header h2 {
        font-size: 1.4rem;
        margin: 0;
        display: flex;
        align-items: center;
        gap: .5rem;
    }

    .breeds-header h2 i {
        font-size: 1.5rem;
    }

    .breeds-body {
        background: #fff;
        padding: 1.5rem 1.75rem 1.25rem;
    }

    .breeds-search .input-group {
        border-radius: 999px;
        overflow: hidden;
        box-shadow: 0 2px 6px rgba(0,0,0,0.06);
    }

    .breeds-search input {
        border: 0;
        box-shadow: none !important;
    }

    .breeds-search .btn {
        border: 0;
        background: var(--agro);
        color: #fff;
    }

    .breeds-search .btn:hover {
        background: var(--agro-700);
    }

    .table-breeds thead th {
        border-top: 0;
        font-size: .9rem;
        text-transform: uppercase;
        letter-spacing: .03em;
        color: #6c757d;
        background-color: #f8f9fa;
    }

    .table-breeds tbody tr {
        transition: background-color .2s ease, transform .1s ease;
    }

    .table-breeds tbody tr:hover {
        background-color: #fdfdfd;
        transform: translateY(-1px);
        box-shadow: 0 3px 10px rgba(0,0,0,0.04);
    }

    .table-breeds td {
        vertical-align: middle;
        font-size: .95rem;
    }

    .btn-action {
        border-radius: 999px;
        padding: .25rem .6rem;
        font-size: .8rem;
    }

    .breeds-footer {
        background: #fff;
        border-top: 1px solid #e9ecef;
        padding: .75rem 1.75rem;
    }
</style>

<div class="container-fluid">
    <div class="breeds-card card">

        {{-- HEADER --}}
        <div class="breeds-header">
            <h2>
                <i class="fas fa-dna"></i>
                Razas
            </h2>

            <a href="{{ route('admin.razas.create') }}" class="btn btn-light btn-sm">
                <i class="fas fa-plus-circle mr-1"></i> Nueva raza
            </a>
        </div>

        {{-- BODY --}}
        <div class="breeds-body">
            @if(session('success'))
                <div class="alert alert-success mb-3">
                    {{ session('success') }}
                </div>
            @endif

            <div class="row align-items-center mb-3">
                <div class="col-md-6 breeds-search mb-2 mb-md-0">
                    <form method="GET" action="{{ route('admin.razas.index') }}">
                        <div class="input-group">
                            <input
                                type="text"
                                name="q"
                                class="form-control"
                                placeholder="Buscar por nombre, tipo de animal o descripción..."
                                value="{{ request('q') }}"
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
                    Listado de razas registradas
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-breeds mb-0">
                    <thead>
                        <tr>
                            <th style="width: 70px;">ID</th>
                            <th>Nombre</th>
                            <th>Tipo de animal</th>
                            <th>Descripción</th>
                            <th class="text-right" style="width: 150px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($razas as $raza)
                            <tr>
                                <td>{{ $raza->id }}</td>
                                <td>{{ $raza->nombre }}</td>
                                <td>{{ $raza->tipoAnimal->nombre ?? '—' }}</td>
                                <td>{{ $raza->descripcion ?? '—' }}</td>
                                <td class="text-right">
                                    <a href="{{ route('admin.razas.edit', $raza->id) }}"
                                       class="btn btn-warning btn-action"
                                       title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.razas.destroy', $raza->id) }}"
                                          method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('¿Eliminar raza?');">
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
                                <td colspan="5" class="text-center text-muted py-4">
                                    No hay razas registradas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- FOOTER / PAGINACIÓN --}}
        <div class="breeds-footer d-flex justify-content-between align-items-center">
            <span class="text-muted small">
                Mostrando {{ $razas->count() }} de {{ $razas->total() }} registros
            </span>
            <div class="mb-0">
                {{ $razas->links() }}
            </div>
        </div>

    </div>
</div>
@endsection
