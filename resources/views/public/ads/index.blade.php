@extends('layouts.public')
@section('title', 'Anuncios')

@section('content')
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

        /* Header Mejorado */
        .page-header-modern {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: var(--border-radius);
            padding: 2rem;
            margin-bottom: 2rem;
            color: white;
            box-shadow: var(--shadow-md);
        }

        .page-header-modern h2 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }

        /* Botón Publicar Mejorado */
        .btn-publish-modern {
            background: linear-gradient(135deg, var(--success-color) 0%, #20c997 100%);
            border: none;
            border-radius: 10px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            box-shadow: var(--shadow-md);
            transition: var(--transition);
            color: white;
        }

        .btn-publish-modern:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
            background: linear-gradient(135deg, #20c997 0%, var(--success-color) 100%);
            color: white;
        }

        .dropdown-menu-modern {
            border-radius: 10px;
            border: none;
            box-shadow: var(--shadow-lg);
            padding: 0.5rem 0;
            margin-top: 0.5rem;
            z-index: 1050 !important;
            position: absolute !important;
        }

        .btn-group {
            position: relative;
            z-index: 1000;
        }

        .dropdown-item-modern {
            padding: 0.75rem 1.5rem;
            transition: var(--transition);
            border-radius: 0;
            color: var(--dark-color) !important;
            display: flex;
            align-items: center;
        }

        .dropdown-item-modern:hover {
            background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
            color: var(--success-color) !important;
            transform: translateX(5px);
        }

        .dropdown-item-modern i {
            width: 20px;
            text-align: center;
            font-size: 1rem;
            display: inline-block !important;
            margin-right: 0.75rem;
            flex-shrink: 0;
            visibility: visible !important;
            opacity: 1 !important;
        }

        .dropdown-item-modern i.text-success {
            color: var(--success-color) !important;
        }

        .dropdown-item-modern i.text-primary {
            color: var(--primary-color) !important;
        }

        .dropdown-item-modern i.fa-cow {
            color: #28a745 !important;
            font-size: 1.1rem;
        }

        /* Formulario de Búsqueda Mejorado */
        .search-card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-md);
            border: none;
            overflow: visible;
            position: relative;
            z-index: 1;
        }

        .search-card-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 1rem 1.5rem;
            border-bottom: 2px solid #dee2e6;
        }

        .search-card-header h5 {
            margin: 0;
            color: var(--dark-color);
            font-weight: 600;
        }

        .form-control-modern {
            border-radius: 8px;
            border: 2px solid #e9ecef;
            padding: 0.75rem 1rem;
            transition: var(--transition);
            font-size: 0.95rem;
            font-weight: 400;
            line-height: 1.5;
            min-height: 42px;
        }

        /* Estilos específicos para inputs de texto */
        input.form-control-modern {
            color: #212529 !important;
            background-color: #ffffff !important;
        }

        input.form-control-modern:focus {
            border-color: var(--success-color);
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.15);
            color: #212529 !important;
            background-color: #ffffff !important;
        }

        /* Estilos específicos para selects */
        select.form-control-modern {
            color: #212529 !important;
            background-color: #ffffff !important;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 16px 12px;
            padding: 0.875rem 2.5rem 0.875rem 1rem !important;
            line-height: 1.5 !important;
            height: auto !important;
            min-height: 42px !important;
            vertical-align: middle !important;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            display: block;
            width: 100%;
        }

        select.form-control-modern:focus {
            border-color: var(--success-color);
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.15);
            color: #212529 !important;
            background-color: #ffffff !important;
        }

        select.form-control-modern option {
            color: #212529 !important;
            background-color: #ffffff !important;
            padding: 0.5rem;
        }

        /* Forzar visibilidad del texto seleccionado */
        select.form-control-modern::-ms-value {
            color: #212529 !important;
            background-color: #ffffff !important;
        }

        .input-group-modern .input-group-text {
            background: linear-gradient(135deg, var(--success-color) 0%, #20c997 100%);
            color: white;
            border: none;
            border-radius: 8px 0 0 8px;
            font-weight: 600;
        }

        .btn-search-modern {
            background: linear-gradient(135deg, var(--success-color) 0%, #20c997 100%);
            border: none;
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
        }

        .btn-search-modern:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
            background: linear-gradient(135deg, #20c997 0%, var(--success-color) 100%);
        }

        /* Tarjetas de Productos Mejoradas */
        .product-card-modern {
            border-radius: var(--border-radius);
            border: none;
            box-shadow: var(--shadow-md);
            transition: var(--transition);
            overflow: hidden;
            background: white;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .product-card-modern:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-8px);
        }

        .card-img-wrapper-modern {
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }

        .product-img {
            transition: var(--transition);
            width: 100%;
            height: 250px;
            object-fit: cover;
        }

        .product-card-modern:hover .product-img {
            transform: scale(1.1);
        }

        .product-img-contain {
            object-fit: contain !important;
            background: white;
        }


        .card-body-modern {
            padding: 1.25rem;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .card-title-modern {
            font-size: 1.15rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 0.75rem;
            line-height: 1.4;
        }

        .card-title-modern i {
            color: var(--success-color);
            margin-right: 0.5rem;
        }

        .meta-list-modern {
            list-style: none;
            padding: 0;
            margin: 0 0 1rem 0;
        }

        .meta-list-modern li {
            padding: 0.25rem 0;
            font-size: 0.875rem;
            color: #6c757d;
        }

        .meta-list-modern li i {
            color: var(--success-color);
            width: 20px;
            margin-right: 0.5rem;
        }

        .badge-category-modern {
            background: linear-gradient(135deg, var(--success-color) 0%, #20c997 100%);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 500;
            font-size: 0.875rem;
            box-shadow: var(--shadow-sm);
            display: inline-block;
            margin-bottom: 1rem;
        }

        .price-box-modern {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            border-left: 4px solid var(--success-color);
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
            box-shadow: var(--shadow-sm);
        }

        .price-box-modern small {
            font-size: 0.75rem;
            font-weight: 600;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .price-box-modern h4 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--success-color);
            margin: 0.25rem 0 0 0;
        }

        .card-footer-modern {
            background: white;
            border-top: 2px solid #e9ecef;
            padding: 1rem 1.25rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .price-footer {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--success-color);
        }

        .btn-view-modern {
            background: linear-gradient(135deg, var(--success-color) 0%, #20c997 100%);
            border: none;
            border-radius: 8px;
            padding: 0.5rem 1.25rem;
            color: white;
            font-weight: 600;
            font-size: 0.875rem;
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
        }

        .btn-view-modern:hover {
            transform: translateX(5px);
            box-shadow: var(--shadow-md);
            background: linear-gradient(135deg, #20c997 0%, var(--success-color) 100%);
            color: white;
        }

        /* Sección de Títulos Mejorada */
        .section-title-modern {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            padding: 1rem 1.5rem;
            border-radius: var(--border-radius);
            margin-bottom: 1.5rem;
            border-left: 4px solid var(--primary-color);
        }

        .section-title-modern h4 {
            margin: 0;
            color: var(--dark-color);
            font-weight: 700;
            font-size: 1.25rem;
        }

        .section-title-modern .badge {
            background: var(--primary-color);
            color: white;
            padding: 0.5rem 0.75rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 600;
            margin-left: 0.5rem;
        }

        /* Cinta de Estado para Maquinaria */
        .status-ribbon {
            position: absolute;
            top: 15px;
            left: -35px;
            width: 180px;
            padding: 8px 0;
            text-align: center;
            font-weight: 700;
            font-size: 0.75rem;
            color: white;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 3px 6px rgba(0,0,0,0.2);
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
            bottom: -8px;
            left: 0;
            border-width: 0 8px 8px 0;
        }

        .status-ribbon::after {
            bottom: -8px;
            right: 0;
            border-width: 0 0 8px 8px;
        }

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
            margin-right: 3px;
            font-size: 0.7rem;
        }

        /* Sin resultados mejorado */
        .no-results-modern {
            background: white;
            border-radius: var(--border-radius);
            padding: 3rem;
            text-align: center;
            box-shadow: var(--shadow-md);
        }

        .no-results-modern i {
            font-size: 4rem;
            color: var(--info-color);
            margin-bottom: 1rem;
        }

        /* Animaciones */
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

        @media (max-width: 768px) {
            .page-header-modern {
                padding: 1.5rem;
            }

            .page-header-modern h2 {
                font-size: 1.5rem;
            }
        }
    </style>

    <section class="container py-4 fade-in">
        <!-- Header Mejorado -->
        <div class="page-header-modern">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h2 class="mb-2">
                        <i class="fas fa-bullhorn mr-2"></i> Anuncios
                    </h2>
                    <p class="mb-0 opacity-90">
                        <i class="fas fa-info-circle mr-1"></i> Explora todos los productos disponibles
                    </p>
                </div>
                @auth
                    @if (auth()->user()->isVendedor() || auth()->user()->isAdmin())
                        <div class="btn-group mt-3 mt-md-0">
                            <button type="button" class="btn btn-publish-modern dropdown-toggle" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-plus-circle mr-2"></i> Publicar Anuncio
                            </button>
                            <div class="dropdown-menu dropdown-menu-modern dropdown-menu-right">
                            <a class="dropdown-item dropdown-item-modern" href="{{ route('ganados.create') }}">
  <i class="fas fa-paw text-success mr-2"></i> Publicar Animal
</a>

                                <a class="dropdown-item dropdown-item-modern" href="{{ route('maquinarias.create') }}">
                                    <i class="fas fa-tractor text-primary mr-2"></i> Publicar Maquinaria
                                </a>
                                <a class="dropdown-item dropdown-item-modern" href="{{ route('organicos.create') }}">
                                    <i class="fas fa-leaf text-success mr-2"></i> Publicar Orgánico
                                </a>
                            </div>
                        </div>
                    @endif
                @endauth
            </div>
        </div>

        <!-- Buscador y Filtros Mejorados -->
        <div class="search-card mb-4">
            <div class="search-card-header">
                <h5>
                    <i class="fas fa-filter text-primary mr-2"></i> Buscar y Filtrar
                </h5>
            </div>
            <div class="card-body p-4">
                <form method="GET" action="{{ route('ads.index') }}" id="filtroForm">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="font-weight-bold mb-2 text-dark">
                                <i class="fas fa-folder text-primary mr-1"></i> Categoría
                            </label>
                            <select name="categoria_id" class="form-control form-control-modern"
                                onchange="document.getElementById('filtroForm').submit()">
                                <option value="">Todas las categorías</option>
                                @foreach ($categorias as $categoria)
                                    <option value="{{ $categoria->id }}"
                                        {{ request('categoria_id') == $categoria->id ? 'selected' : '' }}>
                                        {{ $categoria->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="font-weight-bold mb-2 text-dark">
                                <i class="fas fa-calendar-alt text-info mr-1"></i> Fecha de Publicación
                            </label>
                            <select name="fecha_publicacion" class="form-control form-control-modern"
                                onchange="document.getElementById('filtroForm').submit()">
                                <option value="">Todas las fechas</option>
                                <option value="hoy" {{ request('fecha_publicacion') == 'hoy' ? 'selected' : '' }}>Hoy</option>
                                <option value="semana" {{ request('fecha_publicacion') == 'semana' ? 'selected' : '' }}>Última semana</option>
                                <option value="mes" {{ request('fecha_publicacion') == 'mes' ? 'selected' : '' }}>Último mes</option>
                                <option value="3meses" {{ request('fecha_publicacion') == '3meses' ? 'selected' : '' }}>Últimos 3 meses</option>
                                <option value="6meses" {{ request('fecha_publicacion') == '6meses' ? 'selected' : '' }}>Últimos 6 meses</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="font-weight-bold mb-2 text-dark">
                                <i class="fas fa-search text-success mr-1"></i> Buscar
                            </label>
                            <div class="input-group input-group-modern">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-search"></i>
                                    </span>
                                </div>
                                <input type="text" name="q" class="form-control form-control-modern"
                                    placeholder="Buscar animales, maquinaria u orgánicos..." value="{{ request('q') }}">
                            </div>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="font-weight-bold mb-2 text-dark d-block" style="opacity: 0;">Buscar</label>
                            <button type="submit" class="btn btn-search-modern btn-block">
                                <i class="fas fa-search mr-1"></i> Buscar
                            </button>
                        </div>
                    </div>
                </form>
                @if (request()->has('q') || request()->has('categoria_id') || request()->has('fecha_publicacion'))
                    <div class="mt-3 pt-3 border-top">
                        <a href="{{ route('ads.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-times mr-1"></i> Limpiar filtros
                        </a>
                    </div>
                @endif
            </div>
        </div>

        {{-- Resultados --}}
        @php
            $totalResultados = 0;
            if (method_exists($ganados, 'total')) {
                $totalResultados += $ganados->total();
            } elseif ($ganados->count() > 0) {
                $totalResultados += $ganados->count();
            }
            if (method_exists($maquinarias, 'total')) {
                $totalResultados += $maquinarias->total();
            } elseif ($maquinarias->count() > 0) {
                $totalResultados += $maquinarias->count();
            }
            if (method_exists($organicos, 'total')) {
                $totalResultados += $organicos->total();
            } elseif ($organicos->count() > 0) {
                $totalResultados += $organicos->count();
            }
        @endphp

        @if ($totalResultados > 0 || (!request()->has('q') && !request()->has('categoria_id') && !request()->has('tipo')))
            {{-- GANADOS --}}
            @if (isset($ganados) && ($ganados->count() > 0 || (method_exists($ganados, 'total') && $ganados->total() > 0)))
                <div class="mb-5">
                    <div class="section-title-modern">
                        <h4>
                            <i class="fas fa-cow text-primary"></i> Animales
                            @if (method_exists($ganados, 'total'))
                                <span class="badge">({{ $ganados->total() }})</span>
                            @else
                                <span class="badge">({{ $ganados->count() }})</span>
                            @endif
                        </h4>
                    </div>
                    <div class="row">
                        @foreach ($ganados as $ganado)
                            <div class="col-md-6 col-lg-4 mb-4">
                                <a href="{{ route('ganados.show', $ganado->id) }}" class="text-decoration-none"
                                    style="color: inherit;">
                                    <div class="product-card-modern">
                                        @php
                                            $imagenPrincipal =
                                                $ganado->imagenes->first()->ruta ?? ($ganado->imagen ?? null);
                                        @endphp
                                        @if ($imagenPrincipal)
                                            <div class="card-img-wrapper-modern">
                                                <img src="{{ asset('storage/' . $imagenPrincipal) }}"
                                                    class="product-img" alt="{{ $ganado->nombre }}">
                                            </div>
                                        @else
                                            <div class="card-img-wrapper-modern d-flex align-items-center justify-content-center"
                                                style="height: 250px;">
                                                <i class="fas fa-image fa-4x text-muted"></i>
                                            </div>
                                        @endif
                                        <div class="card-body-modern">
                                            <h5 class="card-title-modern">
                                                <i class="fas fa-tag"></i>{{ $ganado->nombre }}
                                            </h5>
                                            <ul class="meta-list-modern">
                                                @if ($ganado->ubicacion)
                                                    <li>
                                                        <i class="fas fa-map-marker-alt"></i>
                                                        <span>{{ Str::limit($ganado->ubicacion, 40) }}</span>
                                                    </li>
                                                @endif
                                                @if ($ganado->tipoAnimal)
                                                    <li>
                                                        <i class="fas fa-paw"></i>
                                                        <span>{{ $ganado->tipoAnimal->nombre }}</span>
                                                    </li>
                                                @endif
                                                @if ($ganado->edad)
                                                    <li>
                                                        <i class="fas fa-birthday-cake"></i>
                                                        <span>{{ $ganado->edad }} meses</span>
                                                    </li>
                                                @endif
                                                @if ($ganado->fecha_publicacion)
                                                    <li>
                                                        <i class="fas fa-calendar-alt"></i>
                                                        <span>Publicado:
                                                            {{ \Carbon\Carbon::parse($ganado->fecha_publicacion)->format('d/m/Y') }}</span>
                                                    </li>
                                                @endif
                                            </ul>
                                            <div>
                                                <span class="badge-category-modern">
                                                    <i class="fas fa-tags"></i>
                                                    {{ $ganado->categoria->nombre ?? 'Animales' }}
                                                </span>
                                            </div>
                                            @if ($ganado->precio)
                                                <div class="price-box-modern">
                                                    <small>Precio</small>
                                                    <h4>
                                                        <i class="fas fa-boliviano-sign"></i>
                                                        {{ number_format($ganado->precio, 2) }}
                                                    </h4>
                                                </div>
                                            @else
                                                <div class="price-box-modern" style="background: #f8f9fa; border-left-color: #6c757d;">
                                                    <small>Precio</small>
                                                    <p class="mb-0 text-muted">A consultar</p>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="card-footer-modern">
                                            @if ($ganado->precio)
                                                <span class="price-footer">Bs {{ number_format($ganado->precio, 2) }}</span>
                                            @else
                                                <span class="price-footer text-muted">Consultar</span>
                                            @endif
                                            <button class="btn btn-view-modern">
                                                Ver Anuncio <i class="fas fa-arrow-right ml-1"></i>
                                            </button>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                    @if (method_exists($ganados, 'links'))
                        <div class="mt-4">
                            {{ $ganados->appends(request()->except('ganados_page'))->links() }}
                        </div>
                    @endif
                </div>
            @endif

            {{-- MAQUINARIAS --}}
            @if (isset($maquinarias) &&
                    ($maquinarias->count() > 0 || (method_exists($maquinarias, 'total') && $maquinarias->total() > 0)))
                <div class="mb-5">
                    <div class="section-title-modern">
                        <h4>
                            <i class="fas fa-tractor text-primary"></i> Maquinaria
                            @if (method_exists($maquinarias, 'total'))
                                <span class="badge">({{ $maquinarias->total() }})</span>
                            @else
                                <span class="badge">({{ $maquinarias->count() }})</span>
                            @endif
                        </h4>
                    </div>
                    <div class="row">
                        @foreach ($maquinarias as $maquinaria)
                            <div class="col-md-6 col-lg-4 mb-4">
                                <a href="{{ route('maquinarias.show', $maquinaria->id) }}" class="text-decoration-none"
                                    style="color: inherit;">
                                    <div class="product-card-modern">
                                        @php
                                            $imagenPrincipal = $maquinaria->imagenes->first()->ruta ?? null;
                                        @endphp
                                        @if ($imagenPrincipal)
                                            <div class="card-img-wrapper-modern">
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
                                                <img src="{{ asset('storage/' . $imagenPrincipal) }}"
                                                    class="product-img product-img-contain" alt="{{ $maquinaria->nombre }}">
                                            </div>
                                        @else
                                            <div class="card-img-wrapper-modern d-flex align-items-center justify-content-center position-relative"
                                                style="height: 250px;">
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
                                                <i class="fas fa-tractor fa-4x text-muted"></i>
                                            </div>
                                        @endif
                                        <div class="card-body-modern">
                                            <h5 class="card-title-modern">
                                                <i class="fas fa-tag"></i>{{ $maquinaria->nombre }}
                                            </h5>
                                            <ul class="meta-list-modern">
                                                @if ($maquinaria->ubicacion)
                                                    <li>
                                                        <i class="fas fa-map-marker-alt"></i>
                                                        <span>{{ Str::limit($maquinaria->ubicacion, 40) }}</span>
                                                    </li>
                                                @endif
                                                @if ($maquinaria->tipoMaquinaria)
                                                    <li>
                                                        <i class="fas fa-cog"></i>
                                                        <span>{{ $maquinaria->tipoMaquinaria->nombre }}</span>
                                                    </li>
                                                @endif
                                                @if ($maquinaria->created_at)
                                                    <li>
                                                        <i class="fas fa-calendar-alt"></i>
                                                        <span>Publicado:
                                                            {{ \Carbon\Carbon::parse($maquinaria->created_at)->format('d/m/Y') }}</span>
                                                    </li>
                                                @endif
                                            </ul>
                                            <div>
                                                <span class="badge-category-modern">
                                                    <i class="fas fa-tags"></i>
                                                    {{ $maquinaria->categoria->nombre ?? 'Maquinaria' }}
                                                </span>
                                            </div>
                                            @if ($maquinaria->precio_dia)
                                                <div class="price-box-modern">
                                                    <small>Precio por día</small>
                                                    <h4>
                                                        <i class="fas fa-boliviano-sign"></i>
                                                        {{ number_format($maquinaria->precio_dia, 2) }}/día
                                                    </h4>
                                                </div>
                                            @else
                                                <div class="price-box-modern" style="background: #f8f9fa; border-left-color: #6c757d;">
                                                    <small>Precio</small>
                                                    <p class="mb-0 text-muted">A consultar</p>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="card-footer-modern">
                                            @if ($maquinaria->precio_dia)
                                                <span class="price-footer">Bs {{ number_format($maquinaria->precio_dia, 2) }}/día</span>
                                            @else
                                                <span class="price-footer text-muted">Consultar</span>
                                            @endif
                                            <button class="btn btn-view-modern">
                                                Ver Anuncio <i class="fas fa-arrow-right ml-1"></i>
                                            </button>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                    @if (method_exists($maquinarias, 'links'))
                        <div class="mt-4">
                            {{ $maquinarias->appends(request()->except('maquinarias_page'))->links() }}
                        </div>
                    @endif
                </div>
            @endif

            {{-- ORGÁNICOS --}}
            @if (isset($organicos) && ($organicos->count() > 0 || (method_exists($organicos, 'total') && $organicos->total() > 0)))
                <div class="mb-5">
                    <div class="section-title-modern">
                        <h4>
                            <i class="fas fa-leaf text-primary"></i> Orgánicos
                            @if (method_exists($organicos, 'total'))
                                <span class="badge">({{ $organicos->total() }})</span>
                            @else
                                <span class="badge">({{ $organicos->count() }})</span>
                            @endif
                        </h4>
                    </div>
                    <div class="row">
                        @foreach ($organicos as $organico)
                            <div class="col-md-6 col-lg-4 mb-4">
                                <a href="{{ route('organicos.show', $organico->id) }}" class="text-decoration-none"
                                    style="color: inherit;">
                                    <div class="product-card-modern">
                                        @php
                                            $imagenPrincipal = $organico->imagenes->first()->ruta ?? null;
                                        @endphp
                                        @if ($imagenPrincipal)
                                            <div class="card-img-wrapper-modern">
                                                <img src="{{ asset('storage/' . $imagenPrincipal) }}"
                                                    class="product-img product-img-contain" alt="{{ $organico->nombre }}">
                                            </div>
                                        @else
                                            <div class="card-img-wrapper-modern d-flex align-items-center justify-content-center"
                                                style="height: 250px;">
                                                <i class="fas fa-leaf fa-4x text-muted"></i>
                                            </div>
                                        @endif
                                        <div class="card-body-modern">
                                            <h5 class="card-title-modern">
                                                <i class="fas fa-tag"></i>{{ $organico->nombre }}
                                            </h5>
                                            <ul class="meta-list-modern">
                                                @if ($organico->origen)
                                                    <li>
                                                        <i class="fas fa-map-marker-alt"></i>
                                                        <span>{{ Str::limit($organico->origen, 40) }}</span>
                                                    </li>
                                                @endif
                                                @if ($organico->fecha_cosecha)
                                                    <li>
                                                        <i class="fas fa-calendar-alt"></i>
                                                        <span>Cosecha:
                                                            {{ \Carbon\Carbon::parse($organico->fecha_cosecha)->format('d/m/Y') }}</span>
                                                    </li>
                                                @endif
                                                @if ($organico->created_at)
                                                    <li>
                                                        <i class="fas fa-calendar-alt"></i>
                                                        <span>Publicado:
                                                            {{ \Carbon\Carbon::parse($organico->created_at)->format('d/m/Y') }}</span>
                                                    </li>
                                                @endif
                                            </ul>
                                            <div>
                                                <span class="badge-category-modern">
                                                    <i class="fas fa-tags"></i>
                                                    {{ $organico->categoria->nombre ?? 'Orgánico' }}
                                                </span>
                                            </div>
                                            @if ($organico->precio)
                                                <div class="price-box-modern">
                                                    <small>Precio</small>
                                                    <h4>
                                                        <i class="fas fa-boliviano-sign"></i>
                                                        {{ number_format($organico->precio, 2) }}
                                                    </h4>
                                                </div>
                                            @else
                                                <div class="price-box-modern" style="background: #f8f9fa; border-left-color: #6c757d;">
                                                    <small>Precio</small>
                                                    <p class="mb-0 text-muted">A consultar</p>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="card-footer-modern">
                                            @if ($organico->precio)
                                                <span class="price-footer">Bs {{ number_format($organico->precio, 2) }}</span>
                                            @else
                                                <span class="price-footer text-muted">Consultar</span>
                                            @endif
                                            <button class="btn btn-view-modern">
                                                Ver Anuncio <i class="fas fa-arrow-right ml-1"></i>
                                            </button>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                    @if (method_exists($organicos, 'links'))
                        <div class="mt-4">
                            {{ $organicos->appends(request()->except('organicos_page'))->links() }}
                        </div>
                    @endif
                </div>
            @endif

            {{-- Sin resultados --}}
            @if ($totalResultados == 0 && (request()->has('q') || request()->has('categoria_id') || request()->has('tipo')))
                <div class="no-results-modern">
                    <i class="fas fa-search"></i>
                    <h4 class="mt-3">No se encontraron resultados</h4>
                    <p class="text-muted">Intenta con otros términos de búsqueda o <a href="{{ route('ads.index') }}">ver todos los
                            anuncios</a></p>
                </div>
            @endif
        @else
            {{-- Sin productos en la base de datos --}}
            <div class="no-results-modern">
                <i class="fas fa-info-circle"></i>
                <h4 class="mt-3">No hay anuncios disponibles</h4>
                <p class="text-muted">Los vendedores aún no han publicado productos.</p>
            </div>
        @endif

    </section>
@endsection
