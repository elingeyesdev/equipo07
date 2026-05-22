@extends('layouts.public')

@section('title', 'Mercado Agrícola')
@section('standalone_public', true)

@section('content')
    <style>
        html {
            scroll-behavior: smooth;
        }

        .landing-page section[id] {
            scroll-margin-top: 5rem;
        }

        @media (prefers-reduced-motion: reduce) {
            html {
                scroll-behavior: auto;
            }
        }

        /* --- CLASES CUSTOM PARA TRADUCCIÓN EXACTA DE TAILWIND --- */
        .landing-page { background-color: #f7fbf2; color: #142112; font-family: var(--app-font), sans-serif; -webkit-font-smoothing: antialiased; }
        
        /* Tipografía y Colores Exactos */
        .fw-black { font-weight: 900 !important; }
        .text-agro-dark { color: #142112 !important; }
        .text-agro-darker { color: #183b18 !important; }
        .text-agro-muted { color: #66735f !important; }
        .text-agro-muted-dark { color: #4b5949 !important; }
        .text-white-75 { color: rgba(255, 255, 255, 0.75) !important; }
        .text-white-70 { color: rgba(255, 255, 255, 0.70) !important; }
        .text-white-65 { color: rgba(255, 255, 255, 0.65) !important; }
        .text-white-60 { color: rgba(255, 255, 255, 0.60) !important; }

        /* Bordes (Radios Tailwind) */
        .rounded-xl { border-radius: 0.75rem !important; }
        .rounded-2xl { border-radius: 1rem !important; }
        .rounded-3xl { border-radius: 1.5rem !important; }
        .rounded-4xl { border-radius: 2rem !important; }

        /* Contenedor equivalente a max-w-7xl de Tailwind (1280px) */
        .container-landing { width: 100%; padding-right: 1rem; padding-left: 1rem; margin-right: auto; margin-left: auto; max-width: 1280px; }
        @media (min-width: 576px) { .container-landing { padding-right: 1.5rem; padding-left: 1.5rem; } }
        @media (min-width: 992px) { .container-landing { padding-right: 2rem; padding-left: 2rem; } }

        /* Espaciados verticales equivalentes (py-16, py-20, py-24) */
        .landing-py-section { padding-top: 4rem; padding-bottom: 4rem; }
        @media (min-width: 576px) { .landing-py-section { padding-top: 5rem; padding-bottom: 5rem; } }
        @media (min-width: 992px) { .landing-hero-py { padding-top: 6rem; padding-bottom: 6rem; } }

        /* Barra de Navegación */
        .landing-nav {
            background-color: rgba(247, 251, 242, 0.9);
            backdrop-filter: blur(24px); -webkit-backdrop-filter: blur(24px);
            border-bottom: 1px solid rgba(47, 98, 31, 0.1);
            min-height: 5rem;
        }
        .landing-link { color: #4b5949; transition: color 0.2s ease-in-out; }
        .landing-link:hover { color: #2f621f; }
        .landing-logo-icon { border: 1px solid rgba(47, 98, 31, 0.15); box-shadow: 0 10px 15px -3px rgba(47, 98, 31, 0.1); }

        /* Botones Generales */
        .landing-btn-outline {
            border: 1px solid rgba(47, 98, 31, 0.15); background: #fff; color: #183b18;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); transition: transform 0.2s, box-shadow 0.2s;
        }
        .landing-btn-outline:hover { transform: translateY(-2px); box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); color: #183b18; }
        
        .landing-btn-gradient {
            background: linear-gradient(to bottom right, #2f621f, #4f8f2f); color: white;
            box-shadow: 0 20px 25px -5px rgba(47, 98, 31, 0.25); transition: transform 0.2s, box-shadow 0.2s;
        }
        .landing-btn-gradient:hover { transform: translateY(-2px); box-shadow: 0 25px 50px -12px rgba(47, 98, 31, 0.25); color: white; }

        /* Sección Hero */
        .landing-hero-bg { background: linear-gradient(to bottom right, #f8fcf3, #edf8e6, #ffffff); }
        .landing-hero-blob-1 { background-color: rgba(47, 98, 31, 0.1); width: 32rem; height: 32rem; right: -10rem; bottom: -12rem; z-index: 0;}
        .landing-hero-blob-2 { background-color: rgba(166, 200, 95, 0.2); width: 10rem; height: 10rem; filter: blur(48px); top: 2.5rem; left: 1.5rem; z-index: 0;}
        .badge-agro-soft { background-color: #edf7e7; color: #2f621f; border: 1px solid rgba(47, 98, 31, 0.15); }
        
        /* Imágenes y Badges flotantes del Hero */
        .landing-hero-img-container {
            border: 1px solid rgba(47, 98, 31, 0.15) !important;
            box-shadow: 0 25px 50px -12px rgba(47, 98, 31, 0.2) !important;
        }
        .landing-hero-overlay { background: linear-gradient(to bottom, transparent, transparent, rgba(20, 33, 18, 0.7)); }
        .landing-hero-img-badge {
            background-color: rgba(20, 33, 18, 0.75); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.2) !important; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }
        .landing-hero-float-badge { 
            border: 1px solid rgba(47, 98, 31, 0.15) !important; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1) !important; 
            width: 14rem; background: rgba(255, 255, 255, 0.95); z-index: 10;
        }

        /* Tarjetas (Beneficios y Categorías) */
        .landing-card-icon { background: linear-gradient(to bottom right, #2f621f, #5c9938); color: white; box-shadow: 0 10px 15px -3px rgba(47, 98, 31, 0.2); }
        .landing-card { border: 1px solid rgba(47, 98, 31, 0.1); box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.04); transition: transform 0.2s, box-shadow 0.2s; }
        .landing-card:hover { transform: translateY(-4px); box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1); }
        .landing-categorias-bg { background: linear-gradient(to bottom, #f4faef, #ffffff); }

        /* Tarjetas 'Cómo Funciona' */
        .landing-step-card {
            background: linear-gradient(to bottom right, #ffffff, #f7fbf2);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.04); border: 1px solid rgba(47, 98, 31, 0.1) !important;
        }

        /* Sección CTA Final */
        .landing-cta-bg {
            background: linear-gradient(135deg, rgba(24, 59, 24, 0.98), rgba(47, 98, 31, 0.95)), url('{{ asset("img/bg-agrovida.jpg") }}');
            background-size: cover; background-position: center;
            box-shadow: 0 25px 50px -12px rgba(24, 59, 24, 0.25) !important;
        }
        .landing-cta-btn-light { transition: transform 0.2s; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1); }
        .landing-cta-btn-light:hover { transform: translateY(-2px); }
        .landing-cta-btn-outline { background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.25) !important; transition: all 0.2s; }
        .landing-cta-btn-outline:hover { background: rgba(255, 255, 255, 0.15); transform: translateY(-2px); color: white; }

        /* Footer */
        .landing-footer-link { color: #dfeada; transition: color 0.2s; }
        .landing-footer-link:hover { color: #ffffff; text-decoration: none; }
    </style>

    <main class="landing-page min-vh-100">
        <!-- NAVEGACIÓN -->
        <nav class="sticky-top w-100 landing-nav d-flex align-items-center">
            <div class="container-landing d-flex align-items-center justify-content-between gap-3">
                
                <a href="{{ route('landing') }}" class="d-flex align-items-center gap-3 fs-5 fw-black text-agro-darker text-decoration-none">
                    <span class="d-flex align-items-center justify-content-center rounded-2xl bg-white landing-logo-icon" style="width: 2.75rem; height: 2.75rem;">
                        <img src="{{ asset('img/logo-agrovida.png') }}" alt="Mercado Agrícola" style="height: 2rem; width: 2rem; object-fit: contain;">
                    </span>
                    <span>Mercado Agrícola</span>
                </a>

                <div class="d-none d-lg-flex align-items-center gap-4">
                    <a href="#inicio" class="small fw-bold text-decoration-none landing-link">Inicio</a>
                    <a href="#beneficios" class="small fw-bold text-decoration-none landing-link">Beneficios</a>
                    <a href="#categorias" class="small fw-bold text-decoration-none landing-link">Categorías</a>
                    <a href="#como-funciona" class="small fw-bold text-decoration-none landing-link">Cómo funciona</a>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('login') }}" class="btn landing-btn-outline rounded-xl fw-black px-3 py-2 d-flex align-items-center" style="min-height: 2.75rem; font-size: 0.875rem;">
                        Iniciar sesión
                    </a>
                    <a href="{{ route('register') }}" class="btn landing-btn-gradient rounded-xl fw-black px-3 py-2 d-flex align-items-center" style="min-height: 2.75rem; font-size: 0.875rem;">
                        Registrarse
                    </a>
                </div>
            </div>
        </nav>

        <!-- SECCIÓN INICIO (HERO) -->
        <section id="inicio" class="position-relative overflow-hidden landing-hero-bg landing-py-section landing-hero-py">
            <div class="position-absolute rounded-circle landing-hero-blob-1"></div>
            <div class="position-absolute rounded-circle landing-hero-blob-2"></div>

            <div class="container-landing position-relative">
                <div class="row align-items-center" style="gap: 3rem;">
                    
                    <!-- Textos Hero -->
                    <div class="col-lg">
                        <span class="badge badge-agro-soft rounded-pill px-3 py-2 text-uppercase fw-black d-inline-flex align-items-center gap-2" style="font-size: 0.75rem;">
                            <i class="fas fa-seedling"></i> Plataforma agropecuaria integral
                        </span>

                        <h1 class="fw-black text-agro-dark mt-4" style="line-height: 1.02; font-size: clamp(2.25rem, 5vw, 4.5rem);">
                            Mercado Agrícola para conectar productos, animales y maquinaria en un solo lugar.
                        </h1>

                        <p class="fs-5 text-agro-muted mt-4" style="line-height: 1.6; max-width: 42rem;">
                            Una plataforma moderna para publicar, explorar y gestionar oportunidades del mercado agropecuario:
                            productos orgánicos, ganado, maquinaria agrícola y servicios relacionados.
                        </p>

                        <div class="d-flex flex-column flex-sm-row gap-3 mt-4 pt-2">
                            <a href="{{ route('register') }}" class="btn landing-btn-gradient rounded-2xl fw-black px-4 d-flex align-items-center justify-content-center gap-2" style="min-height: 3.5rem;">
                                Comenzar ahora <i class="fas fa-arrow-right small"></i>
                            </a>
                            <a href="{{ route('login') }}" class="btn landing-btn-outline rounded-2xl fw-black px-4 d-flex align-items-center justify-content-center" style="min-height: 3.5rem;">
                                Iniciar sesión
                            </a>
                        </div>

                        <div class="row g-3 mt-4 pt-2" style="max-width: 42rem;">
                            <div class="col-sm-6">
                                <div class="p-4 rounded-2xl bg-white border" style="border-color: rgba(47,98,31,0.1)!important; background-color: rgba(255,255,255,0.75)!important;">
                                    <strong class="d-block fs-3 fw-black text-agro-darker">4 áreas</strong>
                                    <span class="text-agro-muted d-block mt-1">Animales, maquinaria, orgánicos y servicios</span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="p-4 rounded-2xl bg-white border" style="border-color: rgba(47,98,31,0.1)!important; background-color: rgba(255,255,255,0.75)!important;">
                                    <strong class="d-block fs-3 fw-black text-agro-darker">24/7</strong>
                                    <span class="text-agro-muted d-block mt-1">Consulta publicaciones cuando lo necesites</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Imagen Hero -->
                    <div class="col-lg position-relative" style="min-height: 380px;">
                        <div class="position-absolute top-0 bottom-0 start-0 end-0 rounded-4xl overflow-hidden bg-white landing-hero-img-container m-lg-4" style="min-height: 500px;">
                            <img src="{{ asset('img/hero-agrovida.png') }}" alt="Productos y maquinaria agrícola" class="w-100 h-100" style="object-fit: cover;">
                            <div class="position-absolute landing-hero-overlay" style="top:0; bottom:0; left:0; right:0;"></div>
                            
                            <div class="position-absolute bottom-0 end-0 m-4 p-4 rounded-2xl text-white landing-hero-img-badge" style="max-width: 20rem;">
                                <span class="d-block text-white-70" style="font-size: 0.875rem;">Publicaciones activas</span>
                                <strong class="d-block fs-5 fw-black mt-1">Mercado organizado</strong>
                            </div>
                        </div>

                        <!-- Float Badge Maquinaria -->
                        <div class="position-absolute top-0 end-0 d-flex align-items-center gap-3 p-3 rounded-2xl landing-hero-float-badge mt-3 me-2" style="right: 2rem;">
                            <span class="d-flex align-items-center justify-content-center rounded-xl bg-agro text-white" style="width: 2.75rem; height: 2.75rem;">
                                <i class="fas fa-tractor"></i>
                            </span>
                            <div>
                                <strong class="d-block fw-black text-agro-dark">Maquinaria</strong>
                                <span class="small text-agro-muted d-block">Venta o alquiler</span>
                            </div>
                        </div>

                        <!-- Float Badge Orgánicos -->
                        <div class="position-absolute bottom-0 start-0 d-flex align-items-center gap-3 p-3 rounded-2xl landing-hero-float-badge mb-3 ms-2">
                            <span class="d-flex align-items-center justify-content-center rounded-xl bg-agro text-white" style="width: 2.75rem; height: 2.75rem;">
                                <i class="fas fa-leaf"></i>
                            </span>
                            <div>
                                <strong class="d-block fw-black text-agro-dark">Orgánicos</strong>
                                <span class="small text-agro-muted d-block">Productos frescos</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- SECCIÓN BENEFICIOS -->
        <section id="beneficios" class="bg-white landing-py-section">
            <div class="container-landing">
                <div class="text-center mx-auto" style="max-width: 48rem;">
                    <span class="badge badge-agro-soft rounded-pill px-3 py-2 text-uppercase fw-black" style="font-size: 0.75rem;">Beneficios</span>
                    <h2 class="fw-black text-agro-dark mt-3" style="font-size: clamp(1.875rem, 4vw, 3rem); line-height: 1.2;">Una experiencia clara para compradores, vendedores y productores.</h2>
                    <p class="fs-5 text-agro-muted mt-3" style="line-height: 1.6;">Centraliza publicaciones y mejora la forma de encontrar oportunidades dentro del mercado agropecuario.</p>
                </div>

                <div class="row g-4 mt-5">
                    @foreach ([
                        ['icon' => 'fa-box-open', 'title' => 'Publicación de productos', 'text' => 'Presenta productos agrícolas con información clara, imágenes y detalles comerciales.'],
                        ['icon' => 'fa-horse', 'title' => 'Gestión de animales', 'text' => 'Organiza información de ganado, características, ubicación y datos relevantes.'],
                        ['icon' => 'fa-tractor', 'title' => 'Maquinaria agrícola', 'text' => 'Publica maquinaria para venta, alquiler o consulta con una presentación profesional.'],
                        ['icon' => 'fa-handshake', 'title' => 'Contacto comercial', 'text' => 'Facilita la conexión entre compradores, vendedores y operadores del sector.'],
                    ] as $benefit)
                        <div class="col-md-6 col-lg-3">
                            <article class="landing-card h-100 p-4 rounded-3xl bg-white">
                                <span class="d-flex align-items-center justify-content-center landing-card-icon rounded-2xl mb-4" style="width: 3rem; height: 3rem;">
                                    <i class="fas {{ $benefit['icon'] }} fs-5"></i>
                                </span>
                                <h3 class="fs-5 fw-black text-agro-dark mt-3">{{ $benefit['title'] }}</h3>
                                <p class="text-agro-muted mt-2 mb-0" style="line-height: 1.6;">{{ $benefit['text'] }}</p>
                            </article>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- SECCIÓN CATEGORÍAS -->
        <section id="categorias" class="landing-categorias-bg landing-py-section">
            <div class="container-landing">
                <div class="row align-items-end g-4">
                    <div class="col-lg-7">
                        <span class="badge badge-agro-soft rounded-pill px-3 py-2 text-uppercase fw-black" style="font-size: 0.75rem;">Categorías</span>
                        <h2 class="fw-black text-agro-dark mt-3 mb-0" style="font-size: clamp(1.875rem, 4vw, 3rem); line-height: 1.2;">Todo el mercado agropecuario presentado con orden.</h2>
                    </div>
                    <div class="col-lg-5">
                        <p class="fs-5 text-agro-muted mb-0" style="line-height: 1.6;">Tarjetas visuales para encontrar rápidamente el tipo de publicación que cada usuario necesita.</p>
                    </div>
                </div>

                <div class="row g-4 mt-5">
                    @foreach ([
                        ['icon' => 'fa-horse', 'title' => 'Animales', 'text' => 'Ganado y publicaciones pecuarias con datos útiles para evaluar cada oportunidad.'],
                        ['icon' => 'fa-tractor', 'title' => 'Maquinaria agrícola', 'text' => 'Equipos, marcas, estados y opciones para trabajo agrícola o productivo.'],
                        ['icon' => 'fa-carrot', 'title' => 'Productos orgánicos', 'text' => 'Producción fresca, trazabilidad y productos naturales para el mercado local.'],
                        ['icon' => 'fa-bullhorn', 'title' => 'Servicios y anuncios', 'text' => 'Publicaciones relacionadas con operaciones, oferta y demanda del sector.'],
                    ] as $category)
                        <div class="col-md-6 col-lg-3">
                            <article class="landing-card h-100 p-4 rounded-3xl bg-white">
                                <span class="d-flex align-items-center justify-content-center landing-card-icon rounded-2xl mb-4" style="width: 3rem; height: 3rem;">
                                    <i class="fas {{ $category['icon'] }} fs-5"></i>
                                </span>
                                <h3 class="fs-5 fw-black text-agro-dark mt-3">{{ $category['title'] }}</h3>
                                <p class="text-agro-muted mt-2 mb-0" style="line-height: 1.6;">{{ $category['text'] }}</p>
                            </article>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- SECCIÓN CÓMO FUNCIONA -->
        <section id="como-funciona" class="bg-white landing-py-section">
            <div class="container-landing">
                <div class="row g-5">
                    <div class="col-lg-5">
                        <span class="badge badge-agro-soft rounded-pill px-3 py-2 text-uppercase fw-black" style="font-size: 0.75rem;">Cómo funciona</span>
                        <h2 class="fw-black text-agro-dark mt-3 mb-4" style="font-size: clamp(1.875rem, 4vw, 3rem); line-height: 1.2;">Encuentra, publica y organiza oportunidades del campo con facilidad.</h2>
                        <p class="fs-5 text-agro-muted" style="line-height: 1.6;">Crea tu cuenta para explorar el mercado, compartir tus productos y gestionar contactos comerciales desde un entorno claro y ordenado.</p>
                    </div>

                    <div class="col-lg-7">
                        <div class="d-flex flex-column gap-4">
                            @foreach ([
                                ['step' => '01', 'title' => 'Crea tu cuenta', 'text' => 'Regístrate para acceder a las funcionalidades del mercado agrícola.'],
                                ['step' => '02', 'title' => 'Publica o explora productos', 'text' => 'Revisa animales, maquinaria, productos orgánicos y publicaciones del mercado.'],
                                ['step' => '03', 'title' => 'Contacta y gestiona operaciones', 'text' => 'Ordena oportunidades, administra información y da seguimiento desde tu panel.'],
                            ] as $item)
                                <article class="landing-step-card d-flex flex-column flex-sm-row gap-3 gap-sm-4 p-4 rounded-3xl">
                                    <span class="d-flex align-items-center justify-content-center rounded-2xl border text-agro-darker fs-5 fw-black flex-shrink-0" style="width: 3.5rem; height: 3.5rem; border-color: rgba(47,98,31,0.15)!important; background-color: #edf7e7;">
                                        {{ $item['step'] }}
                                    </span>
                                    <div>
                                        <h3 class="fs-5 fw-black text-agro-dark mb-2">{{ $item['title'] }}</h3>
                                        <p class="text-agro-muted mb-0" style="line-height: 1.6;">{{ $item['text'] }}</p>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- SECCIÓN CTA -->
        <section class="bg-white px-3 px-sm-4 px-lg-5 pb-5">
            <div class="container-landing p-4 p-sm-5 rounded-4xl landing-cta-bg text-white d-flex flex-column flex-lg-row align-items-lg-center justify-content-lg-between gap-4">
                <div class="col-lg-8">
                    <span class="badge border rounded-pill px-3 py-2 text-uppercase fw-black text-white mb-3" style="background: rgba(255,255,255,0.1); border-color: rgba(255,255,255,0.2)!important; font-size: 0.75rem;">Mercado Agrícola</span>
                    <h2 class="fw-black mb-3" style="font-size: clamp(1.875rem, 4vw, 3rem); line-height: 1.2;">Impulsa tus publicaciones agropecuarias con una plataforma profesional.</h2>
                    <p class="fs-5 text-white-75 mb-0" style="max-width: 42rem; line-height: 1.6;">Crea tu cuenta para comenzar a explorar, publicar y organizar oportunidades del sector.</p>
                </div>
                <div class="d-flex flex-column flex-sm-row flex-lg-column gap-3 col-lg-auto">
                    <a href="{{ route('register') }}" class="btn bg-white landing-cta-btn-light rounded-2xl fw-black px-4 text-agro-darker d-flex align-items-center justify-content-center" style="min-height: 3.5rem;">
                        Registrarse
                    </a>
                    <a href="{{ route('login') }}" class="btn text-white landing-cta-btn-outline rounded-2xl fw-black px-4 d-flex align-items-center justify-content-center" style="min-height: 3.5rem;">
                        Iniciar sesión
                    </a>
                </div>
            </div>
        </section>

        <!-- FOOTER -->
        <footer class="py-5" style="background-color: #132714; color: #dfeada;">
            <div class="container-landing">
                <div class="row align-items-center gap-4">
                    <div class="col-lg">
                        <strong class="d-block fs-5 fw-black text-white">Mercado Agrícola</strong>
                        <p class="mt-2 mb-0 text-white-65" style="max-width: 28rem;">Plataforma para conectar productos, animales, maquinaria y servicios agropecuarios.</p>
                    </div>
                    <div class="col-lg-auto d-flex flex-wrap gap-3 gap-sm-4">
                        <a href="#beneficios" class="fw-bold text-decoration-none landing-footer-link">Beneficios</a>
                        <a href="#categorias" class="fw-bold text-decoration-none landing-footer-link">Categorías</a>
                        <a href="#como-funciona" class="fw-bold text-decoration-none landing-footer-link">Cómo funciona</a>
                    </div>
                    <div class="col-lg-auto text-white-60 small">
                        © {{ date('Y') }} Mercado Agrícola
                    </div>
                </div>
            </div>
        </footer>
    </main>
@endsection