@extends('layouts.adminlte')

@section('title', 'Nueva Raza')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-3">Nueva Raza</h1>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.razas.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label>Nombre</label>
                    <input type="text" name="nombre" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Tipo de Animal</label>
                    <select name="tipo_animal_id" class="form-control" required>
                        <option value="">Seleccione...</option>
                        @foreach($tipos as $t)
                            <option value="{{ $t->id }}">{{ $t->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label>Descripción</label>
                    <textarea name="descripcion" class="form-control"></textarea>
                </div>

                <button class="btn btn-success">Guardar</button>
                <a href="{{ route('admin.razas.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
</div>
@endsection
