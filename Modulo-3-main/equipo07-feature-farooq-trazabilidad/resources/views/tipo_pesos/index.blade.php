@extends('layouts.adminlte')

@section('title', 'Tipos de Peso')

@section('content')
<style>
    .weights-card {
        border-radius: 15px;
        overflow: hidden;
        border: 0;
        box-shadow: 0 4px 15px rgba(0,0,0,0.06);
    }

    .weights-header {
        background: var(--agro);
        color: #fff;
        padding: 1.5rem 1.75rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .weights-header h2 {
        font-size: 1.4rem;
        margin: 0;
        display: flex;
        align-items: center;
        gap: .5rem;
    }

    .weights-header h2 i {
        font-size: 1.5rem;
    }

    .weights-body {
        background: #fff;
        padding: 1.5rem 1.75rem 1.25rem;
    }

    .table-weights thead th {
        border-top: 0;
        font-size: .9rem;
        text-transform: uppercase;
        letter-spacing: .03em;
        color: #6c757d;
        background-color: #f8f9fa;
    }

    .table-weights tbody tr {
        transition: background-color .2s ease, transform .1s ease;
    }

    .table-weights tbody tr:hover {
        background-color: #fdfdfd;
        transform: translateY(-1px);
        box-shadow: 0 3px 10px rgba(0,0,0,0.04);
    }

    .table-weights td {
        vertical-align: middle;
        font-size: .95rem;
    }

    .btn-action {
        border-radius: 999px;
        padding: .25rem .6rem;
        font-size: .8rem;
    }
</style>

<div class="container-fluid">
    <div class="weights-card card">

        {{-- HEADER --}}
        <div class="weights-header">
            <h2>
                <i class="fas fa-balance-scale"></i>
                Tipos de Peso
            </h2>

            <a href="{{ route('admin.tipo-pesos.create') }}" class="btn btn-light btn-sm">
                <i class="fas fa-plus-circle mr-1"></i> Nuevo tipo de peso
            </a>
        </div>

        {{-- BODY --}}
        <div class="weights-body">
            @if(session('success'))
                <div class="alert alert-success mb-3">
                    {{ session('success') }}
                </div>
            @endif

            <div class="text-muted small mb-2">
                Lista de unidades de peso utilizadas en el mercado agrícola.
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-weights mb-0">
                    <thead>
                        <tr>
                            <th style="width: 70px;">ID</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th class="text-right" style="width: 150px;">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($items as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->nombre }}</td>
                                <td>{{ $item->descripcion ?? '—' }}</td>
                                <td class="text-right">
                                    <a href="{{ route('admin.tipo-pesos.edit', $item->id) }}"
                                       class="btn btn-warning btn-action"
                                       title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <form action="{{ route('admin.tipo-pesos.destroy', $item->id) }}"
                                          method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('¿Eliminar este tipo de peso?');">
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
                                    No hay tipos de peso registrados.
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
