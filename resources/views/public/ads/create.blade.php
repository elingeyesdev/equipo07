@extends('layouts.public')
@section('title', 'Nuevo registro')

@section('content')
    <div class="container py-4">
        <div class="mb-4">
            <h4 class="text-success mb-1">Selecciona el tipo de registro</h4>
            <p class="text-muted mb-0">Elige el modulo donde quieres publicar tu anuncio.</p>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <a href="{{ route('maquinarias.create') }}" class="card h-100 shadow-sm text-decoration-none text-dark">
                    <div class="card-body">
                        <div class="text-success mb-3" style="font-size: 2rem;">
                            <i class="fas fa-tractor"></i>
                        </div>
                        <h5 class="font-weight-bold">Maquinaria</h5>
                        <p class="text-muted mb-0">Equipos, herramientas y maquinaria agricola.</p>
                    </div>
                </a>
            </div>

            <div class="col-md-4 mb-3">
                <a href="{{ route('ganados.create') }}" class="card h-100 shadow-sm text-decoration-none text-dark">
                    <div class="card-body">
                        <div class="text-success mb-3" style="font-size: 2rem;">
                            <i class="fas fa-cow"></i>
                        </div>
                        <h5 class="font-weight-bold">Animales</h5>
                        <p class="text-muted mb-0">Ganado y animales disponibles para venta.</p>
                    </div>
                </a>
            </div>

            <div class="col-md-4 mb-3">
                <a href="{{ route('organicos.create') }}" class="card h-100 shadow-sm text-decoration-none text-dark">
                    <div class="card-body">
                        <div class="text-success mb-3" style="font-size: 2rem;">
                            <i class="fas fa-leaf"></i>
                        </div>
                        <h5 class="font-weight-bold">Organicos</h5>
                        <p class="text-muted mb-0">Productos organicos con trazabilidad y certificados.</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection
