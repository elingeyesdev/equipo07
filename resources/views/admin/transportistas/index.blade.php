@extends('layouts.adminlte')

@section('title', 'Transportistas')
@section('page_title', 'Transportistas')

@section('content')
    <div class="container-fluid">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle mr-1"></i>{{ session('success') }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle mr-1"></i>{{ session('error') }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <div class="d-flex flex-wrap justify-content-between align-items-center">
                    <h3 class="card-title mb-2 mb-md-0">
                        <i class="fas fa-truck mr-1"></i>Asignar rol transportista
                    </h3>

                    <form method="GET" action="{{ route('admin.transportistas.index') }}" class="form-inline">
                        <input type="text" name="q" class="form-control form-control-sm mr-2 mb-2 mb-md-0"
                            placeholder="Nombre o correo" value="{{ request('q') }}">
                        <select name="rol" class="form-control form-control-sm mr-2 mb-2 mb-md-0">
                            <option value="">Todos los roles</option>
                            <option value="cliente" {{ request('rol') === 'cliente' ? 'selected' : '' }}>Clientes</option>
                            <option value="vendedor" {{ request('rol') === 'vendedor' ? 'selected' : '' }}>Vendedores</option>
                            <option value="transportista" {{ request('rol') === 'transportista' ? 'selected' : '' }}>
                                Transportistas
                            </option>
                            <option value="sin_rol" {{ request('rol') === 'sin_rol' ? 'selected' : '' }}>Sin rol</option>
                        </select>
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
                                <th>Rol actual</th>
                                <th class="text-right">Acciones</th>
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
                                        @if ($usuario->isTransportista())
                                            <span class="badge badge-success">transportista</span>
                                        @else
                                            <span class="badge badge-secondary">{{ $usuario->role_name ?: 'sin rol' }}</span>
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        @if ($usuario->isTransportista())
                                            <form action="{{ route('admin.transportistas.quitar', $usuario) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-secondary"
                                                    onclick="return confirm('¿Quitar rol transportista a {{ $usuario->name }}?')">
                                                    <i class="fas fa-user-minus mr-1"></i>Volver a cliente
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('admin.transportistas.hacer', $usuario) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success"
                                                    onclick="return confirm('¿Dar rol transportista a {{ $usuario->name }}?')">
                                                    <i class="fas fa-truck mr-1"></i>Hacer transportista
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">
                                        <i class="fas fa-info-circle mr-1"></i>No se encontraron usuarios.
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
