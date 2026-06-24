@extends('layouts.public')
@section('title', 'Crear cuenta')

@section('content')
    <main class="auth-page">
        <div class="auth-shell auth-shell--register" aria-label="Registro en AgroVida">
            <section class="auth-hero" aria-label="Crear cuenta en AgroVida Bolivia">
                <img src="{{ asset('img/bg-agrovida.jpg') }}" alt="" class="auth-hero-image">
                <div class="auth-hero-overlay"></div>

                <div class="auth-hero-content">
                    <div class="auth-hero-brand">
                        <img src="{{ asset('img/brand/logo-agrovida.jpeg') }}" alt="AgroVida" class="auth-hero-logo">
                        <span>AgroVida Bolivia</span>
                    </div>

                    <div class="auth-hero-copy">
                        <span class="auth-hero-kicker">Comienza en el mercado agrícola</span>
                        <h1>Crea tu cuenta y accede a oportunidades para el agro.</h1>
                        <p>
                            Regístrate para comprar, vender o gestionar productos agrícolas, ganado y maquinaria desde una
                            experiencia clara y segura.
                        </p>
                    </div>

                    <div class="auth-hero-badges" aria-label="Beneficios de registro">
                        <span><i class="fas fa-user-check"></i> Acceso por rol</span>
                        <span><i class="fas fa-store"></i> Mercado activo</span>
                        <span><i class="fas fa-shield-alt"></i> Plataforma segura</span>
                    </div>
                </div>
            </section>

            <section class="auth-form-panel" aria-label="Formulario de registro">
                <div class="auth-login-card auth-register-card">
                    <div class="auth-login-brand">
                        <img src="{{ asset('img/brand/logo-agrovida.jpeg') }}" alt="AgroVida" class="auth-login-logo">
                    </div>

                    <div class="auth-login-header">
                        <span class="auth-login-kicker">Nueva cuenta</span>
                        <h2>Crear cuenta</h2>
                        <p>Completa tus datos para comenzar en AgroVida.</p>
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
                                <i class="fas fa-exclamation-triangle"></i> Por favor, corrige los siguientes errores:
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

                    <form action="{{ route('register.post') }}" method="POST" class="auth-login-form">
                        @csrf

                        <div class="form-group auth-field">
                            <label>Nombre Completo *</label>
                            <div class="auth-input-wrap">
                                <i class="fas fa-user"></i>
                                <input type="text" name="name"
                                    class="form-control form-control-lg auth-input @error('name') is-invalid @enderror"
                                    placeholder="Escribir nombre" value="{{ old('name') }}" required autofocus>
                            </div>
                            @error('name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group auth-field">
                            <label>Correo Electrónico *</label>
                            <div class="auth-input-wrap">
                                <i class="fas fa-envelope"></i>
                                <input type="email" name="email"
                                    class="form-control form-control-lg auth-input @error('email') is-invalid @enderror"
                                    placeholder="correo@ejemplo.com" value="{{ old('email') }}" required>
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
                                    placeholder="Contraseña" required minlength="8">
                            </div>
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="auth-help">Mínimo 8 caracteres.</small>
                        </div>

                        <div class="form-group auth-field">
                            <label>Confirmar Contraseña *</label>
                            <div class="auth-input-wrap">
                                <i class="fas fa-lock"></i>
                                <input type="password" name="password_confirmation"
                                    class="form-control form-control-lg auth-input" placeholder="Confirmar contraseña"
                                    required minlength="8">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success btn-lg btn-block auth-button">
                            <i class="fas fa-user-plus"></i> Crear Cuenta
                        </button>

                        <p class="auth-login-footer">
                            ¿Ya tienes cuenta? <a href="{{ route('login') }}">Inicia sesión</a>
                        </p>
                    </form>
                </div>
            </section>
        </div>
    </main>
@endsection
