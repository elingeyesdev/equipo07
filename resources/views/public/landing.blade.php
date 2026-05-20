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
    </style>

    <main class="landing-page min-h-screen bg-[#f7fbf2] text-[#142112] antialiased">
        <nav class="sticky top-0 z-50 border-b border-[#2f621f]/10 bg-[#f7fbf2]/90 backdrop-blur-xl">
            <div class="mx-auto flex min-h-20 w-full max-w-7xl items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">
                <a href="{{ route('landing') }}" class="flex min-w-max items-center gap-3 text-lg font-black text-[#183b18] no-underline">
                    <span class="flex h-11 w-11 items-center justify-center rounded-2xl border border-[#2f621f]/15 bg-white shadow-lg shadow-[#2f621f]/10">
                        <img src="{{ asset('img/logo-agrovida.png') }}" alt="Mercado Agrícola" class="h-8 w-8 object-contain">
                    </span>
                    <span>Mercado Agrícola</span>
                </a>

                <div class="hidden items-center gap-7 lg:flex">
                    <a href="#inicio" class="text-sm font-bold text-[#4b5949] no-underline transition hover:text-[#2f621f]">Inicio</a>
                    <a href="#beneficios" class="text-sm font-bold text-[#4b5949] no-underline transition hover:text-[#2f621f]">Beneficios</a>
                    <a href="#categorias" class="text-sm font-bold text-[#4b5949] no-underline transition hover:text-[#2f621f]">Categorías</a>
                    <a href="#como-funciona" class="text-sm font-bold text-[#4b5949] no-underline transition hover:text-[#2f621f]">Cómo funciona</a>
                </div>

                <div class="flex items-center gap-2">
                    <a href="{{ route('login') }}" class="inline-flex min-h-11 items-center justify-center rounded-xl border border-[#2f621f]/15 bg-white px-4 text-sm font-black text-[#183b18] no-underline shadow-sm transition hover:-translate-y-0.5 hover:shadow-lg">
                        Iniciar sesión
                    </a>
                    <a href="{{ route('register') }}" class="inline-flex min-h-11 items-center justify-center rounded-xl bg-gradient-to-br from-[#2f621f] to-[#4f8f2f] px-4 text-sm font-black text-white no-underline shadow-xl shadow-[#2f621f]/25 transition hover:-translate-y-0.5 hover:shadow-2xl">
                        Registrarse
                    </a>
                </div>
            </div>
        </nav>

        <section id="inicio" class="relative overflow-hidden bg-gradient-to-br from-[#f8fcf3] via-[#edf8e6] to-white py-16 sm:py-20 lg:py-24">
            <div class="absolute right-[-10rem] bottom-[-12rem] h-[32rem] w-[32rem] rounded-full bg-[#2f621f]/10"></div>
            <div class="absolute top-10 left-6 h-40 w-40 rounded-full bg-[#a6c85f]/20 blur-3xl"></div>

            <div class="relative mx-auto grid w-full max-w-7xl items-center gap-12 px-4 sm:px-6 lg:grid-cols-[1.06fr_0.94fr] lg:px-8">
                <div>
                    <span class="inline-flex items-center gap-2 rounded-full border border-[#2f621f]/15 bg-[#edf7e7] px-4 py-2 text-xs font-black uppercase text-[#2f621f]">
                        <i class="fas fa-seedling"></i>
                        Plataforma agropecuaria integral
                    </span>

                    <h1 class="mt-6 max-w-4xl text-4xl font-black leading-[1.02] tracking-normal text-[#142112] sm:text-5xl lg:text-7xl">
                        Mercado Agrícola para conectar productos, animales y maquinaria en un solo lugar.
                    </h1>

                    <p class="mt-6 max-w-2xl text-lg leading-8 text-[#66735f] sm:text-xl">
                        Una plataforma moderna para publicar, explorar y gestionar oportunidades del mercado agropecuario:
                        productos orgánicos, ganado, maquinaria agrícola y servicios relacionados.
                    </p>

                    <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                        <a href="{{ route('register') }}" class="inline-flex min-h-14 items-center justify-center gap-3 rounded-2xl bg-gradient-to-br from-[#2f621f] to-[#4f8f2f] px-6 text-base font-black text-white no-underline shadow-2xl shadow-[#2f621f]/25 transition hover:-translate-y-0.5">
                            Comenzar ahora
                            <i class="fas fa-arrow-right text-sm"></i>
                        </a>
                        <a href="{{ route('login') }}" class="inline-flex min-h-14 items-center justify-center rounded-2xl border border-[#2f621f]/15 bg-white px-6 text-base font-black text-[#183b18] no-underline shadow-lg shadow-black/5 transition hover:-translate-y-0.5">
                            Iniciar sesión
                        </a>
                    </div>

                    <div class="mt-8 grid max-w-2xl gap-3 sm:grid-cols-2">
                        <div class="rounded-2xl border border-[#2f621f]/10 bg-white/75 p-5 shadow-sm">
                            <strong class="block text-2xl font-black text-[#183b18]">4 áreas</strong>
                            <span class="mt-1 block leading-6 text-[#66735f]">Animales, maquinaria, orgánicos y servicios</span>
                        </div>
                        <div class="rounded-2xl border border-[#2f621f]/10 bg-white/75 p-5 shadow-sm">
                            <strong class="block text-2xl font-black text-[#183b18]">24/7</strong>
                            <span class="mt-1 block leading-6 text-[#66735f]">Consulta publicaciones cuando lo necesites</span>
                        </div>
                    </div>
                </div>

                <div class="relative min-h-[380px] sm:min-h-[500px]">
                    <div class="absolute inset-8 overflow-hidden rounded-[2rem] border border-[#2f621f]/15 bg-white shadow-2xl shadow-[#2f621f]/20 sm:inset-y-8 sm:right-0 sm:left-8">
                        <img src="{{ asset('img/hero-agrovida.png') }}" alt="Productos y maquinaria agrícola" class="h-full w-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-b from-transparent via-transparent to-[#142112]/70"></div>
                        <div class="absolute right-5 bottom-5 max-w-xs rounded-2xl border border-white/20 bg-[#142112]/75 p-5 text-white shadow-xl backdrop-blur">
                            <span class="block text-sm text-white/70">Publicaciones activas</span>
                            <strong class="mt-1 block text-xl font-black">Mercado organizado</strong>
                        </div>
                    </div>

                    <div class="absolute top-0 right-2 flex w-56 items-center gap-3 rounded-2xl border border-[#2f621f]/15 bg-white/95 p-4 shadow-2xl shadow-black/10 sm:right-8">
                        <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#2f621f] text-white">
                            <i class="fas fa-tractor"></i>
                        </span>
                        <div>
                            <strong class="block font-black text-[#142112]">Maquinaria</strong>
                            <span class="text-sm text-[#66735f]">Venta o alquiler</span>
                        </div>
                    </div>

                    <div class="absolute bottom-0 left-2 flex w-56 items-center gap-3 rounded-2xl border border-[#2f621f]/15 bg-white/95 p-4 shadow-2xl shadow-black/10 sm:left-0">
                        <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#2f621f] text-white">
                            <i class="fas fa-leaf"></i>
                        </span>
                        <div>
                            <strong class="block font-black text-[#142112]">Orgánicos</strong>
                            <span class="text-sm text-[#66735f]">Productos frescos</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="beneficios" class="bg-white py-16 sm:py-20">
            <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="mx-auto max-w-3xl text-center">
                    <span class="inline-flex rounded-full border border-[#2f621f]/15 bg-[#edf7e7] px-4 py-2 text-xs font-black uppercase text-[#2f621f]">Beneficios</span>
                    <h2 class="mt-5 text-3xl font-black leading-tight text-[#142112] sm:text-5xl">Una experiencia clara para compradores, vendedores y productores.</h2>
                    <p class="mt-5 text-lg leading-8 text-[#66735f]">Centraliza publicaciones y mejora la forma de encontrar oportunidades dentro del mercado agropecuario.</p>
                </div>

                <div class="mt-10 grid gap-5 md:grid-cols-2 lg:grid-cols-4">
                    @foreach ([
                        ['icon' => 'fa-box-open', 'title' => 'Publicación de productos', 'text' => 'Presenta productos agrícolas con información clara, imágenes y detalles comerciales.'],
                        ['icon' => 'fa-horse', 'title' => 'Gestión de animales', 'text' => 'Organiza información de ganado, características, ubicación y datos relevantes.'],
                        ['icon' => 'fa-tractor', 'title' => 'Maquinaria agrícola', 'text' => 'Publica maquinaria para venta, alquiler o consulta con una presentación profesional.'],
                        ['icon' => 'fa-handshake', 'title' => 'Contacto comercial', 'text' => 'Facilita la conexión entre compradores, vendedores y operadores del sector.'],
                    ] as $benefit)
                        <article class="rounded-3xl border border-[#2f621f]/10 bg-white p-6 shadow-xl shadow-black/[0.04] transition hover:-translate-y-1 hover:shadow-2xl">
                            <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-[#2f621f] to-[#5c9938] text-white shadow-lg shadow-[#2f621f]/20">
                                <i class="fas {{ $benefit['icon'] }}"></i>
                            </span>
                            <h3 class="mt-5 text-lg font-black text-[#142112]">{{ $benefit['title'] }}</h3>
                            <p class="mt-3 leading-7 text-[#66735f]">{{ $benefit['text'] }}</p>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>

        <section id="categorias" class="bg-gradient-to-b from-[#f4faef] to-white py-16 sm:py-20">
            <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="grid items-end gap-6 lg:grid-cols-[1fr_0.7fr]">
                    <div>
                        <span class="inline-flex rounded-full border border-[#2f621f]/15 bg-[#edf7e7] px-4 py-2 text-xs font-black uppercase text-[#2f621f]">Categorías</span>
                        <h2 class="mt-5 max-w-3xl text-3xl font-black leading-tight text-[#142112] sm:text-5xl">Todo el mercado agropecuario presentado con orden.</h2>
                    </div>
                    <p class="text-lg leading-8 text-[#66735f]">Tarjetas visuales para encontrar rápidamente el tipo de publicación que cada usuario necesita.</p>
                </div>

                <div class="mt-10 grid gap-5 md:grid-cols-2 lg:grid-cols-4">
                    @foreach ([
                        ['icon' => 'fa-horse', 'title' => 'Animales', 'text' => 'Ganado y publicaciones pecuarias con datos útiles para evaluar cada oportunidad.'],
                        ['icon' => 'fa-tractor', 'title' => 'Maquinaria agrícola', 'text' => 'Equipos, marcas, estados y opciones para trabajo agrícola o productivo.'],
                        ['icon' => 'fa-carrot', 'title' => 'Productos orgánicos', 'text' => 'Producción fresca, trazabilidad y productos naturales para el mercado local.'],
                        ['icon' => 'fa-bullhorn', 'title' => 'Servicios y anuncios', 'text' => 'Publicaciones relacionadas con operaciones, oferta y demanda del sector.'],
                    ] as $category)
                        <article class="rounded-3xl border border-[#2f621f]/10 bg-white p-6 shadow-xl shadow-black/[0.04] transition hover:-translate-y-1 hover:shadow-2xl">
                            <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-[#2f621f] to-[#5c9938] text-white shadow-lg shadow-[#2f621f]/20">
                                <i class="fas {{ $category['icon'] }}"></i>
                            </span>
                            <h3 class="mt-5 text-lg font-black text-[#142112]">{{ $category['title'] }}</h3>
                            <p class="mt-3 leading-7 text-[#66735f]">{{ $category['text'] }}</p>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>

        <section id="como-funciona" class="bg-white py-16 sm:py-20">
            <div class="mx-auto grid w-full max-w-7xl gap-10 px-4 sm:px-6 lg:grid-cols-[0.85fr_1fr] lg:px-8">
                <div>
                    <span class="inline-flex rounded-full border border-[#2f621f]/15 bg-[#edf7e7] px-4 py-2 text-xs font-black uppercase text-[#2f621f]">Cómo funciona</span>
                    <h2 class="mt-5 text-3xl font-black leading-tight text-[#142112] sm:text-5xl">Encuentra, publica y organiza oportunidades del campo con facilidad.</h2>
                    <p class="mt-5 text-lg leading-8 text-[#66735f]">Crea tu cuenta para explorar el mercado, compartir tus productos y gestionar contactos comerciales desde un entorno claro y ordenado.</p>
                </div>

                <div class="grid gap-5">
                    @foreach ([
                        ['step' => '01', 'title' => 'Crea tu cuenta', 'text' => 'Regístrate para acceder a las funcionalidades del mercado agrícola.'],
                        ['step' => '02', 'title' => 'Publica o explora productos', 'text' => 'Revisa animales, maquinaria, productos orgánicos y publicaciones del mercado.'],
                        ['step' => '03', 'title' => 'Contacta y gestiona operaciones', 'text' => 'Ordena oportunidades, administra información y da seguimiento desde tu panel.'],
                    ] as $item)
                        <article class="grid gap-4 rounded-3xl border border-[#2f621f]/10 bg-gradient-to-br from-white to-[#f7fbf2] p-6 shadow-xl shadow-black/[0.04] sm:grid-cols-[auto_1fr]">
                            <span class="flex h-14 w-14 items-center justify-center rounded-2xl border border-[#2f621f]/15 bg-[#edf7e7] text-lg font-black text-[#183b18]">{{ $item['step'] }}</span>
                            <div>
                                <h3 class="text-xl font-black text-[#142112]">{{ $item['title'] }}</h3>
                                <p class="mt-2 leading-7 text-[#66735f]">{{ $item['text'] }}</p>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="bg-white px-4 pb-16 sm:px-6 sm:pb-20 lg:px-8">
            <div class="mx-auto grid max-w-7xl items-center gap-8 rounded-[2rem] bg-[linear-gradient(135deg,rgba(24,59,24,0.98),rgba(47,98,31,0.95)),url('/img/bg-agrovida.jpg')] bg-cover bg-center p-8 text-white shadow-2xl shadow-[#183b18]/25 sm:p-12 lg:grid-cols-[1fr_auto]">
                <div>
                    <span class="inline-flex rounded-full border border-white/20 bg-white/10 px-4 py-2 text-xs font-black uppercase text-white">Mercado Agrícola</span>
                    <h2 class="mt-5 max-w-3xl text-3xl font-black leading-tight sm:text-5xl">Impulsa tus publicaciones agropecuarias con una plataforma profesional.</h2>
                    <p class="mt-5 max-w-2xl text-lg leading-8 text-white/75">Crea tu cuenta para comenzar a explorar, publicar y organizar oportunidades del sector.</p>
                </div>
                <div class="flex flex-col gap-3 sm:flex-row lg:flex-col">
                    <a href="{{ route('register') }}" class="inline-flex min-h-14 items-center justify-center rounded-2xl bg-white px-6 font-black text-[#183b18] no-underline shadow-xl transition hover:-translate-y-0.5">Registrarse</a>
                    <a href="{{ route('login') }}" class="inline-flex min-h-14 items-center justify-center rounded-2xl border border-white/25 bg-white/10 px-6 font-black text-white no-underline transition hover:-translate-y-0.5 hover:bg-white/15">Iniciar sesión</a>
                </div>
            </div>
        </section>

        <footer class="bg-[#132714] py-8 text-[#dfeada]">
            <div class="mx-auto grid w-full max-w-7xl items-center gap-6 px-4 sm:px-6 lg:grid-cols-[1fr_auto_auto] lg:px-8">
                <div>
                    <strong class="block text-lg font-black text-white">Mercado Agrícola</strong>
                    <p class="mt-2 max-w-md text-white/65">Plataforma para conectar productos, animales, maquinaria y servicios agropecuarios.</p>
                </div>
                <div class="flex flex-wrap gap-4">
                    <a href="#beneficios" class="font-bold text-[#dfeada] no-underline hover:text-white">Beneficios</a>
                    <a href="#categorias" class="font-bold text-[#dfeada] no-underline hover:text-white">Categorías</a>
                    <a href="#como-funciona" class="font-bold text-[#dfeada] no-underline hover:text-white">Cómo funciona</a>
                </div>
                <span class="text-white/60">© {{ date('Y') }} Mercado Agrícola</span>
            </div>
        </footer>
    </main>
@endsection
