<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'AgroVida')</title>

    <link rel="stylesheet" href="{{ asset('vendor/adminlte/plugins/fontawesome-free/css/all.min.css') }}">

    @if (View::hasSection('standalone_public'))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <link rel="stylesheet" href="{{ asset('vendor/adminlte/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
        <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/custom.css') }}?v={{ filemtime(public_path('css/custom.css')) }}">
    @endif
</head>

<body class="hold-transition layout-top-nav {{ request()->routeIs('login', 'register') ? 'no-topbar' : '' }} {{ View::hasSection('standalone_public') ? 'public-standalone-shell' : '' }}">
    <div class="wrapper">

        @if (!View::hasSection('standalone_public') && !request()->routeIs('login', 'register'))
            <nav class="main-header navbar navbar-expand-lg navbar-white navbar-light border-0 project-topbar">
                <div class="container">
                    <a href="{{ route('home') }}" class="navbar-brand d-flex align-items-center">
                        <img src="{{ asset('img/logo-agrovida.png') }}" alt="AgroVida" style="height:34px">
                        <span class="brand-text font-weight-bold ml-2 text-white">AgroVida Bolivia</span>
                    </a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#topnav">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div id="topnav" class="collapse navbar-collapse">
                        <ul class="navbar-nav ml-auto">
                            <li class="nav-item">
                                <a class="nav-link text-white {{ request()->routeIs('home') ? 'active' : '' }}"
                                    href="{{ route('home') }}">
                                    <i class="fas fa-home"></i> Inicio
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white {{ request()->routeIs('ads.index') ? 'active' : '' }}"
                                    href="{{ route('ads.index') }}">
                                    <i class="fas fa-bullhorn"></i> Anuncios
                                </a>
                            </li>
                            @auth
                                @if (auth()->user()->isVendedor() || auth()->user()->isAdmin())
                                    <li class="nav-item">
                                        <a class="nav-link text-white {{ request()->routeIs('productos-venta.*') ? 'active' : '' }}"
                                            href="{{ route('productos-venta.index') }}">
                                            <i class="fas fa-store"></i> {{ auth()->user()->isAdmin() ? 'Productos' : 'Mis productos' }}
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link text-white"
                                            href="{{ auth()->user()->isAdmin() ? route('admin.pedidos.index') : route('vendedor.solicitudes.index') }}">
                                            <i class="fas fa-clipboard-list"></i> Solicitudes
                                        </a>
                                    </li>
                                @endif
                                @if (auth()->user()->isCliente())
                                    <li class="nav-item">
                                        <a class="nav-link text-white {{ request()->routeIs('solicitar-vendedor') ? 'active' : '' }}"
                                            href="{{ route('solicitar-vendedor') }}">
                                            <i class="fas fa-user-tie"></i> Ser Vendedor
                                        </a>
                                    </li>
                                @endif
                                <li class="nav-item">
                                    <a class="nav-link text-white {{ request()->routeIs('cart.*') ? 'active' : '' }}"
                                        href="{{ route('cart.index') }}">
                                        <i class="fas fa-shopping-cart"></i> Carrito
                                        @php
                                            $cartCount = \App\Models\CartItem::where('user_id', auth()->id())->sum(
                                                'cantidad',
                                            );
                                        @endphp
                                        @if ($cartCount > 0)
                                            <span class="badge badge-danger badge-sm"
                                                id="cart-count">{{ $cartCount }}</span>
                                        @endif
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('pedidos.index') }}">
                                        <i class="fas fa-receipt"></i> Mis Pedidos
                                    </a>
                                </li>
                            @else
                                <li class="nav-item">
                                    <a class="nav-link text-white" href="{{ route('solicitar-vendedor') }}">
                                        <i class="fas fa-user-tie"></i> Ser Vendedor
                                    </a>
                                </li>
                            @endauth
                            @auth
                                <li class="nav-item dropdown">
                                    <a class="nav-link text-white dropdown-toggle" href="#" data-toggle="dropdown">
                                        <i class="fas fa-user-circle"></i> {{ auth()->user()->name }}
                                        <span class="badge badge-light ml-1">{{ auth()->user()->role_name }}</span>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a href="{{ route('home') }}" class="dropdown-item">
                                            <i class="fas fa-home mr-2"></i> Inicio
                                        </a>
                                        @if (auth()->user()->isCliente())
                                            <a href="{{ route('solicitar-vendedor') }}" class="dropdown-item">
                                                <i class="fas fa-user-tie mr-2"></i> Ser Vendedor
                                            </a>
                                        @endif
                                        @if (auth()->user()->isAdmin())
                                            <a href="{{ route('admin.solicitudes-vendedor.index') }}"
                                                class="dropdown-item">
                                                <i class="fas fa-clipboard-list mr-2"></i> Panel Admin
                                            </a>
                                        @endif
                                        <a href="{{ route('reclamos.index') }}" class="dropdown-item">
                                            <i class="fas fa-flag mr-2"></i> Reclamos
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="dropdown-item">
                                                <i class="fas fa-sign-out-alt mr-2"></i> Cerrar Sesión
                                            </button>
                                        </form>
                                    </div>
                                </li>
                            @else
                                <li class="nav-item">
                                    <a class="nav-link text-white" href="{{ route('login') }}">
                                        <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link btn btn-success text-white px-3 ml-lg-2"
                                        href="{{ route('register') }}">
                                        Registrarse <i class="fas fa-user-plus ml-1"></i>
                                    </a>
                                </li>
                            @endauth
                        </ul>
                    </div>
                </div>
            </nav>
        @endif


        <div class="{{ View::hasSection('standalone_public') ? 'public-standalone-content' : 'content-wrapper bg-white' }}">
            @yield('content')
        </div>

        @unless (View::hasSection('standalone_public'))
            <footer class="main-footer text-sm">
                <div class="container">
                    <strong>© {{ date('Y') }} AgroVida.</strong> Tu mercado agrícola.
                    @unless (request()->routeIs('login', 'register'))
                        <span class="float-right d-none d-sm-inline">Hecho con AdminLTE 3</span>
                    @endunless
                </div>
            </footer>
        @endunless
    </div>

    @unless (View::hasSection('standalone_public'))
        <script src="{{ asset('vendor/adminlte/plugins/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('vendor/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('vendor/adminlte/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
        <script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>
    @endunless
</body>

</html>
