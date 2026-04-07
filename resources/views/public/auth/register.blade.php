@extends('layouts.public')
@section('title', 'Crear cuenta')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="card shadow-sm rounded-lg">
                    <div class="card-body p-4 p-md-5">
                        <div class="text-center mb-3">
                            <img src="{{ asset('img/logo-agrovida.png') }}" alt="AgroVida" style="height:64px">
                        </div>
                        <h5 class="text-center mb-1">Crear cuenta</h5>
                        <p class="text-center text-muted mb-4">Completa tus datos para comenzar</p>

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

                        <form action="{{ route('register.post') }}" method="POST">
                            @csrf

                            <div class="form-group">
                                <label class="mb-1">Nombre Completo *</label>
                                <input type="text" name="name"
                                    class="form-control form-control-lg login-input @error('name') is-invalid @enderror"
                                    placeholder="Escribir Nombre" value="{{ old('name') }}" required autofocus>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="mb-1">Correo Electrónico *</label>
                                <input type="email" name="email"
                                    class="form-control form-control-lg login-input @error('email') is-invalid @enderror"
                                    placeholder="Correo Electrónico" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="mb-1">Contraseña *</label>
                                <input type="password" name="password"
                                    class="form-control form-control-lg login-input @error('password') is-invalid @enderror"
                                    placeholder="Contraseña" required minlength="8">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Mínimo 8 caracteres.</small>
                            </div>

                            <div class="form-group">
                                <label class="mb-1">Confirmar Contraseña *</label>
                                <input type="password" name="password_confirmation"
                                    class="form-control form-control-lg login-input" placeholder="Confirmar Contraseña"
                                    required minlength="8">
                            </div>

                            <button type="submit" class="btn btn-success btn-lg btn-block">
                                <i class="fas fa-user-plus"></i> Crear Cuenta
                            </button>

                            <p class="text-center mt-3 mb-0">
                                ¿Ya tienes cuenta?
                                <a href="{{ route('login') }}" class="font-weight-bold">Inicia sesión</a>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
