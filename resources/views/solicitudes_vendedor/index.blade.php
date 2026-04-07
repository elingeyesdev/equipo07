@extends('layouts.adminlte')

@section('title', 'Solicitudes de Vendedor')

@section('content')
<style>
    .requests-card {
        border-radius: 15px;
        overflow: hidden;
        border: 0;
        box-shadow: 0 4px 15px rgba(0,0,0,0.06);
    }

    .requests-header {
        background: var(--agro);
        color: #fff;
        padding: 1.5rem 1.75rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .requests-header h2 {
        font-size: 1.4rem;
        margin: 0;
        display: flex;
        align-items: center;
        gap: .5rem;
    }

    .requests-header h2 i {
        font-size: 1.5rem;
    }

    .requests-body {
        background: #fff;
        padding: 1.5rem 1.75rem 1.25rem;
    }

    .requests-filters .input-group,
    .requests-filters select {
        border-radius: 999px;
        overflow: hidden;
        box-shadow: 0 2px 6px rgba(0,0,0,0.06);
    }

    .requests-filters input {
        border: 0;
        box-shadow: none !important;
    }

    .requests-filters .btn-filter {
        border: 0;
        background: var(--agro);
        color: #fff;
    }

    .requests-filters .btn-filter:hover {
        background: var(--agro-700);
    }

    .table-requests thead th {
        border-top: 0;
        font-size: .85rem;
        text-transform: uppercase;
        letter-spacing: .03em;
        color: #6c757d;
        background-color: #f8f9fa;
        white-space: nowrap;
    }

    .table-requests tbody tr {
        transition: background-color .2s ease, transform .1s ease;
    }

    .table-requests tbody tr:hover {
        background-color: #fdfdfd;
        transform: translateY(-1px);
        box-shadow: 0 3px 10px rgba(0,0,0,0.04);
    }

    .table-requests td {
        vertical-align: middle;
        font-size: .9rem;
    }

    .btn-action {
        border-radius: 999px;
        padding: .25rem .6rem;
        font-size: .8rem;
    }

    .requests-footer {
        background: #fff;
        border-top: 1px solid #e9ecef;
        padding: .75rem 1.75rem;
    }

    @media (max-width: 992px) {
        .requests-header {
            flex-direction: column;
            align-items: flex-start;
            gap: .75rem;
        }
    }
</style>

<div class="container-fluid">
    <div class="requests-card card">

        {{-- HEADER --}}
        <div class="requests-header">
            <h2>
                <i class="fas fa-clipboard-list"></i>
                Solicitudes de Vendedor
            </h2>
        </div>

        {{-- BODY --}}
        <div class="requests-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            {{-- FILTROS --}}
            <div class="row align-items-center mb-3 requests-filters">
                <div class="col-lg-6 mb-2 mb-lg-0">
                    {{-- Búsqueda por texto (usuario, email, motivo...) --}}
                    <form method="GET" action="{{ route('admin.solicitudes-vendedor.index') }}">
                        <div class="input-group input-group-sm">
                            <input
                                type="text"
                                name="q"
                                class="form-control"
                                placeholder="Buscar por usuario, email, motivo..."
                                value="{{ request('q') }}"
                            >
                            <div class="input-group-append">
                                <button class="btn btn-filter" type="submit">
                                    <i class="fas fa-search mr-1"></i> Buscar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="col-lg-3 mb-2 mb-lg-0">
                    {{-- Filtro por estado --}}
                    <form method="GET" action="{{ route('admin.solicitudes-vendedor.index') }}">
                        <div class="input-group input-group-sm">
                            {{-- mantenemos q al cambiar estado, si existe --}}
                            <input type="hidden" name="q" value="{{ request('q') }}">
                            <select name="estado" class="form-control" onchange="this.form.submit()">
                                <option value="">Todos los estados</option>
                                <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendientes</option>
                                <option value="aprobada" {{ request('estado') == 'aprobada' ? 'selected' : '' }}>Aprobadas</option>
                                <option value="rechazada" {{ request('estado') == 'rechazada' ? 'selected' : '' }}>Rechazadas</option>
                            </select>
                        </div>
                    </form>
                </div>

                <div class="col-lg-3 text-lg-right text-muted small">
                    Panel de gestión de solicitudes de vendedor
                </div>
            </div>

            {{-- TABLA --}}
            <div class="table-responsive">
                <table class="table table-hover table-requests mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Usuario</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            <th>Dirección</th>
                            <th>Motivo</th>
                            <th>Estado</th>
                            <th>Fecha solicitud</th>
                            <th>Fecha revisión</th>
                            <th>Documento</th>
                            <th class="text-right" style="width: 220px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($solicitudes as $solicitud)
                            <tr>
                                <td>{{ $solicitud->id }}</td>
                                <td>
                                    <strong>{{ $solicitud->user->name }}</strong><br>
                                    <small class="text-muted">
                                        Rol: {{ $solicitud->user->role_name ?? 'cliente' }}
                                    </small>
                                </td>
                                <td>{{ $solicitud->user->email }}</td>
                                <td>{{ $solicitud->telefono }}</td>
                                <td><small>{{ Str::limit($solicitud->direccion, 40) }}</small></td>
                                <td><small>{{ Str::limit($solicitud->motivo, 60) }}</small></td>
                                <td>
                                    @if($solicitud->estado == 'pendiente')
                                        <span class="badge badge-warning">
                                            <i class="fas fa-clock"></i> Pendiente
                                        </span>
                                    @elseif($solicitud->estado == 'aprobada')
                                        <span class="badge badge-success">
                                            <i class="fas fa-check"></i> Aprobada
                                        </span>
                                    @else
                                        <span class="badge badge-danger">
                                            <i class="fas fa-times"></i> Rechazada
                                        </span>
                                    @endif
                                </td>
                                <td><small>{{ $solicitud->created_at->format('d/m/Y H:i') }}</small></td>
                                <td>
                                    @if($solicitud->fecha_revision_admin)
                                        <small>{{ $solicitud->fecha_revision_admin->format('d/m/Y H:i') }}</small>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($solicitud->archivo_documento)
                                        <a href="{{ asset('storage/'.$solicitud->archivo_documento) }}"
                                           target="_blank"
                                           class="btn btn-info btn-action">
                                            <i class="fas fa-file-download"></i> Ver
                                        </a>
                                    @else
                                        <span class="text-muted">Sin documento</span>
                                    @endif
                                </td>
                                <td class="text-right">
                                    @if($solicitud->estado == 'pendiente')
                                        <form action="{{ route('admin.solicitudes-vendedor.aprobar', $solicitud->id) }}"
                                              method="POST" class="d-inline-block">
                                            @csrf
                                            <button type="submit"
                                                    class="btn btn-success btn-action mb-1"
                                                    onclick="return confirm('¿Aprobar esta solicitud? El usuario {{ $solicitud->user->name ?? 'N/A' }} obtendrá rol de vendedor.')">
                                                <i class="fas fa-check"></i> Aprobar
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.solicitudes-vendedor.rechazar', $solicitud->id) }}"
                                              method="POST" class="d-inline-block">
                                            @csrf
                                            <button type="submit"
                                                    class="btn btn-danger btn-action mb-1"
                                                    onclick="return confirm('¿Rechazar la solicitud de {{ $solicitud->user->name ?? 'N/A' }}?')">
                                                <i class="fas fa-times"></i> Rechazar
                                            </button>
                                        </form>
                                    @else
                                        <span class="badge badge-secondary">Procesada</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center text-muted py-4">
                                    <i class="fas fa-inbox fa-2x mb-2"></i>
                                    <p class="mb-0">No hay solicitudes registradas.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- FOOTER / PAGINACIÓN --}}
        <div class="requests-footer d-flex justify-content-between align-items-center">
            <span class="text-muted small">
                Mostrando {{ $solicitudes->count() }} de {{ $solicitudes->total() }} solicitudes
            </span>
            <div class="mb-0">
                {{ $solicitudes->links() }}
            </div>
        </div>

    </div>
</div>
@endsection
