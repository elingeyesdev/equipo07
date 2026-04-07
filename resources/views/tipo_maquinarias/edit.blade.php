@extends('layouts.adminlte')

@section('title', 'Editar Tipo de Maquinaria')
@section('page_title', 'Editar Tipo de Maquinaria')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Editar Tipo de Maquinaria</h3>
    </div>

    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                <h5><i class="fas fa-exclamation-triangle"></i> Por favor, corrige los siguientes errores:</h5>
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

        <form action="{{ route('admin.tipo_maquinarias.update', $tipoMaquinaria) }}" method="post">
            @csrf @method('PUT')

            <div class="form-group">
                <label for="nombre">Nombre *</label>
                <input type="text" 
                       class="form-control @error('nombre') is-invalid @enderror" 
                       name="nombre" 
                       id="nombre"
                       value="{{ old('nombre', $tipoMaquinaria->nombre) }}" 
                       required>
                @error('nombre')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="form-text text-muted">Nombre único del tipo de maquinaria</small>
            </div>

            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                          name="descripcion" 
                          id="descripcion" 
                          rows="3">{{ old('descripcion', $tipoMaquinaria->descripcion) }}</textarea>
                @error('descripcion')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="form-text text-muted">Descripción opcional del tipo de maquinaria</small>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Actualizar
                </button>
                <a href="{{ route('admin.tipo_maquinarias.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

