@extends('layouts.adminlte')

@section('title', 'Solicitar ser Vendedor')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Solicitud para ser Vendedor</h1>
        <a href="{{ route('home') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="fas fa-info-circle"></i> {{ session('info') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <h5 class="alert-heading">
                <i class="fas fa-exclamation-triangle"></i> Por favor, corrige los siguientes errores:
            </h5>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header bg-primary">
            <h3 class="card-title">
                <i class="fas fa-user-tie"></i> Formulario de Solicitud
            </h3>
        </div>
        <div class="card-body">
            <p class="text-muted mb-4">
                Completa el siguiente formulario para solicitar convertirte en vendedor. 
                Un administrador revisará tu solicitud y te notificará la decisión.
            </p>

            <form action="{{ route('solicitar-vendedor.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group mb-3">
                    <label for="motivo">Motivo de la Solicitud *</label>
                    <textarea name="motivo" id="motivo" class="form-control @error('motivo') is-invalid @enderror" 
                              rows="5" minlength="10" maxlength="1000"
                              placeholder="Explica por qué quieres ser vendedor...">{{ old('motivo') }}</textarea>
                    @error('motivo')
                        <div class="invalid-feedback d-block">
                            <strong>{{ $message }}</strong>
                        </div>
                    @enderror
                    <small class="form-text text-muted">Mínimo 10 caracteres. Describe tu experiencia o interés en vender productos agrícolas.</small>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="telefono">Teléfono *</label>
                            <input type="text" name="telefono" id="telefono" 
                                   class="form-control @error('telefono') is-invalid @enderror" 
                                   value="{{ old('telefono') }}" maxlength="20"
                                   placeholder="Ej: 70012345">
                            @error('telefono')
                                <div class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="documento">Número de Documento</label>
                            <input type="text" name="documento" id="documento" 
                                   class="form-control @error('documento') is-invalid @enderror" 
                                   value="{{ old('documento') }}" maxlength="255"
                                   placeholder="Opcional">
                            @error('documento')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label for="direccion">Dirección Completa *</label>
                    <input type="text" name="direccion" id="direccion" 
                           class="form-control @error('direccion') is-invalid @enderror" 
                           value="{{ old('direccion') }}" maxlength="255"
                           placeholder="Ej: Calle Principal #123, Ciudad, Departamento">
                    @error('direccion')
                        <div class="invalid-feedback d-block">
                            <strong>{{ $message }}</strong>
                        </div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="archivo_documento">Documento Adjunto (Opcional)</label>
                    <div class="custom-file">
                        <input type="file" name="archivo_documento" id="archivo_documento" 
                               class="custom-file-input @error('archivo_documento') is-invalid @enderror"
                               accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                        <label class="custom-file-label" for="archivo_documento">
                            Seleccionar archivo (PDF, DOC, DOCX, JPG, PNG - Máx. 5MB)
                        </label>
                    </div>
                    @error('archivo_documento')
                        <div class="invalid-feedback d-block">
                            <strong>{{ $message }}</strong>
                        </div>
                    @enderror
                    <small class="form-text text-muted">
                        Puedes adjuntar un documento que respalde tu solicitud (cédula, certificado, etc.)
                    </small>
                </div>

                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Importante:</strong> Solo puedes tener una solicitud pendiente a la vez. 
                    Si ya enviaste una solicitud, espera la respuesta del administrador.
                </div>

                <div class="form-group mb-0">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-paper-plane"></i> Enviar Solicitud
                    </button>
                    <a href="{{ route('home') }}" class="btn btn-secondary btn-lg">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Actualizar label del input file
    document.querySelector('.custom-file-input').addEventListener('change', function(e) {
        var fileName = e.target.files[0]?.name || 'Seleccionar archivo';
        e.target.nextElementSibling.textContent = fileName;
    });
</script>
@endsection

