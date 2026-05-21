@extends('layouts.adminlte')

@section('title', 'Nueva Categoría')

@section('content')
    <div class="container-fluid">
        <div class="card category-form-card">
            <div class="card-header">
                <strong>
                    <i class="fas fa-tag mr-1"></i>
                    Nueva Categoría
                </strong>
            </div>

            <div class="card-body">
                <form action="{{ route('admin.categorias.store') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="nombre">Nombre *</label>
                        <input type="text" id="nombre" name="nombre"
                            class="form-control @error('nombre') is-invalid @enderror"
                            value="{{ old('nombre') }}" required>
                        @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="descripcion">Descripción</label>
                        <textarea id="descripcion" name="descripcion"
                            class="form-control @error('descripcion') is-invalid @enderror"
                            rows="3">{{ old('descripcion') }}</textarea>
                        @error('descripcion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="category-form-actions">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save mr-1"></i> Guardar
                        </button>
                        <a href="{{ route('admin.categorias.index') }}" class="btn btn-secondary">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
