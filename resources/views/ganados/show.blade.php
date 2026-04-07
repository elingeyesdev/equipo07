@extends('layouts.adminlte')

@section('title', 'Detalle de Ganado')

@section('content')
    <div class="container-fluid">

        {{-- ESTILOS OPTIMIZADOS Y PROFESIONALES --}}
        <style>
            .container-fluid {
                padding-left: 1rem;
                padding-right: 1rem;
            }

            .card {
                margin-bottom: 0.75rem !important;
                border-radius: 8px;
                transition: all 0.3s ease;
            }

            .card:hover {
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1) !important;
            }

            .card-header {
                padding: 0.75rem 1rem;
                font-size: 0.95rem;
            }

            .card-body {
                padding: 1rem;
            }

            .card-body .row {
                margin-left: -0.5rem;
                margin-right: -0.5rem;
            }

            .card-body .row>[class*="col-"] {
                padding-left: 0.5rem;
                padding-right: 0.5rem;
            }

            .badge-lg {
                font-size: 0.8rem;
                padding: 0.35rem 0.6rem;
            }

            .bg-success-light {
                background-color: #d4edda !important;
            }

            .info-item {
                margin-bottom: 0.75rem;
            }

            .info-item:last-child {
                margin-bottom: 0;
            }

            .img-preview-inline {
                display: inline-block;
                vertical-align: middle;
                margin-left: 0.5rem;
            }

            .btn-inline-img {
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
            }

            .info-icon {
                font-size: 1.5rem !important;
                width: 40px;
                text-align: center;
            }

            .info-row-item {
                margin-bottom: 0.75rem;
            }

            .info-row-item:last-child {
                margin-bottom: 0;
            }

            .section-title {
                font-size: 0.9rem;
                font-weight: 600;
                margin-bottom: 0.5rem;
            }

            .info-value {
                font-size: 0.95rem;
                line-height: 1.4;
            }

            .compact-spacing {
                margin-bottom: 0.5rem !important;
            }

            .compact-spacing:last-child {
                margin-bottom: 0 !important;
            }

            .gap-2 {
                gap: 0.5rem;
            }

            @media (max-width: 768px) {
                .card-body {
                    padding: 0.75rem;
                }

                .info-icon {
                    font-size: 1.25rem !important;
                    width: 35px;
                }
            }
        </style>

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h1 class="h4 mb-0 text-dark">
                    <i class="fas fa-cow text-success"></i> Detalle del Ganado
                </h1>
            </div>
            <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('ganados.index') }}"
                class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>

        <div class="row">
            <!-- Columna Izquierda: Galería y Contenido Principal -->
            <div class="col-lg-8">
                <!-- Galería de Imágenes -->
                <div class="card shadow-sm border-0 mb-3">
                    <div class="card-body p-0">
                        @if ($ganado->imagenes && $ganado->imagenes->count() > 0)
                            <div class="position-relative bg-white d-flex justify-content-center align-items-center"
                                style="height: 400px; border-radius: 8px;">
                                <img id="imagen-principal" src="{{ asset('storage/' . $ganado->imagenes->first()->ruta) }}"
                                    alt="{{ $ganado->nombre }}"
                                    style="max-height: 100%; max-width: 100%; object-fit: contain; cursor: pointer;"
                                    data-toggle="modal" data-target="#imageModal"
                                    onclick="document.getElementById('imageModalImg').src = this.src"
                                    title="Click para ver imagen completa">
                                <div class="position-absolute" style="top:10px; right:10px;">
                                    <span class="badge badge-success badge-lg">
                                        <i class="fas fa-image"></i> Click para ampliar
                                    </span>
                                </div>
                            </div>

                            @if ($ganado->imagenes->count() > 1)
                                <div class="p-2">
                                    <div class="row no-gutters">
                                        @foreach ($ganado->imagenes as $imagen)
                                            <div class="col-3 pr-1">
                                                <div class="bg-white border rounded d-flex align-items-center justify-content-center"
                                                    style="height: 80px; cursor: pointer; transition: all 0.2s;"
                                                    onclick="
                                                    document.getElementById('imagen-principal').src = '{{ asset('storage/' . $imagen->ruta) }}';
                                                    document.getElementById('imageModalImg').src = '{{ asset('storage/' . $imagen->ruta) }}';
                                                 "
                                                    onmouseover="this.style.borderColor='#28a745'; this.style.transform='scale(1.05)'"
                                                    onmouseout="this.style.borderColor='#dee2e6'; this.style.transform='scale(1)'">
                                                    <img src="{{ asset('storage/' . $imagen->ruta) }}"
                                                        alt="Imagen {{ $loop->iteration }}"
                                                        style="max-height: 100%; max-width: 100%; object-fit: contain;">
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @elseif($ganado->imagen)
                            <div class="position-relative bg-white d-flex justify-content-center align-items-center"
                                style="height: 400px; border-radius: 8px;">
                                <img src="{{ asset('storage/' . $ganado->imagen) }}" alt="Imagen de {{ $ganado->nombre }}"
                                    style="max-height: 100%; max-width: 100%; object-fit: contain; cursor: pointer;"
                                    onclick="window.open('{{ asset('storage/' . $ganado->imagen) }}', '_blank')"
                                    title="Click para ver imagen completa">
                                <div class="position-absolute" style="top:10px; right:10px;">
                                    <span class="badge badge-success badge-lg">
                                        <i class="fas fa-image"></i> Click para ampliar
                                    </span>
                                </div>
                            </div>
                        @else
                            <div class="d-flex align-items-center justify-content-center bg-light" style="height: 400px;">
                                <div class="text-center text-muted">
                                    <i class="fas fa-image fa-4x mb-3"></i>
                                    <p class="mb-0">Sin imágenes disponibles</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Información Básica -->
                <div class="card shadow-sm border-0 mb-3">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-cow"></i> Información Básica
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 info-row-item">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-dna text-primary info-icon"></i>
                                    <div class="flex-grow-1">
                                        <small class="text-muted d-block mb-0 section-title">Raza</small>
                                        <strong
                                            class="d-block info-value">{{ $ganado->raza->nombre ?? 'No especificada' }}</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 info-row-item">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-birthday-cake text-warning info-icon"></i>
                                    <div class="flex-grow-1">
                                        <small class="text-muted d-block mb-0 section-title">Edad</small>
                                        <strong class="d-block info-value">{{ $ganado->edad ?? '—' }} meses</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 info-row-item">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-weight text-info info-icon"></i>
                                    <div class="flex-grow-1">
                                        <small class="text-muted d-block mb-0 section-title">Tipo de Peso</small>
                                        <strong class="d-block info-value">{{ $ganado->tipoPeso->nombre ?? '—' }}</strong>
                                    </div>
                                </div>
                            </div>
                            @if ($ganado->peso_actual)
                                <div class="col-md-6 info-row-item">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-weight-hanging text-success info-icon"></i>
                                        <div class="flex-grow-1">
                                            <small class="text-muted d-block mb-0 section-title">Peso Actual</small>
                                            <strong class="d-block info-value">{{ number_format($ganado->peso_actual, 2) }}
                                                KG</strong>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class="col-md-6 info-row-item">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-paw text-info info-icon"></i>
                                    <div class="flex-grow-1">
                                        <small class="text-muted d-block mb-0 section-title">Tipo de Animal</small>
                                        <strong
                                            class="d-block info-value">{{ $ganado->tipoAnimal->nombre ?? '—' }}</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 info-row-item">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-venus-mars text-danger info-icon"></i>
                                    <div class="flex-grow-1">
                                        <small class="text-muted d-block mb-0 section-title">Sexo</small>
                                        <strong class="d-block info-value">{{ $ganado->sexo ?? '—' }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if ($ganado->descripcion)
                            <div class="mt-2 pt-2 border-top">
                                <small class="text-muted d-block mb-1 section-title">
                                    <i class="fas fa-align-left"></i> Descripción
                                </small>
                                <p class="mb-0 info-value">{{ $ganado->descripcion }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Datos Sanitarios -->
                @if ($ganado->datoSanitario)
                    <div class="card shadow-sm border-0 mb-3">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-syringe"></i> Datos Sanitarios
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @if ($ganado->datoSanitario->vacuna)
                                    <div class="col-md-6 info-row-item">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-vial text-success info-icon"></i>
                                            <div class="flex-grow-1">
                                                <small class="text-muted d-block mb-0 section-title">Otras Vacunas</small>
                                                <strong
                                                    class="d-block info-value">{{ $ganado->datoSanitario->vacuna }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if ($ganado->datoSanitario->vacunado_fiebre_aftosa || $ganado->datoSanitario->vacunado_antirabica)
                                    <div class="col-12 info-row-item">
                                        <small class="text-muted d-block mb-1 section-title">Vacunaciones
                                            Específicas</small>
                                        <div class="d-flex flex-wrap gap-2">
                                            @if ($ganado->datoSanitario->vacunado_fiebre_aftosa)
                                                <span class="badge badge-success">
                                                    <i class="fas fa-shield-alt"></i> Fiebre Aftosa
                                                </span>
                                            @endif
                                            @if ($ganado->datoSanitario->vacunado_antirabica)
                                                <span class="badge badge-success">
                                                    <i class="fas fa-shield-alt"></i> Antirrábica
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                                @if ($ganado->datoSanitario->tratamiento)
                                    <div class="col-md-6 info-row-item">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-stethoscope text-info info-icon"></i>
                                            <div class="flex-grow-1">
                                                <small class="text-muted d-block mb-0 section-title">Tratamiento</small>
                                                <strong
                                                    class="d-block info-value">{{ $ganado->datoSanitario->tratamiento }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if ($ganado->datoSanitario->medicamento)
                                    <div class="col-md-6 info-row-item">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-capsules text-info info-icon"></i>
                                            <div class="flex-grow-1">
                                                <small class="text-muted d-block mb-0 section-title">Medicamento</small>
                                                <strong
                                                    class="d-block info-value">{{ $ganado->datoSanitario->medicamento }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if ($ganado->datoSanitario->fecha_aplicacion)
                                    <div class="col-md-6 info-row-item">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-calendar-check text-info info-icon"></i>
                                            <div class="flex-grow-1">
                                                <small class="text-muted d-block mb-0 section-title">Fecha de
                                                    Aplicación</small>
                                                <strong
                                                    class="d-block info-value">{{ \Carbon\Carbon::parse($ganado->datoSanitario->fecha_aplicacion)->format('d/m/Y') }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if ($ganado->datoSanitario->proxima_fecha)
                                    <div class="col-md-6 info-row-item">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-calendar-alt text-warning info-icon"></i>
                                            <div class="flex-grow-1">
                                                <small class="text-muted d-block mb-0 section-title">Próxima Fecha</small>
                                                <strong
                                                    class="d-block info-value">{{ \Carbon\Carbon::parse($ganado->datoSanitario->proxima_fecha)->format('d/m/Y') }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if ($ganado->datoSanitario->veterinario)
                                    <div class="col-md-6 info-row-item">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-user-md text-primary info-icon"></i>
                                            <div class="flex-grow-1">
                                                <small class="text-muted d-block mb-0 section-title">Veterinario</small>
                                                <strong
                                                    class="d-block info-value">{{ $ganado->datoSanitario->veterinario }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if ($ganado->datoSanitario->observaciones)
                                    <div class="col-12 info-row-item">
                                        <div class="d-flex align-items-start">
                                            <i class="fas fa-comment-alt text-info info-icon mt-1"></i>
                                            <div class="flex-grow-1">
                                                <small class="text-muted d-block mb-1 section-title">Observaciones</small>
                                                <p class="mb-0 info-value">{{ $ganado->datoSanitario->observaciones }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Certificado de Vacunación SENASAG -->
                @if ($ganado->datoSanitario && $ganado->datoSanitario->certificado_imagen)
                    <div class="card shadow-sm border-0 mb-3">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-file-medical"></i> Certificado de Vacunación SENASAG
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="btn-inline-img">
                                <a href="{{ asset('storage/' . $ganado->datoSanitario->certificado_imagen) }}"
                                    target="_blank" class="btn btn-success btn-sm">
                                    <i class="fas fa-file-image"></i> Ver Certificado
                                </a>
                                <img src="{{ asset('storage/' . $ganado->datoSanitario->certificado_imagen) }}"
                                    alt="Certificado SENASAG" class="img-thumbnail img-preview-inline"
                                    style="max-width: 120px; max-height: 80px; cursor: pointer; object-fit: cover;"
                                    onclick="window.open('{{ asset('storage/' . $ganado->datoSanitario->certificado_imagen) }}', '_blank')"
                                    title="Click para ver imagen completa">
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Certificado de Campeón -->
                @if ($ganado->datoSanitario && $ganado->datoSanitario->certificado_campeon_imagen)
                    <div class="card shadow-sm border-0 mb-3">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-trophy"></i> Certificado de Campeón
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="btn-inline-img">
                                <a href="{{ asset('storage/' . $ganado->datoSanitario->certificado_campeon_imagen) }}"
                                    target="_blank" class="btn btn-success btn-sm">
                                    <i class="fas fa-trophy"></i> Ver Certificado Campeón
                                </a>
                                <img src="{{ asset('storage/' . $ganado->datoSanitario->certificado_campeon_imagen) }}"
                                    alt="Certificado Campeón" class="img-thumbnail img-preview-inline"
                                    style="max-width: 120px; max-height: 80px; cursor: pointer; object-fit: cover;"
                                    onclick="window.open('{{ asset('storage/' . $ganado->datoSanitario->certificado_campeon_imagen) }}', '_blank')"
                                    title="Click para ver imagen completa">
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Logros y Reconocimientos --}}
                @php
                    $tieneLogros = false;
                    $logrosBelleza = [];
                    $logrosLeche = [];
                    $logrosCarne = [];
                    $logrosReproduccion = [];

                    if ($ganado->datoSanitario) {
                        if ($ganado->datoSanitario->logro_campeon_raza) {
                            $logrosBelleza[] = 'Campeón de Raza';
                            $tieneLogros = true;
                        }
                        if ($ganado->datoSanitario->logro_gran_campeon_macho) {
                            $logrosBelleza[] = 'Gran Campeón Macho';
                            $tieneLogros = true;
                        }
                        if ($ganado->datoSanitario->logro_gran_campeon_hembra) {
                            $logrosBelleza[] = 'Gran Campeón Hembra';
                            $tieneLogros = true;
                        }
                        if ($ganado->datoSanitario->logro_mejor_ubre) {
                            $logrosBelleza[] = 'Mejor Ubre';
                            $tieneLogros = true;
                        }

                        if ($ganado->datoSanitario->logro_campeona_litros_dia) {
                            $logrosLeche[] = 'Campeona Litros/Día';
                            $tieneLogros = true;
                        }
                        if ($ganado->datoSanitario->logro_mejor_lactancia) {
                            $logrosLeche[] = 'Mejor Lactancia';
                            $tieneLogros = true;
                        }
                        if ($ganado->datoSanitario->logro_mejor_calidad_leche) {
                            $logrosLeche[] = 'Mejor Calidad de Leche';
                            $tieneLogros = true;
                        }

                        if ($ganado->datoSanitario->logro_mejor_novillo) {
                            $logrosCarne[] = 'Mejor Novillo';
                            $tieneLogros = true;
                        }
                        if ($ganado->datoSanitario->logro_gran_campeon_carne) {
                            $logrosCarne[] = 'Gran Campeón de Carne';
                            $tieneLogros = true;
                        }
                        if ($ganado->datoSanitario->logro_mejor_semental) {
                            $logrosCarne[] = 'Mejor Semental';
                            $tieneLogros = true;
                        }

                        if ($ganado->datoSanitario->logro_mejor_madre) {
                            $logrosReproduccion[] = 'Mejor Madre';
                            $tieneLogros = true;
                        }
                        if ($ganado->datoSanitario->logro_mejor_padre) {
                            $logrosReproduccion[] = 'Mejor Padre';
                            $tieneLogros = true;
                        }
                        if ($ganado->datoSanitario->logro_mejor_fertilidad) {
                            $logrosReproduccion[] = 'Mejor Fertilidad';
                            $tieneLogros = true;
                        }
                    }
                @endphp

                @if ($tieneLogros)
                    <div class="card shadow-sm border-0 mb-3">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0">
                                <i class="fas fa-trophy"></i> Logros y Reconocimientos
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @if (count($logrosBelleza) > 0)
                                    <div class="col-md-6 info-row-item">
                                        <small class="text-muted d-block mb-1 section-title">
                                            <i class="fas fa-star text-warning"></i> Belleza y Ganadería
                                        </small>
                                        @foreach ($logrosBelleza as $logro)
                                            <div class="mb-1">
                                                <i class="fas fa-trophy text-warning"></i>
                                                <span class="info-value">{{ $logro }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                                @if (count($logrosLeche) > 0)
                                    <div class="col-md-6 info-row-item">
                                        <small class="text-muted d-block mb-1 section-title">
                                            <i class="fas fa-tint text-info"></i> Producción de Leche
                                        </small>
                                        @foreach ($logrosLeche as $logro)
                                            <div class="mb-1">
                                                <i class="fas fa-trophy text-warning"></i>
                                                <span class="info-value">{{ $logro }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                                @if (count($logrosCarne) > 0)
                                    <div class="col-md-6 info-row-item">
                                        <small class="text-muted d-block mb-1 section-title">
                                            <i class="fas fa-drumstick-bite text-danger"></i> Producción de Carne
                                        </small>
                                        @foreach ($logrosCarne as $logro)
                                            <div class="mb-1">
                                                <i class="fas fa-trophy text-warning"></i>
                                                <span class="info-value">{{ $logro }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                                @if (count($logrosReproduccion) > 0)
                                    <div class="col-md-6 info-row-item">
                                        <small class="text-muted d-block mb-1 section-title">
                                            <i class="fas fa-heart text-danger"></i> Reproducción
                                        </small>
                                        @foreach ($logrosReproduccion as $logro)
                                            <div class="mb-1">
                                                <i class="fas fa-trophy text-warning"></i>
                                                <span class="info-value">{{ $logro }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Árbol Genealógico --}}
                @if ($ganado->datoSanitario && $ganado->datoSanitario->arbol_genealogico)
                    <div class="card shadow-sm border-0 mb-3">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-sitemap"></i> Árbol Genealógico
                            </h5>
                        </div>
                        <div class="card-body">
                            <a href="{{ asset('storage/' . $ganado->datoSanitario->arbol_genealogico) }}" target="_blank"
                                class="btn btn-success btn-sm">
                                <i class="fas fa-sitemap"></i> Ver Árbol Genealógico
                            </a>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Columna Derecha: Panel de Precio/Carrito, Fechas, Vendedor -->
            <div class="col-lg-4">
                <!-- Panel de Precio y Carrito -->
                <div class="card shadow-sm border-0 mb-3 border-left border-success"
                    style="border-left-width: 4px !important;">
                    <div class="card-body p-3">
                        <h2 class="h5 mb-2 text-dark">{{ $ganado->nombre }}</h2>

                        <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                            <span class="badge badge-success badge-lg">
                                <i class="fas fa-tag"></i> {{ $ganado->categoria->nombre ?? 'Sin categoría' }}
                            </span>
                            @if ($ganado->tipoAnimal)
                                <span class="badge badge-info badge-lg">
                                    <i class="fas fa-paw"></i> {{ $ganado->tipoAnimal->nombre }}
                                </span>
                            @endif
                            @if ($ganado->stock ?? 0 > 0)
                                <span class="badge badge-primary badge-lg">
                                    <i class="fas fa-box"></i> Stock: {{ $ganado->stock }}
                                </span>
                            @endif
                        </div>

                        @if ($ganado->precio)
                            <div class="bg-success-light p-2 rounded mb-2 border-left border-success"
                                style="border-left-width: 4px !important;">
                                <small class="text-muted d-block mb-0">Precio</small>
                                <h3 class="h5 mb-0 text-success font-weight-bold">
                                    Bs {{ number_format($ganado->precio, 2) }}
                                </h3>
                            </div>
                        @else
                            <div class="alert alert-info mb-2 py-2">
                                <i class="fas fa-info-circle"></i> <small>Precio a consultar</small>
                            </div>
                        @endif

                        @auth
                            @if ($ganado->precio && ($ganado->stock ?? 0) > 0)
                                <form action="{{ route('cart.add') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="product_type" value="ganado">
                                    <input type="hidden" name="product_id" value="{{ $ganado->id }}">
                                    <div class="mb-2">
                                        <label class="small font-weight-bold text-muted mb-1 d-block">Cantidad</label>
                                        <input type="number" name="cantidad" class="form-control form-control-sm"
                                            value="1" min="1" max="{{ $ganado->stock ?? 1 }}" required
                                            style="width: 100px;">
                                    </div>
                                    <button type="submit" class="btn btn-success btn-block shadow-sm">
                                        <i class="fas fa-cart-plus"></i> Agregar al Carrito
                                    </button>
                                </form>
                            @endif
                        @endauth
                    </div>
                </div>

                <!-- Fechas -->
                <div class="card shadow-sm border-0 mb-3">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-calendar-alt"></i> Fechas
                        </h5>
                    </div>
                    <div class="card-body">
                        @if ($ganado->fecha_publicacion)
                            <div>
                                <small class="text-muted d-block mb-0 section-title">Fecha de Publicación</small>
                                <strong
                                    class="info-value">{{ \Carbon\Carbon::parse($ganado->fecha_publicacion)->format('d/m/Y') }}</strong>
                            </div>
                        @else
                            <p class="text-muted mb-0 info-value">Sin fecha de publicación</p>
                        @endif
                    </div>
                </div>

                <!-- Información del Vendedor -->
                @if ($ganado->user)
                    <div class="card shadow-sm border-0 border-success" style="border-width: 3px !important;">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0 font-weight-bold">
                                <i class="fas fa-user"></i> Información del Vendedor
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="info-row-item">
                                <h6 class="mb-1 font-weight-bold text-dark">{{ $ganado->user->name }}</h6>
                                <small class="text-muted d-block info-value">
                                    <i class="fas fa-envelope text-success"></i> {{ $ganado->user->email }}
                                </small>
                            </div>

                            <div class="border-top pt-2">
                                @if ($ganado->user->role)
                                    <div class="info-row-item">
                                        <small class="text-muted d-block mb-1 section-title">Tipo de Usuario</small>
                                        @php
                                            $roleName =
                                                $ganado->user->role->nombre ?? ($ganado->user->role_name ?? 'Cliente');
                                            $badgeClass = 'badge-secondary';
                                            if ($roleName === 'Administrador' || $roleName === 'admin') {
                                                $badgeClass = 'badge-danger';
                                            } elseif ($roleName === 'Vendedor' || $roleName === 'vendedor') {
                                                $badgeClass = 'badge-success';
                                            }
                                        @endphp
                                        <span class="badge {{ $badgeClass }} badge-lg">
                                            <i class="fas fa-user-tag"></i> {{ $roleName }}
                                        </span>
                                    </div>
                                @endif

                                @if ($ganado->user->created_at)
                                    <div class="info-row-item">
                                        <small class="text-muted d-block mb-0 section-title">Miembro Desde</small>
                                        <strong class="info-value">
                                            <i class="fas fa-calendar-check text-success"></i>
                                            {{ \Carbon\Carbon::parse($ganado->user->created_at)->format('d/m/Y') }}
                                        </strong>
                                    </div>
                                @endif
                            </div>

                            @auth
                                @if (auth()->id() !== $ganado->user_id)
                                    <div class="mt-2 pt-2 border-top">
                                        <a href="mailto:{{ $ganado->user->email }}"
                                            class="btn btn-success btn-sm btn-block shadow-sm">
                                            <i class="fas fa-envelope mr-1"></i> Contactar Vendedor
                                        </a>
                                    </div>
                                @endif
                            @else
                                <div class="mt-2 pt-2 border-top">
                                    <a href="{{ route('login') }}" class="btn btn-outline-success btn-sm btn-block">
                                        <i class="fas fa-sign-in-alt mr-1"></i> Inicia sesión para contactar
                                    </a>
                                </div>
                            @endauth
                        </div>
                    </div>
                @endif

                {{-- Marca del Animal --}}
                @if (
                    $ganado->datoSanitario &&
                        ($ganado->datoSanitario->marca_ganado ||
                            $ganado->datoSanitario->senal_numero ||
                            $ganado->datoSanitario->marca_ganado_foto))
                    <div class="card shadow-sm border-0 mb-3">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-tag"></i> Marca del Animal
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @if ($ganado->datoSanitario->marca_ganado)
                                    <div class="col-12 info-row-item">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-tag text-primary info-icon"></i>
                                            <div class="flex-grow-1">
                                                <small class="text-muted d-block mb-0 section-title">Marca del
                                                    Ganado</small>
                                                <strong
                                                    class="d-block info-value">{{ $ganado->datoSanitario->marca_ganado }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if ($ganado->datoSanitario->senal_numero)
                                    <div class="col-12 info-row-item">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-hashtag text-primary info-icon"></i>
                                            <div class="flex-grow-1">
                                                <small class="text-muted d-block mb-0 section-title">Señal o #</small>
                                                <strong
                                                    class="d-block info-value">{{ $ganado->datoSanitario->senal_numero }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if ($ganado->datoSanitario->marca_ganado_foto)
                                    <div class="col-12 info-row-item">
                                        <small class="text-muted d-block mb-1 section-title">Foto de la Marca</small>
                                        <div class="btn-inline-img">
                                            <a href="{{ asset('storage/' . $ganado->datoSanitario->marca_ganado_foto) }}"
                                                target="_blank" class="btn btn-success btn-sm">
                                                <i class="fas fa-image"></i> Ver Foto
                                            </a>
                                            <img src="{{ asset('storage/' . $ganado->datoSanitario->marca_ganado_foto) }}"
                                                alt="Foto de la Marca" class="img-thumbnail img-preview-inline"
                                                style="max-width: 100px; max-height: 70px; cursor: pointer; object-fit: cover;"
                                                onclick="window.open('{{ asset('storage/' . $ganado->datoSanitario->marca_ganado_foto) }}', '_blank')"
                                                title="Click para ver imagen completa">
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Información del Dueño --}}
                @if ($ganado->datoSanitario && ($ganado->datoSanitario->nombre_dueno || $ganado->datoSanitario->carnet_dueno_foto))
                    <div class="card shadow-sm border-0 mb-3">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-user"></i> Información del Dueño
                            </h5>
                        </div>
                        <div class="card-body">
                            @if ($ganado->datoSanitario->nombre_dueno)
                                <div class="info-row-item">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-info rounded-circle d-flex align-items-center justify-content-center mr-3"
                                            style="width: 40px; height: 40px;">
                                            <i class="fas fa-user text-white"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <small class="text-muted d-block mb-0 section-title">Nombre del Dueño</small>
                                            <strong
                                                class="d-block info-value">{{ $ganado->datoSanitario->nombre_dueno }}</strong>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if ($ganado->datoSanitario->carnet_dueno_foto)
                                <div class="info-row-item">
                                    <small class="text-muted d-block mb-1 section-title">Carnet del Dueño</small>
                                    <div class="btn-inline-img">
                                        <a href="{{ asset('storage/' . $ganado->datoSanitario->carnet_dueno_foto) }}"
                                            target="_blank" class="btn btn-success btn-sm">
                                            <i class="fas fa-id-card"></i> Ver Carnet
                                        </a>
                                        <img src="{{ asset('storage/' . $ganado->datoSanitario->carnet_dueno_foto) }}"
                                            alt="Carnet Dueño" class="img-thumbnail img-preview-inline"
                                            style="max-width: 100px; max-height: 70px; cursor: pointer; object-fit: cover;"
                                            onclick="window.open('{{ asset('storage/' . $ganado->datoSanitario->carnet_dueno_foto) }}', '_blank')"
                                            title="Click para ver imagen completa">
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- Ubicación --}}
                <div class="card shadow-sm border-0 mb-3">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-map-marker-alt"></i> Ubicación
                        </h5>
                    </div>
                    <div class="card-body">
                        @if ($ganado->ciudad || $ganado->municipio || $ganado->departamento)
                            <div class="info-row-item">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-building text-danger info-icon"></i>
                                    <div class="flex-grow-1">
                                        <small class="text-muted d-block mb-0 section-title">Ciudad</small>
                                        <strong
                                            class="d-block info-value">{{ $ganado->ciudad ?? ($ganado->municipio ?? 'No disponible') }}</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="info-row-item">
                                <small class="text-muted d-block mb-1 section-title">Dirección</small>
                                @php
                                    $direccion = [];
                                    if ($ganado->municipio) {
                                        $direccion[] = $ganado->municipio;
                                    }
                                    if ($ganado->provincia) {
                                        $direccion[] = 'Provincia ' . $ganado->provincia;
                                    }
                                    if ($ganado->departamento) {
                                        $direccion[] = $ganado->departamento;
                                    }
                                    $direccion[] = 'Bolivia';
                                    $direccionCompleta = implode(', ', $direccion);
                                @endphp
                                <strong class="info-value">{{ $direccionCompleta }}</strong>
                            </div>
                        @elseif($ganado->ubicacion)
                            <div class="info-row-item">
                                <i class="fas fa-location-dot text-danger info-icon"></i>
                                <strong class="info-value">{{ $ganado->ubicacion }}</strong>
                            </div>
                        @else
                            <p class="text-muted mb-0 info-value">Sin ubicación especificada</p>
                        @endif
                        @if ($ganado->latitud && $ganado->longitud)
                            <div class="mt-2">
                                <button type="button" class="btn btn-danger btn-sm btn-block" data-toggle="modal"
                                    data-target="#mapModal">
                                    <i class="fas fa-map"></i> Ver Mapa
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal del Mapa -->
    @if ($ganado->latitud && $ganado->longitud)
        <div class="modal fade" id="mapModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-map-marker-alt text-danger"></i> Ubicación del Anuncio
                        </h5>
                        <button class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body p-0">
                        <div id="map-ganado-modal" style="height:500px; width:100%;"></div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Leaflet CSS --}}
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

        {{-- Leaflet JS --}}
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

        <script>
            // Esperar a que todo esté cargado
            window.addEventListener('load', function() {
                $(document).ready(function() {
                    let mapGanado = null;

                    @php
                        $popupText = $ganado->nombre;
                        if ($ganado->ciudad || $ganado->municipio) {
                            $popupText .= ' - ' . ($ganado->ciudad ?? $ganado->municipio);
                        }
                        if ($ganado->municipio || $ganado->provincia || $ganado->departamento) {
                            $direccion = [];
                            if ($ganado->municipio) {
                                $direccion[] = $ganado->municipio;
                            }
                            if ($ganado->provincia) {
                                $direccion[] = 'Provincia ' . $ganado->provincia;
                            }
                            if ($ganado->departamento) {
                                $direccion[] = $ganado->departamento;
                            }
                            $direccion[] = 'Bolivia';
                            $popupText .= ' - ' . implode(', ', $direccion);
                        } elseif ($ganado->ubicacion) {
                            $popupText .= ' - ' . $ganado->ubicacion;
                        }
                    @endphp

                    function initMap() {
                        if (typeof L === 'undefined') {
                            console.error('Leaflet no está disponible');
                            return false;
                        }

                        try {
                            mapGanado = L.map('map-ganado-modal').setView(
                                [{{ $ganado->latitud }}, {{ $ganado->longitud }}],
                                12
                            );

                            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                maxZoom: 19,
                                attribution: '© OpenStreetMap contributors'
                            }).addTo(mapGanado);

                            L.marker([{{ $ganado->latitud }}, {{ $ganado->longitud }}])
                                .addTo(mapGanado)
                                .bindPopup("{{ addslashes($popupText) }}");

                            return true;
                        } catch (error) {
                            console.error('Error al inicializar el mapa:', error);
                            return false;
                        }
                    }

                    $('#mapModal').on('shown.bs.modal', function() {
                        if (!mapGanado) {
                            // Esperar a que el modal esté completamente visible
                            setTimeout(function() {
                                if (!initMap()) {
                                    // Si falla, reintentar después de un momento
                                    setTimeout(initMap, 500);
                                }
                            }, 200);
                        } else {
                            // Si el mapa ya existe, solo invalidar el tamaño
                            setTimeout(function() {
                                mapGanado.invalidateSize();
                            }, 100);
                        }
                    });
                });
            });
        </script>
    @endif

    {{-- MODAL PARA VER IMAGEN EN GRANDE --}}
    <div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content bg-transparent border-0">
                <button type="button" class="close text-white ml-auto mr-2 mt-2" data-dismiss="modal"
                    aria-label="Close" style="font-size: 2rem; z-index: 1051;">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div class="modal-body p-0 text-center">
                    <img id="imageModalImg" src="" class="img-fluid rounded"
                        style="max-height: 80vh; object-fit: contain;">
                </div>
            </div>
        </div>
    </div>

@endsection
