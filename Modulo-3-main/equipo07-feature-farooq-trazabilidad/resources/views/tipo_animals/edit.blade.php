@extends('layouts.adminlte')

@section('content')
<div class="card">
    <div class="card-header">Editar Tipo de Animal</div>

    <div class="card-body">
            <form action="{{ route('admin.tipo_animals.update', $tipoAnimal) }}" method="post">
            @csrf @method('PUT')

            <div class="form-group">
                <label>Nombre *</label>
                <input type="text" class="form-control" name="nombre" value="{{ $tipoAnimal->nombre }}" required>
            </div>

            <div class="form-group">
                <label>Descripción</label>
                <textarea class="form-control" name="descripcion">{{ $tipoAnimal->descripcion }}</textarea>
            </div>

            <button class="btn btn-primary">Actualizar</button>
            <a href="{{ route('admin.tipo_animals.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
@endsection
