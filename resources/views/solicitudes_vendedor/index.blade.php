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

    .btn-document-view {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: .35rem;
        min-height: 34px;
        padding: .38rem .78rem;
        color: #fff;
        background: linear-gradient(135deg, var(--agro), var(--agro-700));
        border: 0;
        border-radius: 999px;
        font-size: .82rem;
        font-weight: 700;
        line-height: 1;
        box-shadow: 0 8px 18px rgba(63, 126, 42, .18);
        transition: transform .18s ease, box-shadow .18s ease, background .18s ease;
    }

    .btn-document-view:hover,
    .btn-document-view:focus {
        color: #fff;
        background: linear-gradient(135deg, var(--agro-700), #244d18);
        box-shadow: 0 10px 22px rgba(63, 126, 42, .24);
        transform: translateY(-1px);
    }

    .btn-document-view i {
        font-size: .9rem;
    }

    .requests-footer {
        background: #fff;
        border-top: 1px solid #e9ecef;
        padding: .75rem 1.75rem;
    }

    .seller-document-modal .modal-dialog {
        max-width: min(1100px, calc(100vw - 2rem));
    }

    .seller-document-modal .modal-content {
        border: 0;
        border-radius: 1rem;
        box-shadow: 0 24px 60px rgba(17, 24, 39, .24);
        overflow: hidden;
    }

    .seller-document-modal .modal-header {
        align-items: flex-start;
        background: linear-gradient(135deg, var(--agro), var(--agro-700));
        color: #fff;
        border-bottom: 0;
        padding: 1rem 1.25rem;
    }

    .seller-document-modal .modal-title {
        font-weight: 700;
        line-height: 1.2;
    }

    .seller-document-modal__subtitle {
        display: block;
        margin-top: .25rem;
        color: rgba(255,255,255,.78);
        font-size: .84rem;
        font-weight: 400;
    }

    .seller-document-modal .close {
        color: #fff;
        opacity: .9;
        text-shadow: none;
    }

    .seller-document-modal .modal-body {
        min-height: 520px;
        padding: 1rem;
        background: #f8fbf6;
    }

    .seller-document-preview {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 500px;
        background: #fff;
        border: 1px solid rgba(63,126,42,.12);
        border-radius: .85rem;
        overflow: hidden;
    }

    .seller-document-preview img {
        max-width: 100%;
        max-height: 70vh;
        object-fit: contain;
    }

    .seller-document-preview iframe {
        width: 100%;
        min-height: 70vh;
        border: 0;
        background: #fff;
    }

    .seller-document-preview__fallback {
        max-width: 460px;
        padding: 2rem;
        text-align: center;
        color: #536056;
    }

    .seller-document-preview__fallback i {
        color: var(--agro);
        font-size: 2.4rem;
        margin-bottom: .75rem;
    }

    .seller-document-modal .modal-footer {
        background: #fff;
        border-top: 1px solid #e9ecef;
    }

    @media (max-width: 992px) {
        .requests-header {
            flex-direction: column;
            align-items: flex-start;
            gap: .75rem;
        }

        .seller-document-modal .modal-body,
        .seller-document-preview {
            min-height: 360px;
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
                                        @php
                                            $documentUrl = asset('storage/'.$solicitud->archivo_documento);
                                        @endphp
                                        <button type="button"
                                                class="btn btn-document-view"
                                                data-file-viewer
                                                data-file-url="{{ $documentUrl }}"
                                                data-file-title="Documento de {{ $solicitud->user->name }}">
                                            <i class="fas fa-eye"></i> Ver documento
                                        </button>
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

<div class="modal fade seller-document-modal" id="sellerDocumentModal" tabindex="-1" role="dialog"
     aria-labelledby="sellerDocumentModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title" id="sellerDocumentModalTitle">Documento de solicitud</h5>
                    <span class="seller-document-modal__subtitle">Vista previa del archivo adjunto</span>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="seller-document-preview" id="sellerDocumentPreview">
                    <div class="seller-document-preview__fallback">
                        <i class="fas fa-file-alt"></i>
                        <strong class="d-block mb-1">Selecciona un documento</strong>
                        <span>La vista previa aparecerá aquí.</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="#" class="btn btn-success" id="sellerDocumentOpen" target="_blank" rel="noopener">
                    <i class="fas fa-external-link-alt mr-1"></i> Abrir archivo
                </a>
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                    Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var modal = document.getElementById('sellerDocumentModal');
        var preview = document.getElementById('sellerDocumentPreview');
        var title = document.getElementById('sellerDocumentModalTitle');
        var openLink = document.getElementById('sellerDocumentOpen');

        if (!modal || !preview || !title || !openLink) {
            return;
        }

        $('#sellerDocumentModal').on('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var url = button.getAttribute('data-document-url');
            var type = button.getAttribute('data-document-type');
            var name = button.getAttribute('data-document-name') || 'Documento de solicitud';

            title.textContent = name;
            openLink.href = url;
            preview.innerHTML = '';

            if (type === 'image') {
                var image = document.createElement('img');
                image.src = url;
                image.alt = name;
                preview.appendChild(image);
                return;
            }

            if (type === 'pdf') {
                var frame = document.createElement('iframe');
                frame.src = url;
                frame.title = name;
                preview.appendChild(frame);
                return;
            }

            preview.innerHTML = [
                '<div class="seller-document-preview__fallback">',
                    '<i class="fas fa-file-alt"></i>',
                    '<strong class="d-block mb-1">Vista previa no disponible</strong>',
                    '<span>Este tipo de archivo debe abrirse directamente.</span>',
                '</div>'
            ].join('');
        });

        $('#sellerDocumentModal').on('hidden.bs.modal', function () {
            preview.innerHTML = [
                '<div class="seller-document-preview__fallback">',
                    '<i class="fas fa-file-alt"></i>',
                    '<strong class="d-block mb-1">Selecciona un documento</strong>',
                    '<span>La vista previa aparecerá aquí.</span>',
                '</div>'
            ].join('');
            openLink.href = '#';
        });
    });
</script>
@endsection
