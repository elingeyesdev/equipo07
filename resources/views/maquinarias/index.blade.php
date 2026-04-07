@extends('layouts.adminlte')
@section('title', 'Maquinarias')
@section('page_title', 'Maquinarias')

@section('content')

    {{-- Cabecera verde Agro (igual que Orgánicos) --}}
    <div class="mb-3">
        <div class="bg-agro rounded-lg p-3 d-flex align-items-center text-white">
            <div>
                <h3 class="mb-0">Listado de Maquinarias</h3>
                <small>{{ $maquinarias->total() }} maquinaria(s) registrada(s)</small>
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
                    <a href="{{ route('maquinarias.create') }}" class="btn btn-outline-light btn-sm mr-2">
                        <i class="fas fa-plus-circle"></i> Nuevo Registro
                    </a>
                @endif

            </div>
        </div>
    </div>


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

    {{-- Tarjetas de maquinarias --}}
    <div class="row">
        @forelse($maquinarias as $m)
            <div class="col-md-6 col-xl-4 mb-4">
                <div class="card h-100 shadow-sm project-card">

                    <div class="bg-light d-flex align-items-center justify-content-center" style="height: 230px;">
                        @if ($m->imagenes && $m->imagenes->count() > 0)
                            <div class="bg-light d-flex align-items-center justify-content-center" style="height: 230px;">
                                <img src="{{ $m->imagenes->count() ? asset('storage/' . $m->imagenes->first()->ruta) : asset('img/maquinaria-placeholder.jpg') }}"
                                    style="max-height: 100%; max-width: 100%; object-fit: contain;"
                                    alt="{{ $m->nombre }}">
                            </div>
                        @else
                            <div class="text-center text-muted">
                                <i class="fas fa-tractor fa-3x mb-2"></i>
                                <div>Sin imagen</div>
                            </div>
                        @endif
                    </div>

                    <div class="card-body d-flex flex-column">
                        <h5 class="mb-1">
                            <a href="{{ route('maquinarias.show', $m) }}" class="text-dark">
                                {{ $m->nombre }}
                            </a>
                        </h5>

                        <div class="mb-2">
                            @if ($m->tipoMaquinaria)
                                <span class="badge badge-warning mr-1">
                                    <i class="fas fa-cog"></i>
                                    {{ $m->tipoMaquinaria->nombre }}
                                </span>
                            @endif
                            @if ($m->marcaMaquinaria)
                                <span class="badge badge-info">
                                    <i class="fas fa-tag"></i>
                                    {{ $m->marcaMaquinaria->nombre }}
                                </span>
                            @endif
                        </div>

                        <div class="mb-2">
                            @if ($m->estadoMaquinaria)
                                @php $estado = strtolower($m->estadoMaquinaria->nombre); @endphp
                                <span class="badge {{ $estado === 'disponible' ? 'badge-success' : 'badge-secondary' }}">
                                    {{ $m->estadoMaquinaria->nombre }}
                                </span>
                            @else
                                <span class="badge badge-secondary">Sin estado</span>
                            @endif
                        </div>

                        <div class="small text-muted mb-2">
                            <i class="far fa-calendar-alt"></i>
                            {{ optional($m->created_at)->format('d/m/Y') }}
                            @if ($m->ubicacion)
                                &nbsp; • &nbsp;
                                <i class="fas fa-map-marker-alt text-danger"></i>
                                {{ \Illuminate\Support\Str::limit($m->ubicacion, 40) }}
                            @endif
                        </div>

                        @if ($m->descripcion)
                            <p class="text-muted small mb-2">
                                {{ \Illuminate\Support\Str::limit($m->descripcion, 100) }}
                            </p>
                        @endif

                        @if ($m->precio_dia)
                            <div class="mt-auto">
                                <div class="font-weight-bold mb-1">
                                    Bs {{ number_format($m->precio_dia, 2) }}
                                    <span class="text-muted">/ día</span>
                                </div>

                                <div class="progress" style="height: 18px;">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: 100%;">
                                        Precio por día
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="card-footer bg-white">
                        <div class="d-grid gap-2">
                            <a href="{{ route('maquinarias.show', $m) }}" class="btn btn-outline-success btn-block mb-1">
                                <i class="fas fa-eye"></i> Ver
                            </a>

                            @if (auth()->check() && (auth()->user()->isVendedor() || auth()->user()->isAdmin()))
                                @if (auth()->user()->isAdmin() || $m->user_id == auth()->id())
                                    <a href="{{ route('maquinarias.edit', $m) }}"
                                        class="btn btn-outline-primary btn-block mb-1">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>

                                    <form action="{{ route('maquinarias.destroy', $m) }}" method="post"
                                        class="delete-form" data-message="¿Está seguro de eliminar esta maquinaria?">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-block">
                                            <i class="fas fa-trash-alt"></i> Eliminar
                                        </button>
                                    </form>
                                @endif
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center text-muted py-5">
                    No hay maquinarias registradas.
                </div>
            </div>
        @endforelse
    </div>

    {{-- Paginación --}}
    <div class="mt-3">
        {{ $maquinarias->appends(['q' => $q ?? null])->links() }}
    </div>

@endsection
