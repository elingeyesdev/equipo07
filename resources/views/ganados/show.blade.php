@extends('layouts.adminlte')

@section('title', 'Detalle de Ganado')

@section('content')
    <div class="container-fluid product-detail-page">

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

            .detail-card {
                border: 0 !important;
                border-radius: 12px;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12) !important;
                overflow: hidden;
                transition: all 0.3s ease;
            }

            .detail-card:hover {
                box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15) !important;
                transform: translateY(-2px);
            }

            .card:hover {
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1) !important;
            }

            .card-header {
                padding: 0.75rem 1rem;
                font-size: 0.95rem;
            }

            .detail-card-header {
                border: 0;
                border-radius: 12px 12px 0 0;
                color: #fff;
                font-weight: 600;
                padding: 1rem 1.5rem;
            }

            .detail-card-header h5 {
                color: #fff;
                font-size: 1.15rem;
                font-weight: 600;
                line-height: 1.3;
            }

            .detail-card-header-primary {
                background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            }

            .detail-card-header-success {
                background: linear-gradient(135deg, #28a745 0%, #218838 100%);
            }

            .detail-card-header-danger {
                background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            }

            .detail-card-header-info {
                background: linear-gradient(135deg, #17a2b8 0%, #117a8b 100%);
            }

            .detail-card-header-warning {
                background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
            }

            .card-header.bg-primary,
            .card-header.bg-success,
            .card-header.bg-danger,
            .card-header.bg-info,
            .card-header.bg-warning {
                border: 0;
                border-radius: 12px 12px 0 0;
                color: #fff !important;
                font-weight: 600;
                padding: 1rem 1.5rem;
            }

            .card-header.bg-primary {
                background: linear-gradient(135deg, #007bff 0%, #0056b3 100%) !important;
            }

            .card-header.bg-success {
                background: linear-gradient(135deg, #28a745 0%, #218838 100%) !important;
            }

            .card-header.bg-danger {
                background: linear-gradient(135deg, #dc3545 0%, #c82333 100%) !important;
            }

            .card-header.bg-info {
                background: linear-gradient(135deg, #17a2b8 0%, #117a8b 100%) !important;
            }

            .card-header.bg-warning {
                background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%) !important;
            }

            .card-header.bg-primary h5,
            .card-header.bg-success h5,
            .card-header.bg-danger h5,
            .card-header.bg-info h5,
            .card-header.bg-warning h5 {
                color: #fff !important;
                font-size: 1.15rem;
                font-weight: 600;
                line-height: 1.3;
            }

            .detail-info-icon {
                width: 48px;
                height: 48px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0;
                font-size: 1.15rem;
                margin-right: 1rem;
            }

            .detail-info-icon-warning {
                background: linear-gradient(135deg, #fff8e1 0%, #ffecb3 100%);
                color: #f57c00;
            }

            .detail-info-icon-success {
                background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
                color: #28a745;
            }

            .detail-info-icon-danger {
                background: linear-gradient(135deg, #ffebee 0%, #ffcdd2 100%);
                color: #dc3545;
            }

            .detail-btn {
                border: 0;
                border-radius: 8px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
                font-weight: 600;
                padding: 0.75rem 1.5rem;
                transition: all 0.3s ease;
            }

            .detail-btn:hover {
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
                transform: translateY(-2px);
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
                align-items: center;
                background: #f8fbf7;
                border: 1px solid #e2eadf;
                border-radius: 10px;
                display: flex;
                flex-wrap: wrap;
                gap: .75rem;
                padding: .75rem;
                width: fit-content;
                max-width: 100%;
            }

            .doc-preview-btn {
                background: transparent;
                border: 1px solid #2f7d32;
                border-radius: 8px;
                color: #2f7d32;
                cursor: pointer;
                display: inline-flex;
                align-items: center;
                gap: .45rem;
                font-weight: 600;
                padding: .45rem .85rem;
                transition: all .2s ease;
            }

            .doc-preview-btn:hover {
                background: #2f7d32;
                color: #fff;
                text-decoration: none;
                transform: translateY(-1px);
            }

            .doc-preview-thumb {
                border: 1px solid #dbe5d7;
                border-radius: 8px;
                box-shadow: none;
                height: 64px;
                margin-left: 0;
                max-width: 96px;
                object-fit: cover;
                padding: 2px;
            }

            .image-preview-trigger {
                cursor: pointer;
            }

            .sanitary-panel-toggle {
                background: #ffffff !important;
                border: 1px solid rgba(255, 255, 255, .75) !important;
                border-radius: 8px;
                color: #205b34 !important;
                font-weight: 700;
                padding: .55rem .85rem;
            }

            .product-detail-page .sanitary-detail-card .sanitary-detail-header {
                background: linear-gradient(135deg, #238647 0%, #145c31 100%) !important;
                border-bottom: 0 !important;
                color: #ffffff !important;
            }

            .product-detail-page .sanitary-detail-card .sanitary-detail-header h5,
            .product-detail-page .sanitary-detail-card .sanitary-detail-header h5 i {
                color: #ffffff !important;
            }

            .sanitary-detail-card .card-body {
                background: #ffffff;
            }

            .sanitary-document-grid {
                display: grid;
                gap: .85rem;
                grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            }

            .sanitary-document-card {
                background: #f7fbf8;
                border: 1px solid #cfe3d4;
                border-radius: 8px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: .9rem;
                min-height: 112px;
                padding: .9rem;
            }

            .sanitary-document-card .section-title {
                color: #30483a !important;
                font-size: .78rem;
                letter-spacing: .02em;
                line-height: 1.25;
                text-transform: uppercase;
            }

            .sanitary-document-card .doc-preview-btn {
                background: #ffffff;
                justify-content: center;
                min-width: 150px;
                text-align: center;
            }

            .sanitary-document-card__thumb {
                border: 1px solid #dbe5d7;
                border-radius: 6px;
                height: 64px;
                width: 76px;
                object-fit: cover;
                flex: 0 0 auto;
            }

            .sanitary-document-card__icon {
                align-items: center;
                background: #fff;
                border: 1px solid #dbe5d7;
                border-radius: 6px;
                color: #dc3545;
                display: flex;
                height: 64px;
                justify-content: center;
                width: 76px;
                flex: 0 0 auto;
            }

            .protected-document-viewer {
                height: 78vh;
                overflow: hidden;
                user-select: none;
            }

            .protected-document-viewer iframe,
            .protected-document-viewer img {
                border: 0;
                height: 100%;
                width: 100%;
            }

            .protected-document-viewer img {
                object-fit: contain;
                pointer-events: none;
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
        <link rel="stylesheet" href="{{ asset('css/product-detail.css') }}">

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-3 product-detail-toolbar">
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
                <div class="card shadow-sm border-0 mb-3 product-gallery-card">
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
                <div class="card shadow-sm border-0 mb-3 product-information-card">
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

                {{-- Datos sanitarios, documentos y reconocimientos --}}
                @php
                    $datoSanitario = $ganado->datoSanitario;
                    $tieneLogros = false;
                    $logrosBelleza = [];
                    $logrosLeche = [];
                    $logrosCarne = [];
                    $logrosReproduccion = [];

                    if ($datoSanitario) {
                        if ($datoSanitario->logro_campeon_raza) {
                            $logrosBelleza[] = 'Campeón de Raza';
                            $tieneLogros = true;
                        }
                        if ($datoSanitario->logro_gran_campeon_macho) {
                            $logrosBelleza[] = 'Gran Campeón Macho';
                            $tieneLogros = true;
                        }
                        if ($datoSanitario->logro_gran_campeon_hembra) {
                            $logrosBelleza[] = 'Gran Campeón Hembra';
                            $tieneLogros = true;
                        }
                        if ($datoSanitario->logro_mejor_ubre) {
                            $logrosBelleza[] = 'Mejor Ubre';
                            $tieneLogros = true;
                        }

                        if ($datoSanitario->logro_campeona_litros_dia) {
                            $logrosLeche[] = 'Campeona Litros/Día';
                            $tieneLogros = true;
                        }
                        if ($datoSanitario->logro_mejor_lactancia) {
                            $logrosLeche[] = 'Mejor Lactancia';
                            $tieneLogros = true;
                        }
                        if ($datoSanitario->logro_mejor_calidad_leche) {
                            $logrosLeche[] = 'Mejor Calidad de Leche';
                            $tieneLogros = true;
                        }

                        if ($datoSanitario->logro_mejor_novillo) {
                            $logrosCarne[] = 'Mejor Novillo';
                            $tieneLogros = true;
                        }
                        if ($datoSanitario->logro_gran_campeon_carne) {
                            $logrosCarne[] = 'Gran Campeón de Carne';
                            $tieneLogros = true;
                        }
                        if ($datoSanitario->logro_mejor_semental) {
                            $logrosCarne[] = 'Mejor Semental';
                            $tieneLogros = true;
                        }

                        if ($datoSanitario->logro_mejor_madre) {
                            $logrosReproduccion[] = 'Mejor Madre';
                            $tieneLogros = true;
                        }
                        if ($datoSanitario->logro_mejor_padre) {
                            $logrosReproduccion[] = 'Mejor Padre';
                            $tieneLogros = true;
                        }
                        if ($datoSanitario->logro_mejor_fertilidad) {
                            $logrosReproduccion[] = 'Mejor Fertilidad';
                            $tieneLogros = true;
                        }
                    }

                    $documentosSanitarios = [];
                    if ($datoSanitario?->certificado_imagen) {
                        $documentosSanitarios[] = [
                            'titulo' => 'Certificado SENASAG',
                            'icono' => 'fas fa-file-medical',
                            'ruta' => asset('storage/' . $datoSanitario->certificado_imagen),
                            'tipo' => 'image',
                        ];
                    }
                    if ($datoSanitario?->certificado_campeon_imagen) {
                        $documentosSanitarios[] = [
                            'titulo' => 'Certificado de campeón',
                            'icono' => 'fas fa-trophy',
                            'ruta' => asset('storage/' . $datoSanitario->certificado_campeon_imagen),
                            'tipo' => 'image',
                        ];
                    }
                    if ($datoSanitario?->marca_ganado_foto) {
                        $documentosSanitarios[] = [
                            'titulo' => 'Foto de la marca',
                            'icono' => 'fas fa-image',
                            'ruta' => asset('storage/' . $datoSanitario->marca_ganado_foto),
                            'tipo' => 'image',
                        ];
                    }
                    if ($datoSanitario?->carnet_dueno_foto) {
                        $documentosSanitarios[] = [
                            'titulo' => 'Carnet del dueño',
                            'icono' => 'fas fa-id-card',
                            'ruta' => asset('storage/' . $datoSanitario->carnet_dueno_foto),
                            'tipo' => 'image',
                        ];
                    }
                    if ($datoSanitario?->arbol_genealogico) {
                        $rutaArbol = asset('storage/' . $datoSanitario->arbol_genealogico);
                        $documentosSanitarios[] = [
                            'titulo' => 'Árbol genealógico',
                            'icono' => 'fas fa-sitemap',
                            'ruta' => $rutaArbol,
                            'tipo' => str_ends_with(strtolower($datoSanitario->arbol_genealogico), '.pdf') ? 'pdf' : 'image',
                        ];
                    }
                @endphp

                @if ($datoSanitario)
                    <div class="card shadow-sm border-0 mb-3 sanitary-detail-card">
                        <div class="card-header sanitary-detail-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fas fa-shield-alt"></i> Datos sanitarios y certificados
                            </h5>
                            <button class="btn btn-light btn-sm sanitary-panel-toggle" type="button" data-toggle="collapse"
                                data-target="#sanitaryDetails" aria-expanded="false" aria-controls="sanitaryDetails">
                                <i class="fas fa-eye"></i> Ver apartado
                            </button>
                        </div>
                        <div id="sanitaryDetails" class="collapse">
                            <div class="card-body">
                                <div class="row">
                                    @if ($datoSanitario->vacuna)
                                        <div class="col-md-6 info-row-item">
                                            <small class="text-muted d-block mb-0 section-title">Otras Vacunas</small>
                                            <strong class="d-block info-value">{{ $datoSanitario->vacuna }}</strong>
                                        </div>
                                    @endif
                                    @if ($datoSanitario->vacunado_fiebre_aftosa || $datoSanitario->vacunado_antirabica)
                                        <div class="col-md-6 info-row-item">
                                            <small class="text-muted d-block mb-1 section-title">Vacunaciones</small>
                                            <div class="d-flex flex-wrap gap-2">
                                                @if ($datoSanitario->vacunado_fiebre_aftosa)
                                                    <span class="badge badge-success"><i class="fas fa-shield-alt"></i> Fiebre Aftosa</span>
                                                @endif
                                                @if ($datoSanitario->vacunado_antirabica)
                                                    <span class="badge badge-success"><i class="fas fa-shield-alt"></i> Antirrábica</span>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                    @if ($datoSanitario->tratamiento)
                                        <div class="col-md-6 info-row-item">
                                            <small class="text-muted d-block mb-0 section-title">Tratamiento</small>
                                            <strong class="d-block info-value">{{ $datoSanitario->tratamiento }}</strong>
                                        </div>
                                    @endif
                                    @if ($datoSanitario->medicamento)
                                        <div class="col-md-6 info-row-item">
                                            <small class="text-muted d-block mb-0 section-title">Medicamento</small>
                                            <strong class="d-block info-value">{{ $datoSanitario->medicamento }}</strong>
                                        </div>
                                    @endif
                                    @if ($datoSanitario->fecha_aplicacion)
                                        <div class="col-md-6 info-row-item">
                                            <small class="text-muted d-block mb-0 section-title">Fecha de Aplicación</small>
                                            <strong class="d-block info-value">{{ \Carbon\Carbon::parse($datoSanitario->fecha_aplicacion)->format('d/m/Y') }}</strong>
                                        </div>
                                    @endif
                                    @if ($datoSanitario->proxima_fecha)
                                        <div class="col-md-6 info-row-item">
                                            <small class="text-muted d-block mb-0 section-title">Próxima Fecha</small>
                                            <strong class="d-block info-value">{{ \Carbon\Carbon::parse($datoSanitario->proxima_fecha)->format('d/m/Y') }}</strong>
                                        </div>
                                    @endif
                                    @if ($datoSanitario->veterinario)
                                        <div class="col-md-6 info-row-item">
                                            <small class="text-muted d-block mb-0 section-title">Veterinario</small>
                                            <strong class="d-block info-value">{{ $datoSanitario->veterinario }}</strong>
                                        </div>
                                    @endif
                                    @if ($datoSanitario->marca_ganado || $datoSanitario->senal_numero)
                                        <div class="col-md-6 info-row-item">
                                            <small class="text-muted d-block mb-0 section-title">Identificación</small>
                                            <strong class="d-block info-value">
                                                {{ $datoSanitario->marca_ganado ?: 'Sin marca registrada' }}
                                                @if ($datoSanitario->senal_numero)
                                                    · Señal {{ $datoSanitario->senal_numero }}
                                                @endif
                                            </strong>
                                        </div>
                                    @endif
                                    @if ($datoSanitario->nombre_dueno)
                                        <div class="col-md-6 info-row-item">
                                            <small class="text-muted d-block mb-0 section-title">Dueño registrado</small>
                                            <strong class="d-block info-value">{{ $datoSanitario->nombre_dueno }}</strong>
                                        </div>
                                    @endif
                                    @if ($datoSanitario->observaciones)
                                        <div class="col-12 info-row-item">
                                            <small class="text-muted d-block mb-1 section-title">Observaciones</small>
                                            <p class="mb-0 info-value">{{ $datoSanitario->observaciones }}</p>
                                        </div>
                                    @endif
                                </div>

                                @if (count($documentosSanitarios))
                                    <div class="border-top pt-3 mt-2">
                                        <small class="text-muted d-block mb-2 section-title">Certificados y documentos</small>
                                        <div class="sanitary-document-grid">
                                            @foreach ($documentosSanitarios as $documento)
                                                <div class="sanitary-document-card">
                                                    <div class="min-width-0">
                                                        <small class="text-muted d-block section-title mb-1">{{ $documento['titulo'] }}</small>
                                                        <button type="button" class="doc-preview-btn"
                                                            data-protected-file-viewer
                                                            data-file-title="{{ $documento['titulo'] }}"
                                                            data-file-type="{{ $documento['tipo'] }}"
                                                            data-file-src="{{ $documento['ruta'] }}">
                                                            <i class="{{ $documento['icono'] }}"></i> Ver documento
                                                        </button>
                                                    </div>
                                                    @if ($documento['tipo'] === 'image')
                                                        <img src="{{ $documento['ruta'] }}" alt="{{ $documento['titulo'] }}"
                                                            class="sanitary-document-card__thumb" oncontextmenu="return false;">
                                                    @else
                                                        <span class="sanitary-document-card__icon">
                                                            <i class="fas fa-file-pdf fa-2x"></i>
                                                        </span>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                @if ($tieneLogros)
                                    <div class="border-top pt-3 mt-3">
                                        <small class="text-muted d-block mb-2 section-title">
                                            <i class="fas fa-trophy text-warning"></i> Logros y Reconocimientos
                                        </small>
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
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                @include('components.product-reviews', ['producto' => $ganado])
            </div>

            <!-- Columna Derecha: Panel de Precio/Carrito, Fechas, Vendedor -->
            <div class="col-lg-4">
                <!-- Panel de Precio y Carrito -->
                <div class="card shadow-sm border-0 mb-3 border-left border-success product-purchase-card"
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
                            <div class="bg-success-light p-2 rounded mb-2 border-left border-success product-price-box"
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
                <div class="card detail-card mb-3">
                    <div class="detail-card-header detail-card-header-primary">
                        <h5 class="mb-0">
                            <i class="fas fa-calendar-alt mr-2"></i> Fechas
                        </h5>
                    </div>
                    <div class="card-body">
                        @if ($ganado->fecha_publicacion)
                            <div class="d-flex align-items-center">
                                <div class="detail-info-icon detail-info-icon-warning">
                                    <i class="fas fa-calendar-check"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block mb-0 section-title">Fecha de Publicación</small>
                                    <strong
                                        class="info-value">{{ \Carbon\Carbon::parse($ganado->fecha_publicacion)->format('d/m/Y') }}</strong>
                                </div>
                            </div>
                        @else
                            <p class="text-muted mb-0 info-value">Sin fecha de publicación</p>
                        @endif
                    </div>
                </div>

                <!-- Información del Vendedor -->
                @if ($ganado->user)
                    <div class="card detail-card mb-3">
                        <div class="detail-card-header detail-card-header-success">
                            <h5 class="mb-0 font-weight-bold">
                                <i class="fas fa-user mr-2"></i> Información del Vendedor
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="info-row-item">
                                <div class="d-flex align-items-center">
                                    <div class="detail-info-icon detail-info-icon-success">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1 font-weight-bold text-dark">{{ $ganado->user->name }}</h6>
                                        <small class="text-muted d-block info-value">
                                            <i class="fas fa-envelope text-success"></i> {{ $ganado->user->email }}
                                        </small>
                                    </div>
                                </div>
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
                                            class="btn btn-success btn-block detail-btn">
                                            <i class="fas fa-envelope mr-1"></i> Contactar Vendedor
                                        </a>
                                    </div>
                                @endif
                            @else
                                <div class="mt-2 pt-2 border-top">
                                    <a href="{{ route('login') }}" class="btn btn-outline-success btn-block detail-btn">
                                        <i class="fas fa-sign-in-alt mr-1"></i> Inicia sesión para contactar
                                    </a>
                                </div>
                            @endauth
                        </div>
                    </div>
                @endif

                {{-- Ubicación --}}
                <div class="card detail-card mb-3">
                    <div class="detail-card-header detail-card-header-danger">
                        <h5 class="mb-0">
                            <i class="fas fa-map-marker-alt mr-2"></i> Ubicación
                        </h5>
                    </div>
                    <div class="card-body">
                        @if ($ganado->ciudad || $ganado->municipio || $ganado->departamento)
                            <div class="info-row-item">
                                <div class="d-flex align-items-center">
                                    <div class="detail-info-icon detail-info-icon-danger">
                                        <i class="fas fa-building"></i>
                                    </div>
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
                                <div class="d-flex align-items-center">
                                    <div class="detail-info-icon detail-info-icon-danger">
                                        <i class="fas fa-location-dot"></i>
                                    </div>
                                    <strong class="info-value">{{ $ganado->ubicacion }}</strong>
                                </div>
                            </div>
                        @else
                            <p class="text-muted mb-0 info-value">Sin ubicación especificada</p>
                        @endif
                        @if ($ganado->latitud && $ganado->longitud)
                            <div class="mt-2">
                                <button type="button" class="btn btn-danger btn-block detail-btn" data-toggle="modal"
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

    {{-- MODAL PARA CERTIFICADOS Y DOCUMENTOS SIN DESCARGA DIRECTA --}}
    <div class="modal fade" id="protectedDocumentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="protectedDocumentTitle">
                        <i class="fas fa-file-alt text-success"></i> Documento
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-2">
                    <div class="protected-document-viewer" id="protectedDocumentViewer" oncontextmenu="return false;"></div>
                </div>
            </div>
        </div>
    </div>

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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const viewer = document.getElementById('protectedDocumentViewer');
            const title = document.getElementById('protectedDocumentTitle');

            document.querySelectorAll('[data-protected-file-viewer]').forEach(function(button) {
                button.addEventListener('click', function() {
                    const fileSrc = button.dataset.fileSrc;
                    const fileType = button.dataset.fileType;
                    const fileTitle = button.dataset.fileTitle || 'Documento';

                    title.innerHTML = `<i class="fas fa-file-alt text-success"></i> ${fileTitle}`;
                    viewer.innerHTML = '';

                    if (fileType === 'pdf') {
                        const iframe = document.createElement('iframe');
                        iframe.src = `${fileSrc}#toolbar=0&navpanes=0&scrollbar=1`;
                        iframe.setAttribute('title', fileTitle);
                        iframe.setAttribute('loading', 'lazy');
                        viewer.appendChild(iframe);
                    } else {
                        const image = document.createElement('img');
                        image.src = fileSrc;
                        image.alt = fileTitle;
                        image.setAttribute('draggable', 'false');
                        viewer.appendChild(image);
                    }

                    $('#protectedDocumentModal').modal('show');
                });
            });

            $('#protectedDocumentModal').on('hidden.bs.modal', function() {
                viewer.innerHTML = '';
            });
        });
    </script>

@endsection
