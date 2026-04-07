@extends('layouts.adminlte')
@section('content')
    <div class="x_panel">
        <div class="x_title">
            <h2>Nueva Maquinaria</h2>
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
            <form action="{{ route('maquinarias.store') }}" method="post" enctype="multipart/form-data">
                @include('maquinarias._form', ['maquinaria' => null])
            </form>
        </div>
    </div>
@endsection
