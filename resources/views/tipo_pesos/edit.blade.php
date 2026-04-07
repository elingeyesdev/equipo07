@extends('layouts.adminlte')

@section('title', 'Editar Tipo de Peso')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-3">Editar Tipo de Peso</h1>

    <div class="card shadow-sm">
        <div class="card-body">

            <form action="{{ route('admin.tipo-pesos.update', $tipoPeso->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Nombre</label>
                    <input type="text" name="nombre" class="form-control" value="{{ $tipoPeso->nombre }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Descripción</label>
                    <textarea name="descripcion" class="form-control">{{ $tipoPeso->descripcion }}</textarea>
                </div>

                <button class="btn btn-primary">
                    <i class="fas fa-save"></i> Actualizar
                </button>

                <a href="{{ route('admin.tipo-pesos.index') }}" class="btn btn-secondary">
                    Cancelar
                </a>
            </form>

        </div>
    </div>
</div>
@endsection
