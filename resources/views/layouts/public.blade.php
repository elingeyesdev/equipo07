<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'AgroVida')</title>

    <link rel="stylesheet" href="{{ asset('vendor/adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">

    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
</head>

<body class="hold-transition layout-top-nav {{ request()->routeIs('login', 'register') ? 'no-topbar' : '' }}">
    <div class="wrapper">

        @if (!request()->routeIs('login', 'register'))
            <nav class="main-header navbar navbar-expand navbar-white navbar-light border-0 project-topbar">
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
                                        <a class="nav-link text-white {{ request()->routeIs('admin.datos-sanitarios.*') ? 'active' : '' }}"
                                            href="{{ route('admin.datos-sanitarios.index') }}">
                                            <i class="fas fa-syringe"></i> Datos Sanitarios
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


        <div class="content-wrapper bg-white">
            @yield('content')
        </div>

        <footer class="main-footer text-sm">
            <div class="container">
                <strong>© {{ date('Y') }} AgroVida.</strong> Tu mercado agrícola.
                <span class="float-right d-none d-sm-inline">Hecho con AdminLTE 3</span>
            </div>
        </footer>
    </div>

    <script src="{{ asset('vendor/adminlte/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/adminlte/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
    <script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>




<style>
    .chatbot-float-container { position: fixed; bottom: 25px; right: 25px; z-index: 9999; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
    .chat-card { display: none; width: 330px; height: 450px; background: #fff; border-radius: 15px; box-shadow: 0 12px 28px rgba(0,0,0,0.25); flex-direction: column; margin-bottom: 20px; border: 1px solid #ddd; overflow: hidden; }
    .chat-header { background: #2ecc71; color: #fff; padding: 15px; display: flex; justify-content: space-between; align-items: center; }
    .chat-body { flex: 1; padding: 15px; overflow-y: auto; background: #f9f9f9; display: flex; flex-direction: column; gap: 12px; }
    .msg { padding: 10px 15px; border-radius: 12px; font-size: 14px; line-height: 1.4; max-width: 85%; }
    .msg-bot { background: #e1f7e7; color: #27ae60; align-self: flex-start; border-bottom-left-radius: 2px; }
    .msg-user { background: #3498db; color: #fff; align-self: flex-end; border-bottom-right-radius: 2px; }
    .chat-footer { padding: 10px; background: #fff; border-top: 1px solid #eee; display: flex; gap: 8px; }
    .chat-footer input { flex: 1; border: 1px solid #ccc; border-radius: 25px; padding: 8px 15px; outline: none; font-size: 14px; }
    .chat-footer input:focus { border-color: #2ecc71; }
    .btn-send { background: #2ecc71; color: white; border: none; width: 38px; height: 38px; border-radius: 50%; cursor: pointer; transition: background 0.3s; }
    .btn-send:hover { background: #27ae60; }
    .fab-button { width: 65px; height: 65px; background: #2ecc71; color: white; border-radius: 50%; border: none; font-size: 28px; cursor: pointer; shadow: 0 5px 15px rgba(46,204,113,0.4); display: flex; align-items: center; justify-content: center; transition: transform 0.3s; }
    .fab-button:hover { transform: scale(1.1) rotate(5deg); }
</style>

<div class="chatbot-float-container">
    <div id="agro-chat-window" class="chat-card">
        <div class="chat-header">
            <span><i class="fas fa-seedling"></i> AgroAsistente IA</span>
            <button onclick="toggleAgroChat()" style="background:none; border:none; color:white; cursor:pointer;"><i class="fas fa-times"></i></button>
        </div>
        <div id="agro-messages" class="chat-body">
            <div class="msg msg-bot">¡Hola! Soy tu asistente inteligente. Puedo ayudarte a encontrar productos orgánicos, maquinaria o ganado. ¿Qué necesitas?</div>
        </div>
        <div class="chat-footer">
            <input type="text" id="agro-input" placeholder="Escribe tu consulta..." onkeypress="if(event.key === 'Enter') sendAgroMessage()">
            <button onclick="sendAgroMessage()" id="agro-send-btn" class="btn-send"><i class="fas fa-paper-plane"></i></button>
        </div>
    </div>
    <button onclick="toggleAgroChat()" class="fab-button"><i class="fas fa-robot"></i></button>
</div>



<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
    function toggleAgroChat() {
        const win = document.getElementById('agro-chat-window');
        win.style.display = (win.style.display === 'flex') ? 'none' : 'flex';
    }

    function sendAgroMessage() {
        const input = document.getElementById('agro-input');
        const msg = input.value.trim();
        if (!msg) return;

        const container = document.getElementById('agro-messages');
        const btn = document.getElementById('agro-send-btn');

        // Mensaje del usuario
        container.innerHTML += `<div class="msg msg-user">${msg}</div>`;
        input.value = '';
        container.scrollTop = container.scrollHeight;

        // Estado de "Cargando..."
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

axios.post('/chat-bot', { message: msg }, {
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }
        })
        .then(res => {
            // Respuesta exitosa normal
            container.innerHTML += `<div class="msg msg-bot">${res.data.reply}</div>`;
        })
        .catch(error => {
            let errorText = "Error desconocido";
            
            // Verificamos si Laravel logró enviarnos el error atrapado en nuestro JSON
            if (error.response && error.response.data && error.response.data.reply) {
                errorText = error.response.data.reply;
            } 
            // Si Laravel nos botó por Token CSRF o ruta inexistente (HTML gigante)
            else if (error.response) {
                errorText = `HTTP [${error.response.status}]: El servidor rechazó la conexión. ¿Expiró la página? (Refresca con F5)`;
            } 
            // Si ni siquiera hay respuesta del servidor
            else {
                errorText = error.message;
            }

            // IMPRESIÓN FORZADA EN LA BURBUJA
            container.innerHTML += `
                <div class="msg msg-bot" style="background:#fee2e2; color:#991b1b; border: 2px solid #ef4444; font-family: monospace;">
                    <b>⚙️ MODO DEBUG:</b><br><br>${errorText}
                </div>`;
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-paper-plane"></i>';
            container.scrollTop = container.scrollHeight;
        });
    }
</script>

    
</body>

</html>
