<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'AgroVida')</title>

    <link rel="stylesheet" href="{{ asset('vendor/adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/form-validation.css') }}?v={{ filemtime(public_path('css/form-validation.css')) }}">

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
                        <img src="{{ asset('img/logo-agrovida.png') }}?v={{ filemtime(public_path('img/logo-agrovida.png')) }}" alt="AgroVida" class="project-topbar__logo">
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
                                            @if ($navPendingRequestsCount > 0)
                                                <span class="nav-notification-badge"
                                                    aria-label="{{ $navPendingRequestsCount }} solicitudes pendientes">
                                                    {{ $navPendingRequestsCount > 99 ? '99+' : $navPendingRequestsCount }}
                                                </span>
                                            @endif
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
                                        @if ($navCartCount > 0)
                                            <span class="nav-notification-badge" id="cart-count"
                                                aria-label="{{ $navCartCount }} productos en el carrito">
                                                {{ $navCartCount > 99 ? '99+' : $navCartCount }}
                                            </span>
                                        @endif
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link text-white {{ request()->routeIs('pedidos.*') ? 'active' : '' }}"
                                        href="{{ route('pedidos.index') }}">
                                        <i class="fas fa-receipt"></i> Mis Pedidos
                                        @if ($navOrderAlertsCount > 0)
                                            <span class="nav-notification-badge"
                                                aria-label="{{ $navOrderAlertsCount }} entregas por confirmar">
                                                {{ $navOrderAlertsCount > 99 ? '99+' : $navOrderAlertsCount }}
                                            </span>
                                        @endif
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
                                <li class="nav-item dropdown account-menu">
                                    <a class="nav-link text-white account-menu__trigger" href="#" data-toggle="dropdown"
                                        aria-haspopup="true">
                                        <span class="account-menu__avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                                        <span class="d-none d-lg-flex account-menu__identity">
                                            <strong>{{ auth()->user()->name }}</strong>
                                            <small>{{ auth()->user()->role_name }}</small>
                                        </span>
                                        <i class="fas fa-chevron-down account-menu__chevron"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right account-menu__panel">
                                        <div class="account-menu__header">
                                            <span class="account-menu__avatar account-menu__avatar--large">
                                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                            </span>
                                            <span>
                                                <strong>{{ auth()->user()->name }}</strong>
                                                <small>{{ auth()->user()->email }}</small>
                                            </span>
                                        </div>
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
                                                @if ($navSellerApplicationsCount > 0)
                                                    <span class="account-menu__count">
                                                        {{ $navSellerApplicationsCount > 99 ? '99+' : $navSellerApplicationsCount }}
                                                    </span>
                                                @endif
                                            </a>
                                        @endif
                                        <a href="{{ route('reclamos.index') }}" class="dropdown-item">
                                            <i class="fas fa-flag mr-2"></i> Reclamos
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="dropdown-item account-menu__logout">
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
    <script src="{{ asset('js/form-validation.js') }}?v={{ filemtime(public_path('js/form-validation.js')) }}"></script>
</body>

</html>
