@extends('layouts.adminlte')

@section('title', 'Transportistas')
@section('page_title', 'Transportistas')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <div class="d-flex flex-wrap justify-content-between align-items-center">
                    <h3 class="card-title mb-2 mb-md-0">
                        <i class="fas fa-truck mr-1"></i>Transportistas registrados
                    </h3>

                    <form method="GET" action="{{ route('admin.transportistas.index') }}" class="form-inline">
                        <input type="text" name="q" class="form-control form-control-sm mr-2 mb-2 mb-md-0"
                            placeholder="Nombre o correo" value="{{ request('q') }}">
                        <button type="submit" class="btn btn-success btn-sm mb-2 mb-md-0">
                            <i class="fas fa-search mr-1"></i>Filtrar
                        </button>
                    </form>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Usuario</th>
                                <th>Correo</th>
                                <th>Telefono</th>
                                <th>Creado por vendedor</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($usuarios as $usuario)
                                <tr>
                                    <td>
                                        <strong>{{ $usuario->name }}</strong>
                                    </td>
                                    <td>{{ $usuario->email }}</td>
                                    <td>
                                        {{ $usuario->telefono ?: 'Sin telefono' }}
                                    </td>
                                    <td>
                                        @if ($usuario->transportistaCreador)
                                            {{ $usuario->transportistaCreador->name }}<br>
                                            <small class="text-muted">{{ $usuario->transportistaCreador->email }}</small>
                                        @else
                                            <span class="text-muted">No registrado</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">
                                        <i class="fas fa-info-circle mr-1"></i>No se encontraron transportistas.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if ($usuarios->hasPages())
                <div class="card-footer">
                    {{ $usuarios->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
