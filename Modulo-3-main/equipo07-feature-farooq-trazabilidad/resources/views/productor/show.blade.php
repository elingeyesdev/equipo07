@extends('layouts.public')

@section('title', 'Perfil de Productor | AgroVida')

@section('content')
<div class="content-header bg-light mb-4 py-4 border-bottom">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-2 text-center text-md-left mb-3 mb-md-0">
                @if($productor->foto_perfil)
                    <img class="profile-user-img img-fluid img-circle" style="width: 150px; height: 150px; object-fit: cover;" src="{{ asset('storage/' . $productor->foto_perfil) }}" alt="User profile picture">
                @else
                    <div class="d-inline-flex justify-content-center align-items-center rounded-circle bg-success text-white" style="width: 150px; height: 150px; font-size: 3rem;">
                        <i class="fas fa-user-tie"></i>
                    </div>
                @endif
            </div>
            <div class="col-md-10">
                <h1 class="m-0 text-dark font-weight-bold">
                    {{ $productor->name }}
                    <span class="badge badge-success align-top ml-2" style="font-size: 14px;"><i class="fas fa-check-circle"></i> Productor Verificado</span>
                </h1>
                
                <div class="mt-3">
                    @if($productor->telefono)
                        <span class="mr-4 text-muted"><i class="fas fa-phone-alt text-primary"></i> {{ $productor->telefono }}</span>
                    @endif
                    
                    @if($productor->direccion)
                        <span class="mr-4 text-muted"><i class="fas fa-map-marker-alt text-danger"></i> {{ $productor->direccion }}</span>
                    @endif
                    
                    <span class="mr-4 text-muted"><i class="fas fa-envelope text-info"></i> {{ $productor->email }}</span>
                </div>
                
                @if($productor->biografia)
                    <p class="mt-3 text-secondary" style="max-width: 800px;">
                        <i class="fas fa-quote-left text-muted mr-1"></i> {{ $productor->biografia }} <i class="fas fa-quote-right text-muted ml-1"></i>
                    </p>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container">
        <h3 class="mb-4"><i class="fas fa-boxes text-success"></i> Catálogo de Productos</h3>
        
        <div class="card card-success card-outline card-outline-tabs shadow-sm">
            <div class="card-header p-0 border-bottom-0">
                <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active font-weight-bold" id="tabs-ganados-tab" data-toggle="pill" href="#tabs-ganados" role="tab" aria-controls="tabs-ganados" aria-selected="true">
                            <i class="fas fa-cow mr-1"></i> Ganados ({{ $productor->ganados->count() }})
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link font-weight-bold" id="tabs-organicos-tab" data-toggle="pill" href="#tabs-organicos" role="tab" aria-controls="tabs-organicos" aria-selected="false">
                            <i class="fas fa-seedling mr-1"></i> Orgánicos ({{ $productor->organicos->count() }})
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link font-weight-bold" id="tabs-maquinarias-tab" data-toggle="pill" href="#tabs-maquinarias" role="tab" aria-controls="tabs-maquinarias" aria-selected="false">
                            <i class="fas fa-tractor mr-1"></i> Maquinaria ({{ $productor->maquinarias->count() }})
                        </a>
                    </li>
                </ul>
            </div>
            
            <div class="card-body bg-light">
                <div class="tab-content" id="custom-tabs-four-tabContent">
                    
                    <!-- TAB GANADOS -->
                    <div class="tab-pane fade show active" id="tabs-ganados" role="tabpanel" aria-labelledby="tabs-ganados-tab">
                        @if($productor->ganados->isEmpty())
                            <div class="alert alert-light text-center py-5">
                                <i class="fas fa-folder-open text-muted fa-3x mb-3"></i>
                                <h5>Este productor no tiene ganados publicados aún.</h5>
                            </div>
                        @else
                            <div class="row">
                                @foreach($productor->ganados as $ganado)
                                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                                    <div class="card h-100 shadow-sm border-0 product-card">
                                        @if($ganado->imagenes->first())
                                            <img src="{{ asset('storage/' . $ganado->imagenes->first()->ruta) }}" class="card-img-top" alt="{{ $ganado->nombre }}" style="height: 180px; object-fit: cover;">
                                        @else
                                            <div class="bg-secondary d-flex justify-content-center align-items-center" style="height: 180px;">
                                                <i class="fas fa-camera fa-3x text-light"></i>
                                            </div>
                                        @endif
                                        <div class="card-body">
                                            <!-- Badge Trazabilidad -->
                                            @if($ganado->datoSanitario)
                                                <span class="badge badge-info mb-2 w-100"><i class="fas fa-shield-alt"></i> Trazabilidad Sanitaria Info</span>
                                            @endif
                                            
                                            <h5 class="card-title font-weight-bold w-100 mb-2">{{ $ganado->nombre }}</h5>
                                            <p class="card-text text-success font-weight-bold mb-1">Bs. {{ number_format($ganado->precio, 2) }}</p>
                                            <p class="text-muted small"><i class="fas fa-map-marker-alt"></i> {{ $ganado->ubicacion ?? 'Ubicación no especificada' }}</p>
                                        </div>
                                        <div class="card-footer bg-white border-top-0 pt-0">
                                            <a href="{{ route('ganados.show', $ganado->id) }}" class="btn btn-outline-success btn-block btn-sm">Ver Detalles</a>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    
                    <!-- TAB ORGÁNICOS -->
                    <div class="tab-pane fade" id="tabs-organicos" role="tabpanel" aria-labelledby="tabs-organicos-tab">
                        @if($productor->organicos->isEmpty())
                            <div class="alert alert-light text-center py-5">
                                <i class="fas fa-folder-open text-muted fa-3x mb-3"></i>
                                <h5>Este productor no tiene productos orgánicos publicados aún.</h5>
                            </div>
                        @else
                            <div class="row">
                                @foreach($productor->organicos as $organico)
                                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                                    <div class="card h-100 shadow-sm border-0 product-card">
                                        <!-- Etiqueta Especial -->
                                        <div class="position-absolute" style="top: 10px; right: 10px; z-index: 2;">
                                            <span class="badge badge-success shadow-sm">100% Orgánico</span>
                                        </div>

                                        @if($organico->imagenes->first())
                                            <img src="{{ asset('storage/' . $organico->imagenes->first()->ruta) }}" class="card-img-top" alt="{{ $organico->nombre }}" style="height: 180px; object-fit: cover;">
                                        @else
                                            <div class="bg-secondary d-flex justify-content-center align-items-center" style="height: 180px;">
                                                <i class="fas fa-camera fa-3x text-light"></i>
                                            </div>
                                        @endif
                                        <div class="card-body">
                                            <!-- Badge Trazabilidad Orgánico -->
                                            @if($organico->trazabilidad)
                                                <span class="badge badge-warning mb-2 w-100" title="Cuenta con Trazabilidad desde Finca">
                                                    <i class="fas fa-search-location"></i> Origen: {{ $organico->trazabilidad->finca }}
                                                </span>
                                            @endif
                                            
                                            <h5 class="card-title font-weight-bold w-100 mb-2">{{ $organico->nombre }}</h5>
                                            <p class="card-text text-success font-weight-bold mb-1">Bs. {{ number_format($organico->precio, 2) }}</p>
                                        </div>
                                        <div class="card-footer bg-white border-top-0 pt-0">
                                            <a href="{{ route('organicos.show', $organico->id) }}" class="btn btn-outline-success btn-block btn-sm">Ver Detalles</a>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    
                    <!-- TAB MAQUINARIAS -->
                    <div class="tab-pane fade" id="tabs-maquinarias" role="tabpanel" aria-labelledby="tabs-maquinarias-tab">
                        @if($productor->maquinarias->isEmpty())
                            <div class="alert alert-light text-center py-5">
                                <i class="fas fa-folder-open text-muted fa-3x mb-3"></i>
                                <h5>Este productor no tiene maquinaria publicada aún.</h5>
                            </div>
                        @else
                            <div class="row">
                                @foreach($productor->maquinarias as $maquinaria)
                                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                                    <div class="card h-100 shadow-sm border-0 product-card">
                                        @if($maquinaria->imagenes->first())
                                            <img src="{{ asset('storage/' . $maquinaria->imagenes->first()->ruta) }}" class="card-img-top" alt="{{ $maquinaria->nombre }}" style="height: 180px; object-fit: cover;">
                                        @else
                                            <div class="bg-secondary d-flex justify-content-center align-items-center" style="height: 180px;">
                                                <i class="fas fa-camera fa-3x text-light"></i>
                                            </div>
                                        @endif
                                        <div class="card-body">
                                            @if($maquinaria->marca)
                                                <span class="badge badge-dark mb-2 w-100">{{ $maquinaria->marca->nombre }}</span>
                                            @endif
                                            <h5 class="card-title font-weight-bold w-100 mb-2">{{ $maquinaria->nombre }}</h5>
                                            <p class="card-text text-success font-weight-bold mb-1">Bs. {{ number_format($maquinaria->precio, 2) }}</p>
                                        </div>
                                        <div class="card-footer bg-white border-top-0 pt-0">
                                            <a href="{{ route('maquinarias.show', $maquinaria->id) }}" class="btn btn-outline-success btn-block btn-sm">Ver Detalles</a>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    
                </div>
            </div>
            <!-- /.card -->
        </div>
    </div>
</div>

<style>
.product-card {
    transition: transform 0.2s, box-shadow 0.2s;
}
.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
}
</style>
@endsection
