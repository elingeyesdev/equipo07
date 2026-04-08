@extends('layouts.adminlte')

@section('title', 'Editar Raza')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-3">Editar Raza</h1>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.razas.update', $raza->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label>Nombre</label>
                    <input type="text" name="nombre" class="form-control" value="{{ $raza->nombre }}" required>
                </div>

                <div class="mb-3">
                    <label>Tipo de Animal</label>
                    <select name="tipo_animal_id" class="form-control" required>
                        @foreach($tipos as $t)
                            <option value="{{ $t->id }}" {{ $raza->tipo_animal_id == $t->id ? 'selected' : '' }}>
                                {{ $t->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label>Descripción</label>
                    <textarea name="descripcion" class="form-control">{{ $raza->descripcion }}</textarea>
                </div>

                <button class="btn btn-primary">Actualizar</button>
                <a href="{{ route('admin.razas.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
</div>
@endsection
