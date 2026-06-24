@extends('layouts.adminlte')

@section('title', 'Certificados de ganado pendientes')

@section('content')
    <style>
        .admin-cert-docs {
            display: flex;
            flex-wrap: wrap;
            gap: .35rem;
        }

        .admin-cert-doc-btn {
            align-items: center;
            background: #fff;
            border: 1px solid #28a745;
            border-radius: 6px;
            color: #16803a;
            display: inline-flex;
            font-size: .86rem;
            gap: .35rem;
            padding: .35rem .55rem;
        }

        .admin-cert-doc-btn:hover {
            background: #28a745;
            color: #fff;
        }

        .admin-cert-viewer {
            background: #f6faf7;
            border: 1px solid #dbe8df;
            border-radius: 8px;
            height: 72vh;
            overflow: hidden;
        }

        .admin-cert-viewer iframe,
        .admin-cert-viewer img {
            border: 0;
            height: 100%;
            width: 100%;
        }

        .admin-cert-viewer img {
            object-fit: contain;
        }

        .admin-reject-help {
            color: #6c757d;
            display: block;
            font-size: .78rem;
            line-height: 1.25;
            margin: -.25rem 0 .45rem;
            text-align: left;
        }

        .admin-reject-help.is-invalid {
            color: #dc3545;
            font-weight: 600;
        }
    </style>

    <div class="container-fluid">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
            <div>
                <h1 class="h3 mb-1">Certificados de ganado pendientes</h1>
                <p class="text-muted mb-0">Aprueba certificados válidos o rechaza la publicación indicando el motivo.</p>
            </div>
            <span class="badge badge-warning px-3 py-2">{{ $certificados->total() }} pendiente(s)</span>
        </div>

        @if (session('ok'))
            <div class="alert alert-success">{{ session('ok') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Publicación</th>
                                <th>Productor</th>
                                <th>Certificados</th>
                                <th>Enviado</th>
                                <th class="text-right">Revisión</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($certificados as $registro)
                                @php
                                    $documentos = collect([
                                        ['label' => 'Documento sanitario PDF', 'path' => $registro->documento_pdf],
                                        ['label' => 'Certificado SENASAG', 'path' => $registro->certificado_imagen],
                                        ['label' => 'Certificado de campeón', 'path' => $registro->certificado_campeon_imagen],
                                        ['label' => 'Árbol genealógico', 'path' => $registro->arbol_genealogico],
                                    ])->filter(fn ($doc) => filled($doc['path']))->map(function ($doc) {
                                        $extension = strtolower(pathinfo($doc['path'], PATHINFO_EXTENSION));
                                        $doc['url'] = asset('storage/' . $doc['path']);
                                        $doc['type'] = $extension === 'pdf' ? 'pdf' : 'image';
                                        return $doc;
                                    });
                                @endphp
                                <tr>
                                    <td>
                                        <a href="{{ route('ganados.show', $registro->ganado) }}"
                                            class="font-weight-bold text-success">
                                            {{ $registro->ganado->nombre }}
                                        </a>
                                        <small class="d-block text-muted">
                                            {{ $registro->ganado->tipoAnimal->nombre ?? 'Sin especie' }}
                                        </small>
                                    </td>
                                    <td>
                                        {{ $registro->ganado->user->name ?? 'Sin productor' }}
                                        <small class="d-block text-muted">{{ $registro->ganado->user->email ?? '' }}</small>
                                    </td>
                                    <td>
                                        <div class="admin-cert-docs">
                                            @foreach ($documentos as $doc)
                                                <button type="button" class="admin-cert-doc-btn"
                                                    data-admin-cert-viewer
                                                    data-file-title="{{ $doc['label'] }}"
                                                    data-file-src="{{ $doc['url'] }}"
                                                    data-file-type="{{ $doc['type'] }}">
                                                    <i class="fas fa-eye"></i>{{ $doc['label'] }}
                                                </button>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td>
                                        <small>{{ $registro->created_at?->format('d/m/Y H:i') }}</small>
                                    </td>
                                    <td class="text-right" style="min-width: 280px;">
                                        <form action="{{ route('admin.ganados.certificados.aprobar', $registro) }}"
                                            method="post" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-success mb-2">
                                                <i class="fas fa-check mr-1"></i>Aprobar
                                            </button>
                                        </form>

                                        <form action="{{ route('admin.ganados.certificados.rechazar', $registro) }}"
                                            method="post" class="admin-reject-form">
                                            @csrf
                                            @method('DELETE')
                                            <textarea name="motivo" class="form-control form-control-sm mb-2"
                                                data-reject-reason
                                                rows="2" required minlength="10"
                                                placeholder="Motivo del rechazo para el productor">{{ old('motivo') }}</textarea>
                                            <small class="admin-reject-help" data-reject-help>
                                                Escribe al menos 10 caracteres para notificar al productor.
                                            </small>
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash-alt mr-1"></i>Rechazar y eliminar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                                        <h5>No hay certificados de ganado pendientes</h5>
                                        <p class="text-muted mb-0">Todos los documentos fueron revisados.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if ($certificados->hasPages())
                <div class="card-footer">{{ $certificados->links() }}</div>
            @endif
        </div>
    </div>

    <div class="modal fade" id="adminCertModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="adminCertModalTitle">
                        <i class="fas fa-certificate text-success mr-1"></i>Certificado
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="admin-cert-viewer" id="adminCertViewer"></div>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-success" id="adminCertDownload" download>
                        <i class="fas fa-download mr-1"></i>Descargar archivo
                    </a>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const viewer = document.getElementById('adminCertViewer');
            const title = document.getElementById('adminCertModalTitle');
            const download = document.getElementById('adminCertDownload');

            document.querySelectorAll('[data-admin-cert-viewer]').forEach(function(button) {
                button.addEventListener('click', function() {
                    const fileSrc = button.dataset.fileSrc;
                    const fileType = button.dataset.fileType;
                    const fileTitle = button.dataset.fileTitle || 'Certificado';

                    title.innerHTML = `<i class="fas fa-certificate text-success mr-1"></i>${fileTitle}`;
                    download.href = fileSrc;
                    download.setAttribute('download', fileTitle);
                    viewer.innerHTML = '';

                    if (fileType === 'pdf') {
                        const iframe = document.createElement('iframe');
                        iframe.src = `${fileSrc}#toolbar=0&navpanes=0&scrollbar=1`;
                        iframe.setAttribute('title', fileTitle);
                        viewer.appendChild(iframe);
                    } else {
                        const image = document.createElement('img');
                        image.src = fileSrc;
                        image.alt = fileTitle;
                        viewer.appendChild(image);
                    }

                    $('#adminCertModal').modal('show');
                });
            });

            $('#adminCertModal').on('hidden.bs.modal', function() {
                viewer.innerHTML = '';
                download.href = '#';
            });

            document.querySelectorAll('.admin-reject-form').forEach(function(form) {
                const reason = form.querySelector('[data-reject-reason]');
                const help = form.querySelector('[data-reject-help]');

                function updateRejectHelp() {
                    const currentLength = reason.value.trim().length;
                    help.textContent = currentLength >= 10
                        ? 'Motivo listo. Se notificará al productor.'
                        : `Faltan ${10 - currentLength} caracter(es) para poder rechazar.`;
                    help.classList.toggle('is-invalid', currentLength < 10);
                }

                reason.addEventListener('input', updateRejectHelp);
                updateRejectHelp();

                form.addEventListener('submit', function(event) {
                    const currentLength = reason.value.trim().length;

                    if (currentLength < 10) {
                        event.preventDefault();
                        updateRejectHelp();
                        reason.focus();
                        alert('Para rechazar y eliminar la publicación debes escribir un motivo de al menos 10 caracteres.');
                        return;
                    }

                    if (!confirm('¿Rechazar certificado y eliminar esta publicación?')) {
                        event.preventDefault();
                    }
                });
            });
        });
    </script>
@endsection
