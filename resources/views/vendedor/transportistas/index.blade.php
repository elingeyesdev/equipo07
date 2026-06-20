@extends('layouts.adminlte')

@section('title', 'Mis transportistas')
@section('page_title', 'Mis transportistas')

@section('content')
    <div class="container-fluid">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle mr-1"></i>{{ session('success') }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle mr-1"></i>{{ session('error') }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Revisa los datos:</strong>
                <ul class="mb-0 pl-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row">
            <div class="col-lg-5">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-user-plus mr-1"></i>Crear transportista
                        </h3>
                    </div>
                    <form action="{{ route('vendedor.transportistas.store') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label for="name">Nombre</label>
                                <input type="text" name="name" id="name" class="form-control"
                                    value="{{ old('name') }}" required>
                            </div>

                            <div class="form-group">
                                <label for="email">Correo</label>
                                <input type="email" name="email" id="email" class="form-control"
                                    value="{{ old('email') }}" required>
                            </div>

                            <div class="form-group">
                                <label for="telefono">Telefono</label>
                                <input type="text" name="telefono" id="telefono" class="form-control"
                                    value="{{ old('telefono') }}" placeholder="Ej: 70000000" maxlength="18"
                                    inputmode="tel" pattern="(?:\+?591[\s-]?)?[0-9](?:[\s-]?[0-9]){6,7}">
                            </div>

                            <div class="form-group">
                                <label for="password">Contraseña temporal</label>
                                <input type="text" name="password" id="password" class="form-control"
                                    value="{{ old('password') }}" required minlength="8" maxlength="72">
                                <small class="text-muted">
                                    Dale esta contraseña al transportista para que pueda iniciar sesion.
                                </small>
                            </div>

                            <div class="form-group mb-0">
                                <label for="password_confirmation">Confirmar contraseña</label>
                                <input type="text" name="password_confirmation" id="password_confirmation"
                                    class="form-control" value="{{ old('password_confirmation') }}" required minlength="8" maxlength="72">
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save mr-1"></i>Crear transportista
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex flex-wrap justify-content-between align-items-center">
                            <h3 class="card-title mb-2 mb-md-0">
                                <i class="fas fa-truck mr-1"></i>Transportistas creados
                            </h3>
                            <form method="GET" action="{{ route('vendedor.transportistas.index') }}" class="form-inline">
                                <input type="text" name="q" class="form-control form-control-sm mr-2"
                                    placeholder="Buscar" value="{{ request('q') }}">
                                <button type="submit" class="btn btn-sm btn-outline-success">
                                    <i class="fas fa-search mr-1"></i>Filtrar
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Contacto</th>
                                        <th>Envios</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($transportistas as $transportista)
                                        <tr>
                                            <td>
                                                <strong>{{ $transportista->name }}</strong><br>
                                                <small class="text-muted">Transportista</small>
                                            </td>
                                            <td>
                                                {{ $transportista->email }}<br>
                                                @if ($transportista->telefono)
                                                    <small><i class="fas fa-phone-alt mr-1"></i>{{ $transportista->telefono }}</small>
                                                @else
                                                    <small class="text-muted">Sin telefono</small>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge badge-primary">{{ $transportista->transportista_envios_count }}</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted py-4">
                                                <i class="fas fa-info-circle mr-1"></i>Aun no creaste transportistas.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    @if ($transportistas->hasPages())
                        <div class="card-footer">
                            {{ $transportistas->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
