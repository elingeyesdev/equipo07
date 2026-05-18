@extends('layouts.public')
@section('title', 'Iniciar sesión')

@section('content')
    <main class="auth-page">
        <div class="auth-shell" aria-label="Acceso a AgroVida">
            <section class="auth-hero" aria-label="AgroVida Bolivia">
                <img src="{{ asset('img/bg-agrovida.jpg') }}" alt="" class="auth-hero-image">
                <div class="auth-hero-overlay"></div>

                <div class="auth-hero-content">
                    <div class="auth-hero-brand">
                        <img src="{{ asset('img/logo-agrovida.png') }}" alt="AgroVida" class="auth-hero-logo">
                        <span>AgroVida Bolivia</span>
                    </div>

                    <div class="auth-hero-copy">
                        <span class="auth-hero-kicker">Mercado agrícola integrado</span>
                        <h1>Conecta tu producción con un mercado más organizado.</h1>
                        <p>
                            Gestiona productos, animales y maquinaria desde una plataforma clara, segura y pensada para el
                            agro boliviano.
                        </p>
                    </div>

                    <div class="auth-hero-badges" aria-label="Categorías disponibles">
                        <span><i class="fas fa-seedling"></i> Orgánicos</span>
                        <span><i class="fas fa-horse"></i> Ganado</span>
                        <span><i class="fas fa-tractor"></i> Maquinaria</span>
                    </div>
                </div>
            </section>

            <section class="auth-form-panel" aria-label="Formulario de inicio de sesión">
                <div class="auth-login-card">
                    <div class="auth-login-brand">
                        <img src="{{ asset('img/logo-agrovida.png') }}" alt="AgroVida" class="auth-login-logo">
                    </div>

                    <div class="auth-login-header">
                        <span class="auth-login-kicker">Acceso seguro</span>
                        <h2>Iniciar sesión</h2>
                        <p>Accede con tu cuenta para continuar. Según tu rol, te llevaremos a tu panel.</p>
                    </div>

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle"></i> {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <h6 class="alert-heading">
                                <i class="fas fa-exclamation-triangle"></i> Errores de validación:
                            </h6>
                            <ul class="mb-0 small">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <form action="{{ route('login.post') }}" method="POST" class="auth-login-form">
                        @csrf

                        <div class="form-group auth-field">
                            <label>Correo Electrónico *</label>
                            <div class="auth-input-wrap">
                                <i class="fas fa-envelope"></i>
                                <input type="email" name="email"
                                    class="form-control form-control-lg auth-input @error('email') is-invalid @enderror"
                                    placeholder="correo@ejemplo.com" value="{{ old('email') }}" required autofocus>
                            </div>
                            @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group auth-field">
                            <label>Contraseña *</label>
                            <div class="auth-input-wrap">
                                <i class="fas fa-lock"></i>
                                <input type="password" name="password"
                                    class="form-control form-control-lg auth-input @error('password') is-invalid @enderror"
                                    placeholder="Ingresa tu contraseña" required>
                            </div>
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="auth-options">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="remember" name="remember">
                                <label class="custom-control-label" for="remember">Recuérdame</label>
                            </div>
                            <a href="#">¿Olvidaste tu contraseña?</a>
                        </div>

                        <button type="submit" class="btn btn-success btn-lg btn-block auth-button">
                            <i class="fas fa-sign-in-alt"></i> Entrar
                        </button>

                        <p class="auth-login-footer">
                            ¿No tienes cuenta? <a href="{{ route('register') }}">Crear cuenta</a>
                        </p>
                    </form>
                </div>
            </section>
        </div>
    </main>
@endsection
