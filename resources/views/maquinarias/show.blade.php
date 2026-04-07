@extends('layouts.adminlte')

@section('title', 'Detalle de Maquinaria')

@section('content')
    <div class="container-fluid">
        {{-- ESTILOS PROFESIONALES Y MODERNOS --}}
        <style>
            :root {
                --primary-color: #007bff;
                --success-color: #28a745;
                --warning-color: #ffc107;
                --danger-color: #dc3545;
                --info-color: #17a2b8;
                --dark-color: #343a40;
                --light-bg: #f8f9fa;
                --shadow-sm: 0 2px 4px rgba(0,0,0,0.08);
                --shadow-md: 0 4px 12px rgba(0,0,0,0.12);
                --shadow-lg: 0 8px 24px rgba(0,0,0,0.15);
                --border-radius: 12px;
                --transition: all 0.3s ease;
            }

            .page-header {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                border-radius: var(--border-radius);
                padding: 2rem;
                margin-bottom: 2rem;
                color: white;
                box-shadow: var(--shadow-md);
            }

            .page-header h1 {
                font-size: 2rem;
                font-weight: 700;
                margin-bottom: 0.5rem;
                text-shadow: 0 2px 4px rgba(0,0,0,0.2);
            }

            .page-header p {
                font-size: 1rem;
                opacity: 0.95;
                margin: 0;
            }

            .product-card {
                border-radius: var(--border-radius);
                border: none;
                box-shadow: var(--shadow-md);
                transition: var(--transition);
                overflow: hidden;
            }

            .product-card:hover {
                box-shadow: var(--shadow-lg);
                transform: translateY(-2px);
            }

            .image-container {
                position: relative;
                background: #fff;
                border-radius: var(--border-radius);
                overflow: hidden;
                box-shadow: var(--shadow-sm);
                transition: var(--transition);
            }

            .image-container:hover {
                box-shadow: var(--shadow-md);
            }

            .main-image {
                width: 100%;
                height: 450px;
                object-fit: contain;
                background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
                cursor: pointer;
                transition: var(--transition);
            }

            .main-image:hover {
                transform: scale(1.02);
            }

            .thumbnail-container {
                background: white;
                border: 2px solid #e9ecef;
                border-radius: 8px;
                padding: 8px;
                cursor: pointer;
                transition: var(--transition);
                height: 100px;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .thumbnail-container:hover {
                border-color: var(--primary-color);
                transform: translateY(-3px);
                box-shadow: var(--shadow-sm);
            }

            .thumbnail-container img {
                max-height: 100%;
                max-width: 100%;
                object-fit: contain;
            }

            .info-card {
                background: white;
                border-radius: var(--border-radius);
                box-shadow: var(--shadow-md);
                transition: var(--transition);
                border: none;
            }

            .info-card:hover {
                box-shadow: var(--shadow-lg);
            }

            .card-header-custom {
                background: linear-gradient(135deg, var(--primary-color) 0%, #0056b3 100%);
                color: white;
                padding: 1rem 1.5rem;
                border-radius: var(--border-radius) var(--border-radius) 0 0;
                font-weight: 600;
                border: none;
            }

            .card-header-success {
                background: linear-gradient(135deg, var(--success-color) 0%, #218838 100%);
            }

            .card-header-danger {
                background: linear-gradient(135deg, var(--danger-color) 0%, #c82333 100%);
            }

            .card-header-warning {
                background: linear-gradient(135deg, var(--warning-color) 0%, #e0a800 100%);
            }

            .price-box {
                background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
                border-left: 4px solid var(--warning-color);
                border-radius: var(--border-radius);
                padding: 1.5rem;
                box-shadow: var(--shadow-sm);
                transition: var(--transition);
            }

            .price-box:hover {
                box-shadow: var(--shadow-md);
                transform: translateX(5px);
            }

            .price-value {
                font-size: 2rem;
                font-weight: 700;
                color: #856404;
                line-height: 1.2;
            }

            .badge-modern {
                padding: 0.5rem 1rem;
                border-radius: 20px;
                font-weight: 500;
                font-size: 0.875rem;
                box-shadow: var(--shadow-sm);
                transition: var(--transition);
            }

            .badge-modern:hover {
                transform: scale(1.05);
                box-shadow: var(--shadow-md);
            }

            .info-item {
                padding: 1rem;
                border-radius: 8px;
                transition: var(--transition);
                margin-bottom: 0.75rem;
            }

            .info-item:hover {
                background: var(--light-bg);
                transform: translateX(5px);
            }

            .info-icon {
                width: 48px;
                height: 48px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.25rem;
                margin-right: 1rem;
                flex-shrink: 0;
            }

            .icon-primary {
                background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
                color: var(--primary-color);
            }

            .icon-success {
                background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
                color: var(--success-color);
            }

            .icon-danger {
                background: linear-gradient(135deg, #ffebee 0%, #ffcdd2 100%);
                color: var(--danger-color);
            }

            .icon-info {
                background: linear-gradient(135deg, #e0f7fa 0%, #b2ebf2 100%);
                color: var(--info-color);
            }

            .icon-warning {
                background: linear-gradient(135deg, #fff8e1 0%, #ffecb3 100%);
                color: #f57c00;
            }

            .btn-modern {
                border-radius: 8px;
                padding: 0.75rem 1.5rem;
                font-weight: 600;
                transition: var(--transition);
                box-shadow: var(--shadow-sm);
                border: none;
            }

            .btn-modern:hover {
                transform: translateY(-2px);
                box-shadow: var(--shadow-md);
            }

            .btn-success-modern {
                background: linear-gradient(135deg, var(--success-color) 0%, #218838 100%);
                color: white;
            }

            .btn-success-modern:hover {
                background: linear-gradient(135deg, #218838 0%, var(--success-color) 100%);
                color: white;
            }

            .seller-card {
                border: 3px solid var(--success-color);
                border-radius: var(--border-radius);
                box-shadow: var(--shadow-md);
                transition: var(--transition);
            }

            .seller-card:hover {
                box-shadow: var(--shadow-lg);
                transform: translateY(-3px);
            }

            .section-title {
                font-size: 0.875rem;
                font-weight: 600;
                color: #6c757d;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                margin-bottom: 0.5rem;
            }

            .section-value {
                font-size: 1rem;
                font-weight: 600;
                color: var(--dark-color);
                line-height: 1.5;
            }

            .description-text {
                font-size: 0.95rem;
                line-height: 1.7;
                color: #495057;
            }

            .status-badge {
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                padding: 0.5rem 1rem;
                border-radius: 20px;
                font-weight: 500;
            }

            .zoom-badge {
                position: absolute;
                top: 15px;
                right: 15px;
                background: rgba(40, 167, 69, 0.9);
                backdrop-filter: blur(10px);
                color: white;
                padding: 0.5rem 1rem;
                border-radius: 20px;
                font-size: 0.875rem;
                font-weight: 500;
                box-shadow: var(--shadow-md);
                z-index: 10;
            }

            /* Cinta de Estado */
            .status-ribbon {
                position: absolute;
                top: 20px;
                left: -35px;
                width: 200px;
                padding: 10px 0;
                text-align: center;
                font-weight: 700;
                font-size: 0.875rem;
                color: white;
                text-transform: uppercase;
                letter-spacing: 1px;
                box-shadow: 0 4px 8px rgba(0,0,0,0.2);
                z-index: 20;
                transform: rotate(-45deg);
                transition: var(--transition);
            }

            .status-ribbon:hover {
                transform: rotate(-45deg) scale(1.05);
            }

            .status-ribbon::before,
            .status-ribbon::after {
                content: '';
                position: absolute;
                border-style: solid;
                border-color: transparent;
            }

            .status-ribbon::before {
                bottom: -10px;
                left: 0;
                border-width: 0 10px 10px 0;
            }

            .status-ribbon::after {
                bottom: -10px;
                right: 0;
                border-width: 0 0 10px 10px;
            }

            /* Colores según estado */
            .status-ribbon.disponible {
                background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            }

            .status-ribbon.disponible::before {
                border-right-color: #1e7e34;
            }

            .status-ribbon.disponible::after {
                border-left-color: #1e7e34;
            }

            .status-ribbon.en_mantenimiento,
            .status-ribbon.mantenimiento {
                background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
            }

            .status-ribbon.en_mantenimiento::before,
            .status-ribbon.mantenimiento::before {
                border-right-color: #e0a800;
            }

            .status-ribbon.en_mantenimiento::after,
            .status-ribbon.mantenimiento::after {
                border-left-color: #e0a800;
            }

            .status-ribbon.en_uso,
            .status-ribbon.uso {
                background: linear-gradient(135deg, #17a2b8 0%, #007bff 100%);
            }

            .status-ribbon.en_uso::before,
            .status-ribbon.uso::before {
                border-right-color: #138496;
            }

            .status-ribbon.en_uso::after,
            .status-ribbon.uso::after {
                border-left-color: #138496;
            }

            .status-ribbon.dado_baja,
            .status-ribbon.dado-de-baja {
                background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            }

            .status-ribbon.dado_baja::before,
            .status-ribbon.dado-de-baja::before {
                border-right-color: #bd2130;
            }

            .status-ribbon.dado_baja::after,
            .status-ribbon.dado-de-baja::after {
                border-left-color: #bd2130;
            }

            .status-ribbon i {
                margin-right: 5px;
            }

            @media (max-width: 992px) {
                .page-header {
                    padding: 1.5rem;
                }

                .page-header h1 {
                    font-size: 1.5rem;
                }

                .main-image {
                    height: 300px;
                }
            }

            .fade-in {
                animation: fadeIn 0.5s ease-in;
            }

            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .pulse-animation {
                animation: pulse 2s infinite;
            }

            @keyframes pulse {
                0%, 100% {
                    opacity: 1;
                }
                50% {
                    opacity: 0.7;
                }
            }
        </style>

        <!-- Header Mejorado -->
        <div class="page-header fade-in">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h1 class="mb-2">
                        <i class="fas fa-tractor mr-2"></i> Detalle de Maquinaria
                    </h1>
                    <p class="mb-0">
                        <i class="fas fa-info-circle mr-1"></i> Información completa del producto
                    </p>
                </div>
                <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('maquinarias.index') }}"
                    class="btn btn-light btn-modern mt-2 mt-md-0">
                    <i class="fas fa-arrow-left mr-2"></i> Volver
                </a>
            </div>
        </div>

        <div class="row fade-in">
            <!-- Galería de Imágenes -->
            <div class="col-lg-5 mb-4">
                @if ($maquinaria->imagenes && $maquinaria->imagenes->count() > 0)
                    <div class="image-container mb-3">
                        <div class="position-relative">
                            @if ($maquinaria->estadoMaquinaria)
                                @php
                                    $estadoNombre = strtolower($maquinaria->estadoMaquinaria->nombre);
                                    $estadoClase = str_replace(' ', '_', $estadoNombre);
                                    $estadoTexto = ucfirst(str_replace('_', ' ', $estadoNombre));
                                    
                                    // Mapeo de estados a iconos
                                    $iconos = [
                                        'disponible' => 'fa-check-circle',
                                        'en_mantenimiento' => 'fa-tools',
                                        'mantenimiento' => 'fa-tools',
                                        'en_uso' => 'fa-cog',
                                        'uso' => 'fa-cog',
                                        'dado_baja' => 'fa-ban',
                                        'dado-de-baja' => 'fa-ban'
                                    ];
                                    $icono = $iconos[$estadoClase] ?? 'fa-info-circle';
                                @endphp
                                <div class="status-ribbon {{ $estadoClase }}">
                                    <i class="fas {{ $icono }}"></i>{{ $estadoTexto }}
                                </div>
                            @elseif($maquinaria->estado)
                                @php
                                    $estadoNombre = strtolower($maquinaria->estado);
                                    $estadoClase = str_replace(' ', '_', $estadoNombre);
                                    $estadoTexto = ucfirst(str_replace('_', ' ', $estadoNombre));
                                    
                                    $iconos = [
                                        'disponible' => 'fa-check-circle',
                                        'en_mantenimiento' => 'fa-tools',
                                        'mantenimiento' => 'fa-tools',
                                        'en_uso' => 'fa-cog',
                                        'uso' => 'fa-cog',
                                        'dado_baja' => 'fa-ban',
                                        'dado-de-baja' => 'fa-ban'
                                    ];
                                    $icono = $iconos[$estadoClase] ?? 'fa-info-circle';
                                @endphp
                                <div class="status-ribbon {{ $estadoClase }}">
                                    <i class="fas {{ $icono }}"></i>{{ $estadoTexto }}
                                </div>
                            @endif
                            
                            <img id="mainImage" 
                                src="{{ asset('storage/' . $maquinaria->imagenes->first()->ruta) }}"
                                alt="{{ $maquinaria->nombre }}"
                                class="main-image"
                                data-toggle="modal" 
                                data-target="#imageModal"
                                onclick="document.getElementById('imageModalImg').src = this.src"
                                title="Click para ver imagen completa">
                            
                            <div class="zoom-badge">
                                <i class="fas fa-search-plus mr-1"></i> Click para ampliar
                            </div>
                        </div>
                    </div>

                    @if ($maquinaria->imagenes->count() > 1)
                        <div class="row">
                            @foreach ($maquinaria->imagenes as $imagen)
                                <div class="col-4 mb-3">
                                    <div class="thumbnail-container"
                                        onclick="
                                            document.getElementById('mainImage').src = '{{ asset('storage/' . $imagen->ruta) }}';
                                            document.getElementById('imageModalImg').src = '{{ asset('storage/' . $imagen->ruta) }}';
                                        ">
                                        <img src="{{ asset('storage/' . $imagen->ruta) }}" 
                                            alt="Imagen {{ $loop->iteration }}">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                @else
                    <div class="image-container">
                        <div class="position-relative d-flex align-items-center justify-content-center bg-light" style="height: 450px;">
                            @if ($maquinaria->estadoMaquinaria)
                                @php
                                    $estadoNombre = strtolower($maquinaria->estadoMaquinaria->nombre);
                                    $estadoClase = str_replace(' ', '_', $estadoNombre);
                                    $estadoTexto = ucfirst(str_replace('_', ' ', $estadoNombre));
                                    
                                    $iconos = [
                                        'disponible' => 'fa-check-circle',
                                        'en_mantenimiento' => 'fa-tools',
                                        'mantenimiento' => 'fa-tools',
                                        'en_uso' => 'fa-cog',
                                        'uso' => 'fa-cog',
                                        'dado_baja' => 'fa-ban',
                                        'dado-de-baja' => 'fa-ban'
                                    ];
                                    $icono = $iconos[$estadoClase] ?? 'fa-info-circle';
                                @endphp
                                <div class="status-ribbon {{ $estadoClase }}">
                                    <i class="fas {{ $icono }}"></i>{{ $estadoTexto }}
                                </div>
                            @elseif($maquinaria->estado)
                                @php
                                    $estadoNombre = strtolower($maquinaria->estado);
                                    $estadoClase = str_replace(' ', '_', $estadoNombre);
                                    $estadoTexto = ucfirst(str_replace('_', ' ', $estadoNombre));
                                    
                                    $iconos = [
                                        'disponible' => 'fa-check-circle',
                                        'en_mantenimiento' => 'fa-tools',
                                        'mantenimiento' => 'fa-tools',
                                        'en_uso' => 'fa-cog',
                                        'uso' => 'fa-cog',
                                        'dado_baja' => 'fa-ban',
                                        'dado-de-baja' => 'fa-ban'
                                    ];
                                    $icono = $iconos[$estadoClase] ?? 'fa-info-circle';
                                @endphp
                                <div class="status-ribbon {{ $estadoClase }}">
                                    <i class="fas {{ $icono }}"></i>{{ $estadoTexto }}
                                </div>
                            @endif
                            <div class="text-center text-muted">
                                <i class="fas fa-image fa-4x mb-3 pulse-animation"></i>
                                <p class="mb-0">Sin imágenes disponibles</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Información Principal -->
            <div class="col-lg-7">
                <div class="info-card mb-4">
                    <div class="card-body p-4">
                        <h2 class="h3 mb-3 text-dark font-weight-bold">{{ $maquinaria->nombre }}</h2>

                        <div class="d-flex flex-wrap align-items-center gap-2 mb-4">
                            @if ($maquinaria->tipoMaquinaria)
                                <span class="badge badge-warning badge-modern">
                                    <i class="fas fa-cog mr-1"></i> {{ $maquinaria->tipoMaquinaria->nombre }}
                                </span>
                            @endif
                            @if ($maquinaria->marcaMaquinaria)
                                <span class="badge badge-info badge-modern">
                                    <i class="fas fa-tag mr-1"></i> {{ $maquinaria->marcaMaquinaria->nombre }}
                                </span>
                            @endif
                            @if ($maquinaria->estadoMaquinaria)
                                @php
                                    $estado = strtolower($maquinaria->estadoMaquinaria->nombre);
                                    $badgeClass = $estado === 'disponible' ? 'badge-success' : 'badge-secondary';
                                @endphp
                                <span class="badge {{ $badgeClass }} badge-modern status-badge">
                                    <i class="fas fa-{{ $estado === 'disponible' ? 'check-circle' : 'clock' }} mr-1"></i> 
                                    {{ $maquinaria->estadoMaquinaria->nombre }}
                                </span>
                            @endif
                        </div>

                        @if ($maquinaria->precio_dia)
                            <div class="price-box mb-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <small class="text-muted d-block mb-1" style="font-weight: 600;">Precio por día</small>
                                        <div class="price-value">
                                            Bs {{ number_format($maquinaria->precio_dia, 2) }}
                                            <small class="text-muted" style="font-size: 1rem;">/día</small>
                                        </div>
                                    </div>
                                    <div>
                                        <i class="fas fa-calendar-day fa-3x text-warning opacity-50"></i>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @auth
                            @if ($maquinaria->precio_dia)
                                <div class="border-top pt-4">
                                    <form action="{{ route('cart.add') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_type" value="maquinaria">
                                        <input type="hidden" name="product_id" value="{{ $maquinaria->id }}">
                                        <div class="form-row align-items-end">
                                            <div class="col-auto">
                                                <label class="section-title mb-2 d-block">Días de alquiler</label>
                                                <input type="number" 
                                                    name="dias_alquiler" 
                                                    class="form-control form-control-lg"
                                                    value="1" 
                                                    min="1" 
                                                    required 
                                                    style="width: 120px; border-radius: 8px;">
                                            </div>
                                            <div class="col">
                                                <button type="submit" class="btn btn-success-modern btn-lg btn-block btn-modern">
                                                    <i class="fas fa-cart-plus mr-2"></i> Agregar al Carrito
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        </div>

        <!-- Información Detallada -->
        <div class="row fade-in">
            <div class="col-lg-8">
                <!-- Información Básica -->
                <div class="info-card mb-3">
                    <div class="card-header-custom">
                        <h5 class="mb-0">
                            <i class="fas fa-info-circle mr-2"></i> Información Detallada
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row">
                            @if ($maquinaria->modelo)
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <div class="d-flex align-items-center">
                                            <div class="info-icon icon-primary">
                                                <i class="fas fa-tag"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="section-title">Modelo</div>
                                                <div class="section-value">{{ $maquinaria->modelo }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if ($maquinaria->telefono)
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <div class="d-flex align-items-center">
                                            <div class="info-icon icon-success">
                                                <i class="fas fa-phone"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="section-title">Teléfono de Contacto</div>
                                                <div class="section-value">
                                                    <a href="tel:{{ $maquinaria->telefono }}" class="text-dark text-decoration-none">
                                                        <i class="fas fa-phone-alt mr-1"></i>{{ $maquinaria->telefono }}
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if ($maquinaria->categoria)
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <div class="d-flex align-items-center">
                                            <div class="info-icon icon-info">
                                                <i class="fas fa-folder"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="section-title">Categoría</div>
                                                <div class="section-value">{{ $maquinaria->categoria->nombre }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if ($maquinaria->created_at)
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <div class="d-flex align-items-center">
                                            <div class="info-icon icon-warning">
                                                <i class="fas fa-calendar-alt"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="section-title">Fecha de Publicación</div>
                                                <div class="section-value">
                                                    {{ \Carbon\Carbon::parse($maquinaria->created_at)->format('d/m/Y') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        @if ($maquinaria->descripcion)
                            <div class="mt-3 pt-3 border-top">
                                <div class="section-title mb-2">
                                    <i class="fas fa-align-left mr-1"></i> Descripción
                                </div>
                                <p class="mb-0 description-text">{{ $maquinaria->descripcion }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Ubicación -->
                <div class="info-card mb-3">
                    <div class="card-header-danger">
                        <h5 class="mb-0">
                            <i class="fas fa-map-marker-alt mr-2"></i> Ubicación
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        @if ($maquinaria->ciudad || $maquinaria->municipio || $maquinaria->departamento)
                            <div class="mb-3">
                                <div class="info-item mb-3">
                                    <div class="d-flex align-items-center">
                                        <div class="info-icon icon-danger">
                                            <i class="fas fa-building"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="section-title">Ciudad</div>
                                            <div class="section-value">
                                                {{ $maquinaria->ciudad ?? ($maquinaria->municipio ?? 'No disponible') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <div class="section-title mb-2">Dirección Completa</div>
                                    @php
                                        $direccion = [];
                                        if ($maquinaria->municipio) {
                                            $direccion[] = $maquinaria->municipio;
                                        }
                                        if ($maquinaria->provincia) {
                                            $direccion[] = 'Provincia ' . $maquinaria->provincia;
                                        }
                                        if ($maquinaria->departamento) {
                                            $direccion[] = $maquinaria->departamento;
                                        }
                                        $direccion[] = 'Bolivia';
                                        $direccionCompleta = implode(', ', $direccion);
                                    @endphp
                                    <div class="section-value">
                                        <i class="fas fa-map-marker-alt text-danger mr-1"></i>{{ $direccionCompleta }}
                                    </div>
                                </div>
                            </div>
                        @elseif($maquinaria->ubicacion)
                            <div class="info-item">
                                <div class="d-flex align-items-center">
                                    <div class="info-icon icon-danger">
                                        <i class="fas fa-location-dot"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="section-value">{{ $maquinaria->ubicacion }}</div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <p class="text-muted mb-0 description-text">Sin ubicación especificada</p>
                        @endif
                        @if ($maquinaria->latitud && $maquinaria->longitud)
                            <div class="mt-3">
                                <button type="button" 
                                    class="btn btn-danger btn-modern btn-block" 
                                    data-toggle="modal"
                                    data-target="#mapModal">
                                    <i class="fas fa-map mr-2"></i> Ver Mapa Interactivo
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Columna Derecha -->
            <div class="col-lg-4">
                <!-- Fechas -->
                <div class="info-card mb-3">
                    <div class="card-header-custom">
                        <h5 class="mb-0">
                            <i class="fas fa-calendar-alt mr-2"></i> Fechas
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        @if ($maquinaria->created_at)
                            <div class="info-item">
                                <div class="d-flex align-items-center">
                                    <div class="info-icon icon-warning">
                                        <i class="fas fa-calendar-check"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="section-title">Fecha de Publicación</div>
                                        <div class="section-value">
                                            {{ \Carbon\Carbon::parse($maquinaria->created_at)->format('d/m/Y') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <p class="text-muted mb-0 description-text">Sin fecha de publicación</p>
                        @endif
                    </div>
                </div>

                <!-- Información del Vendedor -->
                @if ($maquinaria->user)
                    <div class="seller-card mb-3">
                        <div class="card-header-success">
                            <h5 class="mb-0 font-weight-bold">
                                <i class="fas fa-user mr-2"></i> Información del Vendedor
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <h6 class="mb-2 font-weight-bold text-dark" style="font-size: 1.1rem;">
                                    {{ $maquinaria->user->name }}
                                </h6>
                                <div class="d-flex align-items-center text-muted">
                                    <i class="fas fa-envelope text-success mr-2"></i>
                                    <span style="font-size: 0.9rem;">{{ $maquinaria->user->email }}</span>
                                </div>
                            </div>

                            <div class="border-top pt-3">
                                @if ($maquinaria->user->role)
                                    <div class="mb-3">
                                        <div class="section-title mb-2">Tipo de Usuario</div>
                                        @php
                                            $roleName = $maquinaria->user->role->nombre ?? ($maquinaria->user->role_name ?? 'Cliente');
                                            $badgeClass = 'badge-secondary';
                                            if ($roleName === 'Administrador' || $roleName === 'admin') {
                                                $badgeClass = 'badge-danger';
                                            } elseif ($roleName === 'Vendedor' || $roleName === 'vendedor') {
                                                $badgeClass = 'badge-success';
                                            }
                                        @endphp
                                        <span class="badge {{ $badgeClass }} badge-modern">
                                            <i class="fas fa-user-tag mr-1"></i> {{ $roleName }}
                                        </span>
                                    </div>
                                @endif

                                @if ($maquinaria->user->created_at)
                                    <div class="mb-3">
                                        <div class="section-title mb-1">Miembro Desde</div>
                                        <div class="section-value">
                                            <i class="fas fa-calendar-check text-success mr-1"></i>
                                            {{ \Carbon\Carbon::parse($maquinaria->user->created_at)->format('d/m/Y') }}
                                        </div>
                                    </div>
                                @endif
                            </div>

                            @auth
                                @if (auth()->id() !== $maquinaria->user_id)
                                    <div class="mt-3 pt-3 border-top">
                                        <a href="mailto:{{ $maquinaria->user->email }}"
                                            class="btn btn-success-modern btn-block btn-modern">
                                            <i class="fas fa-envelope mr-2"></i> Contactar Vendedor
                                        </a>
                                    </div>
                                @endif
                            @else
                                <div class="mt-3 pt-3 border-top">
                                    <a href="{{ route('login') }}" class="btn btn-outline-success btn-block btn-modern">
                                        <i class="fas fa-sign-in-alt mr-2"></i> Inicia sesión para contactar
                                    </a>
                                </div>
                            @endauth
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal del Mapa -->
    @if ($maquinaria->latitud && $maquinaria->longitud)
        <div class="modal fade" id="mapModal" tabindex="-1" role="dialog" aria-labelledby="mapModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content" style="border-radius: var(--border-radius);">
                    <div class="modal-header" style="background: linear-gradient(135deg, var(--danger-color) 0%, #c82333 100%); color: white;">
                        <h5 class="modal-title" id="mapModalLabel">
                            <i class="fas fa-map-marker-alt mr-2"></i> Ubicación del Anuncio
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body p-0">
                        <div id="map-maquinaria-modal" style="height: 500px; width: 100%; border-radius: 0 0 var(--border-radius) var(--border-radius);"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-modern" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Leaflet CSS --}}
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

        {{-- Leaflet JS --}}
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

        <script>
            window.addEventListener('load', function() {
                $(document).ready(function() {
                    let mapMaquinaria = null;

                    @php
                        $popupText = $maquinaria->nombre;
                        if ($maquinaria->ciudad || $maquinaria->municipio) {
                            $popupText .= ' - ' . ($maquinaria->ciudad ?? $maquinaria->municipio);
                        }
                        if ($maquinaria->municipio || $maquinaria->provincia || $maquinaria->departamento) {
                            $direccion = [];
                            if ($maquinaria->municipio) {
                                $direccion[] = $maquinaria->municipio;
                            }
                            if ($maquinaria->provincia) {
                                $direccion[] = 'Provincia ' . $maquinaria->provincia;
                            }
                            if ($maquinaria->departamento) {
                                $direccion[] = $maquinaria->departamento;
                            }
                            $direccion[] = 'Bolivia';
                            $popupText .= ' - ' . implode(', ', $direccion);
                        } elseif ($maquinaria->ubicacion) {
                            $popupText .= ' - ' . $maquinaria->ubicacion;
                        }
                    @endphp

                    function initMap() {
                        if (typeof L === 'undefined') {
                            console.error('Leaflet no está disponible');
                            return false;
                        }

                        try {
                            mapMaquinaria = L.map('map-maquinaria-modal').setView(
                                [{{ $maquinaria->latitud }}, {{ $maquinaria->longitud }}],
                                12
                            );

                            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                maxZoom: 19,
                                attribution: '© OpenStreetMap contributors'
                            }).addTo(mapMaquinaria);

                            L.marker([{{ $maquinaria->latitud }}, {{ $maquinaria->longitud }}])
                                .addTo(mapMaquinaria)
                                .bindPopup("{{ addslashes($popupText) }}");

                            return true;
                        } catch (error) {
                            console.error('Error al inicializar el mapa:', error);
                            return false;
                        }
                    }

                    $('#mapModal').on('shown.bs.modal', function() {
                        if (!mapMaquinaria) {
                            setTimeout(function() {
                                if (!initMap()) {
                                    setTimeout(initMap, 500);
                                }
                            }, 200);
                        } else {
                            setTimeout(function() {
                                mapMaquinaria.invalidateSize();
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
                    aria-label="Close" style="font-size: 2rem; z-index: 1051; text-shadow: 0 2px 4px rgba(0,0,0,0.5);">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div class="modal-body p-0 text-center">
                    <img id="imageModalImg" src="" class="img-fluid rounded"
                        style="max-height: 80vh; object-fit: contain; box-shadow: var(--shadow-lg);">
                </div>
            </div>
        </div>
    </div>
@endsection
