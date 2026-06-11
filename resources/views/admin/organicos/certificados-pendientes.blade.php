@extends('layouts.adminlte')

@section('title', 'Certificados organicos pendientes')

@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
            <div>
                <h1 class="h3 mb-1">Certificados organicos pendientes</h1>
                <p class="text-muted mb-0">Revisa los documentos cargados por los vendedores.</p>
            </div>
            <span class="badge badge-warning px-3 py-2">
                {{ $certificados->total() }} pendiente(s)
            </span>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Producto</th>
                                <th>Vendedor</th>
                                <th>Certificado</th>
                                <th>Fechas</th>
                                <th>Archivo</th>
                                <th class="text-right">Revision</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($certificados as $registro)
                                @php
                                    $nombreCertificado = $registro->certificado?->nombre
                                        ?? $registro->nombre_adicional
                                        ?? 'Certificado adicional';
                                    $archivoUrl = asset('storage/' . $registro->archivo);
                                @endphp
                                <tr>
                                    <td>
                                        @if ($registro->organico)
                                            <a href="{{ route('organicos.show', $registro->organico) }}"
                                                class="font-weight-bold text-success">
                                                {{ $registro->organico->nombre }}
                                            </a>
                                        @else
                                            <span class="text-muted">Producto eliminado</span>
                                        @endif
                                        <small class="d-block text-muted">
                                            Enviado {{ optional($registro->created_at)->format('d/m/Y H:i') }}
                                        </small>
                                    </td>
                                    <td>
                                        {{ $registro->organico?->user?->name ?? 'Sin vendedor' }}
                                        <small class="d-block text-muted">
                                            {{ $registro->organico?->user?->email }}
                                        </small>
                                    </td>
                                    <td>
                                        <strong>{{ $nombreCertificado }}</strong>
                                        @if ($registro->observaciones)
                                            <small class="d-block text-muted">{{ $registro->observaciones }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <small class="d-block">
                                            Emision:
                                            {{ $registro->fecha_emision?->format('d/m/Y') ?? 'No indicada' }}
                                        </small>
                                        <small class="d-block">
                                            Vencimiento:
                                            {{ $registro->fecha_vencimiento?->format('d/m/Y') ?? 'No indicado' }}
                                        </small>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-outline-success"
                                            data-file-viewer data-file-url="{{ $archivoUrl }}"
                                            data-file-title="{{ $nombreCertificado }}">
                                            <i class="fas fa-eye mr-1"></i> Revisar
                                        </button>
                                    </td>
                                    <td class="text-right text-nowrap">
                                        <form action="{{ route('admin.organicos.certificados.estado', $registro) }}"
                                            method="post" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="estado" value="verificado">
                                            <button type="submit" class="btn btn-sm btn-success">
                                                <i class="fas fa-check mr-1"></i> Aprobar
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.organicos.certificados.estado', $registro) }}"
                                            method="post" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="estado" value="rechazado">
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-times mr-1"></i> Rechazar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                                        <h5>No hay certificados pendientes</h5>
                                        <p class="text-muted mb-0">Todos los documentos organicos fueron revisados.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if ($certificados->hasPages())
                <div class="card-footer">
                    {{ $certificados->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
