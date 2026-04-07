@extends('layouts.gentelella')

@section('content')
    <div class="x_panel">
        <div class="x_title">
            <h2>Editar Categoría</h2>
            <div class="clearfix"></div>
        </div>

        <div class="x_content">
            <form action="{{ route('admin.categorias.update', $categoria) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="nombre">Nombre</label>
                    <input type="text" class="form-control" name="nombre" value="{{ $categoria->nombre }}" required>
                </div>

                <div class="form-group">
                    <label for="descripcion">Descripción</label>
                    <textarea class="form-control" name="descripcion" rows="3">
                    {{ $categoria->descripcion }}
                </textarea>
                </div>

                <button type="submit" class="btn btn-primary">Actualizar</button>
                <a href="{{ route('admin.categorias.index') }}" class="btn btn-secondary">Cancelar</a>

            </form>
        </div>
    </div>
@endsection
