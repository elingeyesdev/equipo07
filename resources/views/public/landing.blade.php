@extends('layouts.public')

@section('title', 'Mercado Agrícola')
@section('standalone_public', true)

@section('content')
    <style>
        .landing-page{
            --ink:#2c4033; --muted:#66766c; --green:#2eab5b; --green-d:#238647;
            --cream:#f8fbf8; --line:#dcebe1; --wheat:#f4a261;
            --serif: Georgia, "Times New Roman", serif;
            background:var(--cream);
            color:var(--ink);
            -webkit-font-smoothing:antialiased;
        }
        .landing-page .serif{ font-family:var(--serif); font-weight:600; letter-spacing:-.012em; }
        .landing-page .eyebrow{ font-size:.8rem; font-weight:750; letter-spacing:.15em; text-transform:uppercase; }

        html{ scroll-behavior:smooth; scroll-padding-top:5.5rem; }
        .landing-page section[id]{ scroll-margin-top:5.5rem; }

        /* Nav */
        .landing-nav{ transition: background-color .3s ease, border-color .3s ease, box-shadow .3s ease, transform .35s ease; }
        .landing-nav.is-stuck{ background-color:rgba(248,251,248,.94); border-color:rgba(44,64,51,.10); box-shadow:0 1px 0 rgba(44,64,51,.04), 0 12px 30px rgba(44,64,51,.07); backdrop-filter:saturate(1.1); }
        .landing-nav.is-hidden{ transform:translateY(-100%); }
        .landing-nav .nav-link{ position:relative; }
        .landing-nav .nav-link::after{ content:""; position:absolute; left:0; right:100%; bottom:-4px; height:1.5px; background:var(--green); transition:right .28s cubic-bezier(.2,.7,.2,1); }
        .landing-nav .nav-link:hover::after{ right:0; }
        .landing-nav .nav-link{ font-size:1rem !important; }
        .landing-nav a:first-child span:first-child{ transition:transform .3s ease, box-shadow .3s ease; }
        .landing-nav a:first-child:hover span:first-child{ transform:rotate(-4deg) scale(1.06); box-shadow:0 9px 22px rgba(46,171,91,.18); }

        /* Buttons */
        .landing-page .btn-primary{ background:var(--green); color:#fff; transition:transform .2s ease, box-shadow .2s ease, background-color .2s ease; }
        .landing-page .btn-primary:hover{ background:var(--green-d); transform:translateY(-1px); box-shadow:0 10px 22px rgba(46,171,91,.24); }
        .landing-page .btn-ghost{ transition:color .2s ease, transform .2s ease; }
        .landing-page .link-arrow i{ transition:transform .25s ease; }
        .landing-page .link-arrow:hover i{ transform:translateX(4px); }

        /* Cards */
        .landing-page .card{ transition:transform .35s cubic-bezier(.2,.7,.2,1), box-shadow .35s ease, border-color .35s ease; }
        .landing-page .card:hover{ transform:translateY(-4px); box-shadow:0 18px 40px rgba(44,64,51,.09); border-color:rgba(46,171,91,.32); }
        .landing-page .icon-tile{ transition:background-color .3s ease, color .3s ease, border-color .3s ease; }
        .landing-page .card:hover .icon-tile{ background:var(--green); color:#fff; border-color:var(--green); }
        .landing-page .card .icon-tile i{ transition:transform .35s cubic-bezier(.2,.7,.2,1); }
        .landing-page .card:hover .icon-tile i{ transform:scale(1.14) rotate(-5deg); }

        /* Hero image frame */
        .landing-page .hero-frame{ box-shadow:0 30px 60px -28px rgba(44,64,51,.38); }
        .landing-page .hero-frame img{ transition:transform 1.2s cubic-bezier(.2,.7,.2,1); }
        .landing-page .hero-frame:hover img{ transform:scale(1.03); }
        .landing-hero::before,
        .landing-hero::after{ content:""; position:absolute; pointer-events:none; border-radius:999px; filter:blur(2px); }
        .landing-hero::before{ width:28rem; height:28rem; top:-14rem; left:-12rem; background:radial-gradient(circle, rgba(46,171,91,.13), transparent 68%); }
        .landing-hero::after{ width:24rem; height:24rem; right:-10rem; bottom:-12rem; background:radial-gradient(circle, rgba(244,162,97,.12), transparent 68%); }
        .landing-hero > div{ position:relative; z-index:1; }

        /* Legibilidad de textos secundarios */
        .landing-page .text-\[\.8rem\]{ font-size:.9rem !important; }
        .landing-page .text-\[\.85rem\]{ font-size:.94rem !important; }
        .landing-page .text-\[\.88rem\]{ font-size:.97rem !important; }
        .landing-page .text-\[\.9rem\]{ font-size:1rem !important; }
        .landing-page .text-\[\.92rem\]{ font-size:1rem !important; }
        .landing-page .text-\[\.95rem\]{ font-size:1.03rem !important; }
        .landing-page .text-\[\.98rem\]{ font-size:1.04rem !important; }
        .landing-page p{ text-wrap:pretty; }

        /* Reveal on scroll */
        .landing-page [data-reveal]{ opacity:0; transform:translateY(20px); transition:opacity .7s cubic-bezier(.2,.7,.2,1), transform .7s cubic-bezier(.2,.7,.2,1); }
        .landing-page [data-reveal].is-in{ opacity:1; transform:none; }

        @media (prefers-reduced-motion: reduce){
            html{ scroll-behavior:auto; }
            .landing-nav,
            .landing-page [data-reveal],
            .landing-page .hero-frame img{ transition:none !important; }
            .landing-page [data-reveal]{ opacity:1; transform:none; }
        }
    </style>

    <main class="landing-page min-h-screen">

        <!-- NAV -->
        <nav class="landing-nav sticky top-0 z-50 border-b border-transparent" data-landing-nav>
            <div class="mx-auto flex h-[5.25rem] w-full max-w-[1216px] items-center justify-between gap-4 px-5 sm:px-7 lg:px-6 xl:px-0">
                <a href="{{ route('landing') }}" class="flex items-center gap-3 no-underline">
                    <span class="flex h-10 w-10 items-center justify-center rounded-full border border-[var(--line)] bg-white">
                        <img src="{{ asset('img/brand/logo-agrovida.jpeg') }}?v={{ filemtime(public_path('img/brand/logo-agrovida.jpeg')) }}" alt="Mercado Agrícola" class="h-7 w-7 rounded-full object-cover">
                    </span>
                    <span class="serif text-[1.15rem] text-[var(--green-d)]">Mercado Agrícola</span>
                </a>

                <div class="hidden items-center gap-9 lg:flex">
                    <a href="#inicio" class="nav-link text-[.92rem] font-medium text-[var(--muted)] no-underline hover:text-[var(--green-d)]">Inicio</a>
                    <a href="#beneficios" class="nav-link text-[.92rem] font-medium text-[var(--muted)] no-underline hover:text-[var(--green-d)]">Beneficios</a>
                    <a href="#categorias" class="nav-link text-[.92rem] font-medium text-[var(--muted)] no-underline hover:text-[var(--green-d)]">Categorías</a>
                    <a href="#como-funciona" class="nav-link text-[.92rem] font-medium text-[var(--muted)] no-underline hover:text-[var(--green-d)]">Cómo funciona</a>
                </div>

                <div class="flex items-center gap-2">
                    <a href="{{ route('transporte.index') }}" class="inline-flex min-h-11 items-center justify-center gap-2 rounded-xl border border-[#2eab5b]/20 bg-[#eaf8ef] px-4 text-sm font-black text-[#238647] no-underline transition hover:-translate-y-0.5 hover:shadow-lg">
                        <i class="fas fa-truck"></i>
                        Transporte
                    </a>
                    <a href="{{ route('login') }}" class="btn-ghost hidden min-h-10 items-center rounded-lg px-4 text-[.9rem] font-semibold text-[var(--green-d)] no-underline hover:text-[var(--green)] sm:inline-flex">Iniciar sesión</a>
                    <a href="{{ route('register') }}" class="btn-primary inline-flex min-h-10 items-center justify-center rounded-lg px-5 text-[.9rem] font-semibold no-underline">Registrarse</a>
                </div>
            </div>
        </nav>

        <!-- HERO -->
        <section id="inicio" class="landing-hero relative overflow-hidden">
            <div class="mx-auto grid w-full max-w-[1216px] items-center gap-12 px-5 py-14 sm:px-7 lg:grid-cols-[1.05fr_0.95fr] lg:gap-16 lg:px-6 lg:py-20 xl:px-0">
                <div>
                    <div class="flex items-center gap-3" data-reveal>
                        <span class="h-px w-8 bg-[var(--wheat)]"></span>
                        <span class="eyebrow text-[var(--wheat)]">Plataforma agropecuaria</span>
                    </div>

                    <h1 class="serif mt-6 text-[2.6rem] leading-[1.04] text-[var(--ink)] sm:text-[3.2rem] lg:text-[3.5rem]" data-reveal style="transition-delay:.06s">
                        El mercado del campo,<br>reunido en un solo lugar.
                    </h1>

                    <p class="mt-6 max-w-[34rem] text-[1.05rem] leading-[1.75] text-[var(--muted)]" data-reveal style="transition-delay:.12s">
                        Publica, explora y gestiona oportunidades del sector agropecuario —productos orgánicos, ganado, maquinaria y servicios— desde un entorno claro y ordenado.
                    </p>

                    <div class="mt-9 flex flex-col gap-3 sm:flex-row sm:items-center" data-reveal style="transition-delay:.18s">
                        <a href="{{ route('register') }}" class="btn-primary link-arrow inline-flex min-h-[3.25rem] items-center justify-center gap-2.5 rounded-xl px-7 text-[.98rem] font-semibold no-underline">
                            Comenzar ahora <i class="fas fa-arrow-right text-[.8rem]"></i>
                        </a>
                        <a href="#categorias" class="btn-ghost inline-flex min-h-[3.25rem] items-center justify-center gap-2 rounded-xl px-5 text-[.98rem] font-semibold text-[var(--green-d)] no-underline hover:text-[var(--green)]">
                            Ver categorías
                        </a>
                    </div>

                    <dl class="mt-12 grid max-w-[34rem] grid-cols-2 divide-x divide-[var(--line)] border-t border-[var(--line)] pt-7" data-reveal style="transition-delay:.24s">
                        <div class="pr-6">
                            <dt class="serif text-[2rem] text-[var(--green-d)]">4 áreas</dt>
                            <dd class="mt-1 text-[.92rem] leading-6 text-[var(--muted)]">Animales, maquinaria, orgánicos y servicios</dd>
                        </div>
                        <div class="pl-6">
                            <dt class="serif text-[2rem] text-[var(--green-d)]">24/7</dt>
                            <dd class="mt-1 text-[.92rem] leading-6 text-[var(--muted)]">Consulta publicaciones cuando lo necesites</dd>
                        </div>
                    </dl>
                </div>

                <div class="relative" data-reveal style="transition-delay:.12s">
                    <div class="hero-frame relative overflow-hidden rounded-[1.5rem] border border-[var(--line)] bg-white">
                        <div class="aspect-[3/2] w-full overflow-hidden sm:aspect-[16/10] lg:aspect-[4/5]">
                            <img src="{{ asset('img/hero-agrovida.png') }}" alt="Productos y maquinaria agrícola" class="h-full w-full object-cover object-center">
                        </div>
                        <span class="pointer-events-none absolute inset-0 bg-gradient-to-t from-[#2c4033]/35 via-transparent to-transparent"></span>
                    </div>

                    {{-- Tarjetas: grid estática en móvil/tablet, flotantes en desktop --}}
                    <div class="mt-5 grid grid-cols-1 gap-3 sm:grid-cols-3 lg:mt-0 lg:block">
                        @foreach ([
                            ['icon' => 'fa-tractor', 'title' => 'Maquinaria', 'text' => 'Venta o alquiler', 'pos' => 'lg:right-4 lg:top-6'],
                            ['icon' => 'fa-leaf', 'title' => 'Orgánicos', 'text' => 'Productos frescos', 'pos' => 'lg:left-4 lg:top-1/2 lg:-translate-y-1/2'],
                            ['icon' => 'fa-horse', 'title' => 'Animales', 'text' => 'Ganado y producción', 'pos' => 'lg:right-4 lg:bottom-6'],
                        ] as $card)
                            <div class="flex items-center gap-3 rounded-xl border border-[var(--line)] bg-white px-4 py-3 shadow-[0_16px_34px_-14px_rgba(44,64,51,.35)] lg:absolute lg:w-[13.5rem] {{ $card['pos'] }}">
                                <span class="flex h-10 w-10 flex-none items-center justify-center rounded-lg bg-[var(--green)] text-white"><i class="fas {{ $card['icon'] }} text-[.95rem]"></i></span>
                                <div>
                                    <strong class="block text-[.92rem] text-[var(--ink)]">{{ $card['title'] }}</strong>
                                    <span class="text-[.8rem] text-[var(--muted)]">{{ $card['text'] }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        <!-- BENEFICIOS -->
        <section id="beneficios" class="border-t border-[var(--line)] bg-white py-20 sm:py-24">
            <div class="mx-auto w-full max-w-[1216px] px-5 sm:px-7 lg:px-6 xl:px-0">
                <div class="max-w-2xl" data-reveal>
                    <div class="flex items-center gap-3">
                        <span class="h-px w-8 bg-[var(--wheat)]"></span>
                        <span class="eyebrow text-[var(--wheat)]">Beneficios</span>
                    </div>
                    <h2 class="serif mt-5 text-[2.1rem] leading-[1.1] text-[var(--ink)] sm:text-[2.7rem]">Una experiencia clara para quienes trabajan el campo.</h2>
                    <p class="mt-5 text-[1.05rem] leading-8 text-[var(--muted)]">Centraliza publicaciones y mejora la forma de encontrar oportunidades dentro del mercado agropecuario.</p>
                </div>

                <div class="mt-14 grid gap-x-8 gap-y-12 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ([
                        ['icon' => 'fa-box-open', 'num' => '01', 'title' => 'Publicación de productos', 'text' => 'Presenta productos agrícolas con información clara, imágenes y detalles comerciales.'],
                        ['icon' => 'fa-horse', 'num' => '02', 'title' => 'Gestión de animales', 'text' => 'Organiza información de ganado, características, ubicación y datos relevantes.'],
                        ['icon' => 'fa-tractor', 'num' => '03', 'title' => 'Maquinaria agrícola', 'text' => 'Publica maquinaria para venta, alquiler o consulta con una presentación profesional.'],
                        ['icon' => 'fa-handshake', 'num' => '04', 'title' => 'Contacto comercial', 'text' => 'Facilita la conexión entre compradores, vendedores y operadores del sector.'],
                    ] as $i => $benefit)
                        <article class="card group rounded-2xl border border-[var(--line)] bg-white p-7" data-reveal style="transition-delay:{{ $i * 0.08 }}s">
                            <div class="flex items-center justify-between">
                                <span class="icon-tile flex h-12 w-12 items-center justify-center rounded-xl border border-[var(--line)] bg-[#fff4eb] text-[var(--green)]"><i class="fas {{ $benefit['icon'] }} text-[1.05rem]"></i></span>
                                <span class="serif text-[1.1rem] text-[var(--line)] group-hover:text-[var(--wheat)]">{{ $benefit['num'] }}</span>
                            </div>
                            <h3 class="mt-6 text-[1.1rem] font-semibold text-[var(--ink)]">{{ $benefit['title'] }}</h3>
                            <p class="mt-2.5 text-[.95rem] leading-7 text-[var(--muted)]">{{ $benefit['text'] }}</p>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- CATEGORÍAS -->
        <section id="categorias" class="py-20 sm:py-24">
            <div class="mx-auto w-full max-w-[1216px] px-5 sm:px-7 lg:px-6 xl:px-0">
                <div class="grid items-end gap-6 lg:grid-cols-[1fr_.7fr]" data-reveal>
                    <div>
                        <div class="flex items-center gap-3">
                            <span class="h-px w-8 bg-[var(--wheat)]"></span>
                            <span class="eyebrow text-[var(--wheat)]">Categorías</span>
                        </div>
                        <h2 class="serif mt-5 max-w-2xl text-[2.1rem] leading-[1.1] text-[var(--ink)] sm:text-[2.7rem]">Todo el mercado agropecuario, presentado con orden.</h2>
                    </div>
                    <p class="text-[1.05rem] leading-8 text-[var(--muted)] lg:pb-2">Encuentra rápidamente el tipo de publicación que necesitas, sin ruido ni desorden.</p>
                </div>

                <div class="mt-14 grid gap-px overflow-hidden rounded-2xl border border-[var(--line)] bg-[var(--line)] sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ([
                        ['icon' => 'fa-horse', 'title' => 'Animales', 'text' => 'Ganado y publicaciones pecuarias con datos útiles para evaluar cada oportunidad.'],
                        ['icon' => 'fa-tractor', 'title' => 'Maquinaria agrícola', 'text' => 'Equipos, marcas, estados y opciones para trabajo agrícola o productivo.'],
                        ['icon' => 'fa-carrot', 'title' => 'Productos orgánicos', 'text' => 'Producción fresca, trazabilidad y productos naturales para el mercado local.'],
                        ['icon' => 'fa-bullhorn', 'title' => 'Servicios y anuncios', 'text' => 'Publicaciones relacionadas con operaciones, oferta y demanda del sector.'],
                    ] as $i => $category)
                        <a href="{{ route('register') }}" class="group flex flex-col bg-white p-8 no-underline transition-colors hover:bg-[#f8fbf8]" data-reveal style="transition-delay:{{ $i * 0.08 }}s">
                            <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-[var(--green-d)] text-white"><i class="fas {{ $category['icon'] }}"></i></span>
                            <h3 class="mt-6 text-[1.15rem] font-semibold text-[var(--ink)]">{{ $category['title'] }}</h3>
                            <p class="mt-2.5 flex-1 text-[.95rem] leading-7 text-[var(--muted)]">{{ $category['text'] }}</p>
                            <span class="link-arrow mt-6 inline-flex items-center gap-2 text-[.88rem] font-semibold text-[var(--green)]">Explorar <i class="fas fa-arrow-right text-[.72rem]"></i></span>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- CÓMO FUNCIONA -->
        <section id="como-funciona" class="border-t border-[var(--line)] bg-white py-20 sm:py-24">
            <div class="mx-auto grid w-full max-w-[1216px] gap-14 px-5 sm:px-7 lg:grid-cols-[.85fr_1fr] lg:px-6 xl:px-0">
                <div data-reveal>
                    <div class="flex items-center gap-3">
                        <span class="h-px w-8 bg-[var(--wheat)]"></span>
                        <span class="eyebrow text-[var(--wheat)]">Cómo funciona</span>
                    </div>
                    <h2 class="serif mt-5 text-[2.1rem] leading-[1.1] text-[var(--ink)] sm:text-[2.7rem]">Encuentra, publica y organiza oportunidades del campo.</h2>
                    <p class="mt-5 text-[1.05rem] leading-8 text-[var(--muted)]">Crea tu cuenta para explorar el mercado, compartir tus productos y gestionar contactos comerciales desde un entorno claro y ordenado.</p>
                    <a href="{{ route('register') }}" class="btn-primary link-arrow mt-8 inline-flex min-h-[3.25rem] items-center gap-2.5 rounded-xl px-7 text-[.98rem] font-semibold text-white no-underline">Crear cuenta <i class="fas fa-arrow-right text-[.8rem]"></i></a>
                </div>

                <div class="relative">
                    <span class="absolute left-[1.55rem] top-3 bottom-3 hidden w-px bg-[var(--line)] sm:block"></span>
                    <div class="grid gap-6">
                        @foreach ([
                            ['step' => '01', 'title' => 'Crea tu cuenta', 'text' => 'Regístrate para acceder a las funcionalidades del mercado agrícola.'],
                            ['step' => '02', 'title' => 'Publica o explora productos', 'text' => 'Revisa animales, maquinaria, productos orgánicos y publicaciones del mercado.'],
                            ['step' => '03', 'title' => 'Contacta y gestiona operaciones', 'text' => 'Ordena oportunidades, administra información y da seguimiento desde tu panel.'],
                        ] as $i => $item)
                            <article class="relative flex gap-5" data-reveal style="transition-delay:{{ $i * 0.1 }}s">
                                <span class="serif relative z-10 flex h-[3.1rem] w-[3.1rem] flex-none items-center justify-center rounded-full border border-[var(--line)] bg-white text-[1.15rem] text-[var(--green-d)]">{{ $item['step'] }}</span>
                                <div class="rounded-2xl border border-[var(--line)] bg-[var(--cream)] p-6">
                                    <h3 class="text-[1.15rem] font-semibold text-[var(--ink)]">{{ $item['title'] }}</h3>
                                    <p class="mt-2 text-[.95rem] leading-7 text-[var(--muted)]">{{ $item['text'] }}</p>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA -->
        <section class="px-5 pb-20 pt-4 sm:px-7 sm:pb-24 lg:px-6 xl:px-0">
            <div class="mx-auto max-w-[1216px]">
                <div class="relative overflow-hidden rounded-[1.75rem] bg-[var(--green-d)] px-8 py-14 sm:px-14 sm:py-16" data-reveal>
                    <img src="{{ asset('img/bg-agrovida.jpg') }}" alt="" class="pointer-events-none absolute inset-0 h-full w-full object-cover opacity-[.14]">
                    <div class="relative grid items-center gap-10 lg:grid-cols-[1fr_auto]">
                        <div>
                            <div class="flex items-center gap-3">
                                <span class="h-px w-8 bg-white/40"></span>
                                <span class="eyebrow text-white/70">Mercado Agrícola</span>
                            </div>
                            <h2 class="serif mt-5 max-w-2xl text-[2.1rem] leading-[1.12] text-white sm:text-[2.7rem]">Impulsa tus publicaciones agropecuarias con una plataforma profesional.</h2>
                            <p class="mt-5 max-w-xl text-[1.05rem] leading-8 text-white/75">Crea tu cuenta para comenzar a explorar, publicar y organizar oportunidades del sector.</p>
                        </div>
                        <div class="flex flex-col gap-3 sm:flex-row lg:flex-col">
                            <a href="{{ route('register') }}" class="inline-flex min-h-[3.25rem] items-center justify-center rounded-xl bg-white px-7 text-[.98rem] font-semibold text-[var(--green-d)] no-underline transition hover:-translate-y-0.5">Registrarse</a>
                            <a href="{{ route('login') }}" class="inline-flex min-h-[3.25rem] items-center justify-center rounded-xl border border-white/30 px-7 text-[.98rem] font-semibold text-white no-underline transition hover:bg-white/10">Iniciar sesión</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- FOOTER -->
        <footer class="bg-[#2c4033] py-12 text-[#eaf8ef]">
            <div class="mx-auto grid w-full max-w-[1216px] gap-8 px-5 sm:px-7 lg:grid-cols-[1.4fr_1fr_auto] lg:items-start lg:px-6 xl:px-0">
                <div>
                    <div class="flex items-center gap-3">
                        <span class="flex h-9 w-9 items-center justify-center rounded-full bg-white/10"><i class="fas fa-seedling text-white"></i></span>
                        <strong class="serif text-[1.15rem] font-normal text-white">Mercado Agrícola</strong>
                    </div>
                    <p class="mt-4 max-w-md text-[.92rem] leading-7 text-white/60">Plataforma para conectar productos, animales, maquinaria y servicios agropecuarios.</p>
                </div>
                <div class="flex flex-col gap-3">
                    <span class="eyebrow text-white/40">Secciones</span>
                    <a href="#beneficios" class="text-[.92rem] text-white/75 no-underline hover:text-white">Beneficios</a>
                    <a href="#categorias" class="text-[.92rem] text-white/75 no-underline hover:text-white">Categorías</a>
                    <a href="#como-funciona" class="text-[.92rem] text-white/75 no-underline hover:text-white">Cómo funciona</a>
                </div>
                <div class="lg:text-right">
                    <span class="text-[.85rem] text-white/45">© {{ date('Y') }} Mercado Agrícola</span>
                </div>
            </div>
        </footer>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Navegación: fija al hacer scroll y se oculta al bajar
            const nav = document.querySelector('[data-landing-nav]');
            if (nav) {
                let lastScrollY = window.scrollY;
                const updateNav = () => {
                    const y = window.scrollY;
                    nav.classList.toggle('is-stuck', y > 12);
                    nav.classList.toggle('is-hidden', y > lastScrollY && y > 120);
                    lastScrollY = Math.max(y, 0);
                };
                updateNav();
                window.addEventListener('scroll', updateNav, { passive: true });
            }

            // Animación sutil de aparición al hacer scroll
            const reveals = document.querySelectorAll('.landing-page [data-reveal]');
            if ('IntersectionObserver' in window) {
                const io = new IntersectionObserver((entries) => {
                    entries.forEach((entry) => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('is-in');
                            io.unobserve(entry.target);
                        }
                    });
                }, { threshold: 0.12, rootMargin: '0px 0px -8% 0px' });
                reveals.forEach((el) => io.observe(el));
            } else {
                reveals.forEach((el) => el.classList.add('is-in'));
            }
        });
    </script>
@endsection
