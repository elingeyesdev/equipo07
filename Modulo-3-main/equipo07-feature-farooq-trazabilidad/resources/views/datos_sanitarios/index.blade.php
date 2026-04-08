@extends('layouts.adminlte')

@section('title', 'Datos Sanitarios')

@section('content')
    <div class="container-fluid">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1 text-dark">
                    <i class="fas fa-syringe text-success"></i> Datos Sanitarios
                </h1>
                <p class="text-muted mb-0">Gestión de registros sanitarios de los animales</p>
            </div>
            <a href="{{ route('admin.datos-sanitarios.create') }}" class="btn btn-success btn-lg">
                <i class="fas fa-plus-circle"></i> Nuevo Registro
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if ($items->count() > 0)
            <!-- Estadísticas Rápidas -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="mb-0">Total Registros</h6>
                                    <h3 class="mb-0">{{ $items->count() }}</h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-clipboard-list fa-2x opacity-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="mb-0">Con Certificado</h6>
                                    <h3 class="mb-0">{{ $items->where('certificado_imagen', '!=', null)->count() }}</h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-certificate fa-2x opacity-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="mb-0">Fiebre Aftosa</h6>
                                    <h3 class="mb-0">{{ $items->where('vacunado_fiebre_aftosa', true)->count() }}</h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-shield-alt fa-2x opacity-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="mb-0">Antirrábica</h6>
                                    <h3 class="mb-0">{{ $items->where('vacunado_antirabica', true)->count() }}</h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-shield-alt fa-2x opacity-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla de Registros -->
            <div class="card shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">
                        <i class="fas fa-list"></i> Lista de Registros Sanitarios
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th style="width: 60px;">ID</th>
                                    <th>Animal</th>
                                    <th>Dueño</th>
                                    <th>Vacunaciones</th>
                                    <th>Documentos</th>
                                    <th>Guía Movimiento</th>
                                    <th style="width: 120px;">Fecha</th>
                                    <th style="width: 140px;" class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items as $item)
                                    <tr>
                                        <td>
                                            <span class="badge badge-secondary badge-lg">#{{ $item->id }}</span>
                                        </td>
                                        <td>
                                            @if ($item->ganado)
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-cow text-primary mr-2"></i>
                                                    <div>
                                                        <strong class="d-block">{{ $item->ganado->nombre }}</strong>
                                                        @if ($item->ganado->tipoAnimal)
                                                            <small class="text-muted">
                                                                <i class="fas fa-paw"></i>
                                                                {{ $item->ganado->tipoAnimal->nombre }}
                                                            </small>
                                                        @endif
                                                        @if (!$item->ganado->fecha_publicacion)
                                                            <br><span class="badge badge-warning badge-sm mt-1">
                                                                <i class="fas fa-eye-slash"></i> Sin publicar
                                                            </span>
                                                        @else
                                                            <br><span class="badge badge-success badge-sm mt-1">
                                                                <i class="fas fa-eye"></i> Publicado
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-muted"><i class="fas fa-info-circle"></i> Sin datos</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($item->nombre_dueno)
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-user text-info mr-2"></i>
                                                    <div>
                                                        <strong class="d-block">{{ $item->nombre_dueno }}</strong>
                                                        @if ($item->carnet_dueno_foto)
                                                            <a href="{{ asset('storage/' . $item->carnet_dueno_foto) }}"
                                                                target="_blank" class="btn btn-sm btn-info mt-1">
                                                                <i class="fas fa-id-card"></i> Ver Carnet
                                                            </a>
                                                        @else
                                                            <small class="text-muted">Sin carnet</small>
                                                        @endif
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-muted"><i class="fas fa-info-circle"></i> Sin datos</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                @if ($item->vacunado_fiebre_aftosa)
                                                    <span class="badge badge-success mb-1">
                                                        <i class="fas fa-check"></i> Fiebre Aftosa
                                                    </span>
                                                @endif
                                                @if ($item->vacunado_antirabica)
                                                    <span class="badge badge-success mb-1">
                                                        <i class="fas fa-check"></i> Antirrábica
                                                    </span>
                                                @endif
                                                @if ($item->vacuna)
                                                    <small class="text-muted mt-1">
                                                        <i class="fas fa-vial"></i> {{ Str::limit($item->vacuna, 30) }}
                                                    </small>
                                                @endif
                                                @if (!$item->vacunado_fiebre_aftosa && !$item->vacunado_antirabica && !$item->vacuna)
                                                    <span class="text-muted"><i class="fas fa-info-circle"></i> Sin
                                                        datos</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                @if ($item->certificado_imagen)
                                                    <a href="{{ asset('storage/' . $item->certificado_imagen) }}"
                                                        target="_blank" class="btn btn-sm btn-info mb-1">
                                                        <i class="fas fa-certificate"></i> Certificado
                                                    </a>
                                                @else
                                                    <span class="text-muted"><i class="fas fa-info-circle"></i> Sin
                                                        datos</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            @if ($item->marca_ganado || $item->senal_numero || $item->marca_ganado_foto)
                                                <div class="small">
                                                    @if ($item->marca_ganado)
                                                        <div class="mb-1">
                                                            <i class="fas fa-tag text-primary"></i>
                                                            <strong>Marca:</strong> {{ $item->marca_ganado }}
                                                        </div>
                                                    @endif
                                                    @if ($item->senal_numero)
                                                        <div class="mb-1">
                                                            <i class="fas fa-hashtag text-primary"></i>
                                                            <strong>Señal:</strong> {{ $item->senal_numero }}
                                                        </div>
                                                    @endif
                                                    @if ($item->marca_ganado_foto)
                                                        <div class="mb-1">
                                                            <a href="{{ asset('storage/' . $item->marca_ganado_foto) }}"
                                                                target="_blank" class="btn btn-sm btn-primary">
                                                                <i class="fas fa-image"></i> Ver Foto Marca
                                                            </a>
                                                        </div>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-muted"><i class="fas fa-info-circle"></i> Sin
                                                    datos</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($item->fecha_aplicacion)
                                                <div class="small">
                                                    <i class="fas fa-calendar text-success"></i>
                                                    <strong>{{ \Carbon\Carbon::parse($item->fecha_aplicacion)->format('d/m/Y') }}</strong>
                                                </div>
                                            @else
                                                <span class="text-muted"><i class="fas fa-info-circle"></i> Sin
                                                    datos</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.datos-sanitarios.edit', $item->id) }}"
                                                    class="btn btn-sm btn-warning" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.datos-sanitarios.destroy', $item->id) }}"
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('¿Está seguro de eliminar este registro sanitario?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger"
                                                        title="Eliminar">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @else
            <!-- Estado Vacío -->
            <div class="card shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="fas fa-syringe fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted">No hay registros sanitarios</h4>
                    <p class="text-muted mb-4">Comience creando su primer registro sanitario</p>
                    <a href="{{ route('admin.datos-sanitarios.create') }}" class="btn btn-success btn-lg">
                        <i class="fas fa-plus-circle"></i> Crear Primer Registro
                    </a>
                </div>
            </div>
        @endif
    </div>

    <style>
        .badge-lg {
            font-size: 1rem;
            padding: 0.5rem 0.75rem;
        }

        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
        }

        .thead-light th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
        }
    </style>
@endsection
