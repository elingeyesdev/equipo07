@extends('layouts.adminlte')

@section('title', 'Certificados de ganado pendientes')

@section('content')
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
                                    ])->filter(fn ($doc) => filled($doc['path']));
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
                                        @foreach ($documentos as $doc)
                                            <a href="{{ asset('storage/' . $doc['path']) }}" target="_blank"
                                                class="btn btn-sm btn-outline-success mb-1">
                                                <i class="fas fa-eye mr-1"></i>{{ $doc['label'] }}
                                            </a>
                                        @endforeach
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
                                            method="post">
                                            @csrf
                                            @method('DELETE')
                                            <textarea name="motivo" class="form-control form-control-sm mb-2"
                                                rows="2" required minlength="10"
                                                placeholder="Motivo del rechazo para el productor">{{ old('motivo') }}</textarea>
                                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('¿Rechazar certificado y eliminar esta publicación?')">
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
@endsection
