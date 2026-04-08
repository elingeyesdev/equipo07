@extends('layouts.adminlte')

@section('content')
<div class="card">
    <div class="card-header">Nuevo Tipo de Animal</div>

    <div class="card-body">
            <form action="{{ route('admin.tipo_animals.store') }}" method="post">
            @csrf

            <div class="form-group">
                <label>Nombre *</label>
                <input type="text" class="form-control" name="nombre" required>
            </div>

            <div class="form-group">
                <label>Descripción</label>
                <textarea class="form-control" name="descripcion"></textarea>
            </div>

            <button class="btn btn-success">Guardar</button>
            <a href="{{ route('admin.tipo_animals.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
@endsection
