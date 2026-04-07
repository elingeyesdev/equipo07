@extends('layouts.adminlte')

@section('title', 'Nuevo Tipo de Peso')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-3">Nuevo Tipo de Peso</h1>

    <div class="card shadow-sm">
        <div class="card-body">

            <form action="{{ route('admin.tipo-pesos.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Nombre</label>
                    <input type="text" name="nombre" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Descripción</label>
                    <textarea name="descripcion" class="form-control"></textarea>
                </div>

                <button class="btn btn-success">
                    <i class="fas fa-save"></i> Guardar
                </button>

                <a href="{{ route('admin.tipo-pesos.index') }}" class="btn btn-secondary">
                    Cancelar
                </a>
            </form>

        </div>
    </div>
</div>
@endsection
