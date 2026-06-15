@extends('layouts.adminlte')

@section('title', 'Listado de Animales')
@section('page_title', 'Listado de Animales')

@section('content')
    <style>
        .animal-card {
            border: 0;
            border-radius: .75rem;
            transition: all 0.3s ease;
            background: white;
            box-shadow: var(--app-shadow-sm);
            display: flex;
            flex-direction: column;
            height: 100%;
            overflow: hidden;
        }

        .animal-card:hover {
            box-shadow: var(--app-shadow-md);
            transform: translateY(-2px);
        }

        .animal-image-wrapper {
            width: 100%;
            height: 200px;
            background: #f8f9fa;
            border-radius: 0;
            border: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            cursor: pointer;
        }

        .animal-image {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            /* se ve completa */
            transition: transform 0.3s ease;
        }

        .animal-image-wrapper:hover .animal-image {
            transform: scale(1.03);
        }

        .animal-info h5 {
            font-size: 1.1rem;
            font-weight: 600;
            color: #1f2a1f;
            margin-bottom: 0.5rem;
        }

        .info-badge {
            padding: 0.25rem 0.6rem;
            border-radius: 999px;
            font-size: 0.8rem;
            font-weight: 500;
            margin-right: 0.25rem;
            margin-bottom: 0.25rem;
            display: inline-block;
        }

        .price-tag {
            font-size: 1.2rem;
            font-weight: 700;
            color: #0f9f3d;
        }

        .stock-badge {
            font-size: 0.9rem;
            padding: 0.35rem 0.8rem;
            border-radius: 999px;
        }

        .info-row {
            display: flex;
            align-items: center;
            margin-bottom: 0.25rem;
            font-size: 0.9rem;
        }

        .info-row i {
            width: 18px;
            color: #6c757d;
            margin-right: 0.35rem;
        }

        .action-buttons {
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
        }

        .btn-action {
            border-radius: .45rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .btn-action:hover {
            transform: translateY(-2px);
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
        }

        .empty-state i {
            font-size: 5rem;
            color: #dee2e6;
            margin-bottom: 1.5rem;
        }

        @media (max-width: 768px) {
            .animal-image-wrapper {
                height: 180px;
            }
        }
    </style>

    <div class="container-fluid">
        <div class="module-list-header">
            <div>
                <h3 class="module-list-header__title">Listado de Animales</h3>
                <small class="module-list-header__meta">
                    {{ $ganados->total() ?? $ganados->count() }}
                    {{ ($ganados->total() ?? $ganados->count()) == 1 ? 'animal registrado' : 'animales registrados' }}
                </small>
            </div>

            <div class="module-list-header__actions">
                @if (auth()->check() && (auth()->user()->isVendedor() || auth()->user()->isAdmin()))
                    <a href="{{ route('ganados.create') }}" class="btn btn-outline-light btn-sm">
                        <i class="fas fa-plus-circle mr-1"></i> Nuevo Registro
                    </a>
                @endif
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if ($ganados->count() > 0)
            <div class="row">
                @foreach ($ganados as $ganado)
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                        <div class="card shadow-sm border-0 h-100 project-card animal-card">

                                    {{-- Imagen --}}
                                    @php
                                        $imagenPrincipal = $ganado->imagenes->first()->ruta ?? null;
                                    @endphp
                                    <div class="animal-image-wrapper"
                                        @if ($imagenPrincipal) onclick="window.open('{{ asset('storage/' . $imagenPrincipal) }}', '_blank')"
                       title="Click para ver imagen completa" @endif>
                                        @if ($imagenPrincipal)
                                            <img src="{{ asset('storage/' . $imagenPrincipal) }}" alt="{{ $ganado->nombre }}"
                                                class="animal-image">
                                        @else
                                            <i class="fas fa-image fa-3x text-muted"></i>
                                        @endif
                                    </div>

                                    {{-- Info principal --}}
                                    <div class="animal-info mb-3 px-3 pt-3">
                                        <h5 class="module-card-title">
                                            <span>
                                                <i class="fas fa-tag text-primary mr-2"></i>{{ $ganado->nombre }}
                                            </span>
                                        </h5>

                                        <div class="mb-2">
                                            @if ($ganado->tipoAnimal)
                                                <span class="info-badge badge-info">
                                                    <i class="fas fa-paw mr-1"></i>{{ $ganado->tipoAnimal->nombre }}
                                                </span>
                                            @endif
                                            @if ($ganado->raza)
                                                <span class="info-badge badge-secondary">
                                                    <i class="fas fa-dna mr-1"></i>{{ $ganado->raza->nombre }}
                                                </span>
                                            @endif
                                            @if ($ganado->categoria)
                                                <span class="info-badge badge-success">
                                                    <i class="fas fa-tags mr-1"></i>{{ $ganado->categoria->nombre }}
                                                </span>
                                            @endif
                                        </div>

                                        <div class="info-row">
                                            <i class="fas fa-birthday-cake"></i>
                                            <span><strong>Edad:</strong> {{ $ganado->edad ?? 'N/A' }} meses</span>
                                        </div>

                                        <div class="info-row">
                                            <i class="fas fa-venus-mars"></i>
                                            <span><strong>Sexo:</strong> {{ $ganado->sexo ?? 'N/A' }}</span>
                                        </div>

                                        @if ($ganado->tipoPeso)
                                            <div class="info-row">
                                                <i class="fas fa-weight-hanging"></i>
                                                <span><strong>Peso:</strong> {{ $ganado->tipoPeso->nombre }}</span>
                                            </div>
                                        @endif

                                        <div class="info-row">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <span class="text-muted">
                                                {{ Str::limit($ganado->ubicacion ?? 'Sin ubicación', 45) }}
                                            </span>
                                        </div>

                                        @if ($ganado->fecha_publicacion)
                                            <div class="info-row">
                                                <i class="fas fa-calendar-check"></i>
                                                <span class="text-success">
                                                    <strong>Publicado:</strong>
                                                    {{ \Carbon\Carbon::parse($ganado->fecha_publicacion)->format('d/m/Y') }}
                                                </span>
                                            </div>
                                        @else
                                            <div class="info-row">
                                                <i class="fas fa-calendar-times"></i>
                                                <span class="text-warning"><strong>No publicado</strong></span>
                                            </div>
                                        @endif

                                        @if ($ganado->datoSanitario)
                                            <div class="info-row">
                                                <i class="fas fa-syringe"></i>
                                                <span class="text-info">
                                                    <strong>Datos sanitarios:</strong>
                                                    {{ Str::limit($ganado->datoSanitario->vacuna ?? 'Registrado', 40) }}
                                                </span>
                                            </div>
                                        @else
                                            <div class="info-row">
                                                <i class="fas fa-syringe"></i>
                                                <span class="text-muted">Sin registro sanitario</span>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Footer: precio + acciones --}}
                                    <div class="mt-auto px-3 pb-3">
                                        @if ($ganado->precio)
                                            <div class="mb-2">
                                                <span class="text-muted small d-block">Precio</span>
                                                <div class="price-tag">Bs {{ number_format($ganado->precio, 2) }}</div>
                                            </div>
                                        @endif

                                        <div class="mb-3">
                                            <span class="text-muted small d-block">Stock disponible</span>
                                            <span
                                                class="stock-badge badge {{ ($ganado->stock ?? 0) > 0 ? 'badge-success' : 'badge-danger' }}">
                                                <i class="fas fa-boxes mr-1"></i>{{ $ganado->stock ?? 0 }} unidades
                                            </span>
                                        </div>

                                        <div class="mt-auto">
                                            <a href="{{ route('ganados.show', $ganado->id) }}"
                                                class="btn btn-sm btn-outline-agro w-100 mb-1" title="Ver detalles">
                                                <i class="fas fa-eye"></i> Ver
                                            </a>

                                            @if (auth()->check() && (auth()->user()->isVendedor() || auth()->user()->isAdmin()))
                                                @if (auth()->user()->isAdmin() || $ganado->user_id == auth()->id())
                                                    <a href="{{ route('ganados.edit', $ganado->id) }}"
                                                        class="btn btn-sm btn-outline-primary w-100 mb-1" title="Editar">
                                                        <i class="fas fa-edit"></i> Editar
                                                    </a>

                                                    <form action="{{ route('ganados.destroy', $ganado->id) }}"
                                                        method="POST" class="d-inline" id="deleteForm{{ $ganado->id }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-sm btn-outline-danger w-100"
                                                            onclick="confirmDelete({{ $ganado->id }})" title="Eliminar">
                                                            <i class="fas fa-trash-alt"></i> Eliminar
                                                        </button>
                                                    </form>
                                                @endif
                                            @endif
                                        </div>
                                    </div>

                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-4">
                {{ $ganados->links() }}
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-cow"></i>
                <h3 class="text-muted mb-3">No hay animales registrados</h3>
                <p class="text-muted mb-4">Comienza agregando tu primer animal al sistema</p>
                @if (auth()->check() && (auth()->user()->isVendedor() || auth()->user()->isAdmin()))
                    <a href="{{ route('ganados.create') }}" class="btn btn-success btn-lg">
                        <i class="fas fa-plus-circle mr-2"></i>Agregar Primer Animal
                    </a>
                @endif
            </div>
        @endif
    </div>

    <script>
        function confirmDelete(ganadoId) {
            if (confirm('¿Estás seguro de eliminar este animal? Esta acción no se puede deshacer.')) {
                document.getElementById('deleteForm' + ganadoId).submit();
            }
        }
    </script>
@endsection
