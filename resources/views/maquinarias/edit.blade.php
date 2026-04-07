@extends('layouts.adminlte')
@section('content')
    <div class="x_panel">
        <div class="x_title">
            <h2>Editar Maquinaria</h2>
        </div>
        <div class="x_content">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('maquinarias.update', $maquinaria) }}" method="post" enctype="multipart/form-data">
                @method('PUT')
                @include('maquinarias._form', ['maquinaria' => $maquinaria])
            </form>
        </div>
    </div>
@endsection
