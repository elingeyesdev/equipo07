@extends('layouts.adminlte')
@section('title', 'Orgánicos')
@section('page_title', 'Orgánicos')

@section('content')

    {{-- Cabecera verde Agro --}}
    <div class="mb-3">
        <div class="bg-agro rounded-lg p-3 d-flex align-items-center text-white">
            <div>
                <h3 class="mb-0">Listado de Orgánicos</h3>
                <small>{{ $organicos->total() }} producto(s) registrado(s)</small>
            </div>

            <div class="ml-auto d-flex">
                <form method="get" class="form-inline mr-2">
                    <div class="input-group input-group-sm">
                        <input type="text" name="q" value="{{ $q ?? '' }}" class="form-control"
                            placeholder="Buscar...">
                        <div class="input-group-append">
                            <button class="btn btn-light">Buscar</button>
                        </div>
                    </div>
                </form>

                @if (auth()->check() && (auth()->user()->isVendedor() || auth()->user()->isAdmin()))
                    <a href="{{ route('organicos.create') }}" class="btn btn-outline-light btn-sm mr-2">
                        <i class="fas fa-plus-circle"></i> Nuevo Registro
                    </a>
                @endif

            </div>
        </div>
    </div>

    {{-- Mensajes --}}
    @if (session('ok'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle"></i> {{ session('ok') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif


    {{-- GRID de tarjetas --}}
    @if ($organicos->count())
        <div class="row">
            @foreach ($organicos as $o)
                <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">

                    <div class="card shadow-sm border-0 h-100">

                        {{-- Imagen --}}
                        @php
                            $img = optional($o->imagenes->first())->ruta ?? null;
                        @endphp
                        <div class="w-100 d-flex justify-content-center align-items-center"
                            style="height: 200px; background:#fff;">
                            <img src="{{ $img ? asset('storage/' . $img) : asset('img/organico-placeholder.jpg') }}"
                                style="max-height: 100%; max-width: 100%; object-fit: contain;" alt="{{ $o->nombre }}">
                        </div>


                        <div class="card-body d-flex flex-column">

                            {{-- Nombre --}}
                            <h5 class="card-title mb-1">
                                <a href="{{ route('organicos.show', $o) }}" class="text-dark">
                                    {{ $o->nombre }}
                                </a>
                            </h5>

                            {{-- Badges --}}
                            <div class="mb-2">
                                @if ($o->categoria)
                                    <span class="badge badge-agro">
                                        <i class="fas fa-tag"></i> {{ $o->categoria->nombre }}
                                    </span>
                                @endif

                                @if ($o->tipoCultivo)
                                    <span class="badge badge-warning">
                                        <i class="fas fa-seedling"></i> {{ $o->tipoCultivo->nombre }}
                                    </span>
                                @endif

                                @if ($o->unidad)
                                    <span class="badge badge-secondary">
                                        <i class="fas fa-balance-scale"></i> {{ $o->unidad->nombre }}
                                    </span>
                                @endif
                            </div>


                            {{-- Fecha --}}
                            @if ($o->fecha_cosecha)
                                <small class="text-muted d-block mb-2">
                                    <i class="fas fa-calendar-alt"></i>
                                    {{ \Carbon\Carbon::parse($o->fecha_cosecha)->format('d/m/Y') }}
                                </small>
                            @endif

                            {{-- Descripción --}}
                            @if ($o->descripcion)
                                <p class="text-muted mb-2" style="font-size: 0.85rem;">
                                    {{ Str::limit($o->descripcion, 70) }}
                                </p>
                            @endif

                            {{-- Ubicación --}}
                            <p class="mb-2">
                                <small class="text-muted">
                                    <i class="fas fa-map-marker-alt"></i>
                                    @if ($o->origen)
                                        {{ $o->origen }}
                                    @elseif($o->latitud_origen && $o->longitud_origen)
                                        Lat: {{ $o->latitud_origen }}, Lng: {{ $o->longitud_origen }}
                                    @else
                                        Sin ubicación
                                    @endif
                                </small>
                            </p>

                            {{-- Precio --}}
                            <div class="text-agro font-weight-bold mb-2" style="font-size: 1.2rem;">
                                Bs {{ number_format($o->precio, 2) }}
                            </div>

                            {{-- Stock --}}
                            <span class="badge badge-agro mb-3">
                                {{ $o->stock }}
                                {{ $o->unidad ? strtolower($o->unidad->nombre) : 'unidades' }}
                            </span>


                            {{-- Acciones --}}
                            <div class="mt-auto">
                                <a href="{{ route('organicos.show', $o) }}" class="btn btn-sm btn-outline-agro w-100 mb-1">
                                    <i class="fas fa-eye"></i> Ver
                                </a>

                                @if (auth()->check() && (auth()->user()->isAdmin() || $o->user_id == auth()->id()))
                                    <a href="{{ route('organicos.edit', $o) }}"
                                        class="btn btn-sm btn-outline-primary w-100 mb-1">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>

                                    <form action="{{ route('organicos.destroy', $o) }}" method="post">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger w-100"
                                            onclick="return confirm('¿Eliminar este orgánico?')">
                                            <i class="fas fa-trash-alt"></i> Eliminar
                                        </button>
                                    </form>
                                @endif
                            </div>

                        </div>

                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-3">
            {{ $organicos->appends(['q' => $q ?? null])->links() }}
        </div>
    @else
        <div class="card">
            <div class="card-body text-center text-muted">No hay productos orgánicos registrados.</div>
        </div>
    @endif

@endsection
