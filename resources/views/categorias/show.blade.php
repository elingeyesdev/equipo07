@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h2>Detalles de la Categoría</h2>

        <div class="card">
            <div class="card-body">
                <h4>{{ $categoria->nombre }}</h4>
                <p>{{ $categoria->descripcion }}</p>
            </div>
        </div>

        <a href="{{ route('admin.categorias.index') }}" class="btn btn-secondary mt-3">Volver</a>
    </div>
@endsection
