@extends('layouts.adminlte')
@section('hide_page_title', true)

@section('content')
    <div class="maquinaria-wizard-page">
        @if ($errors->any())
            <div class="alert alert-danger maquinaria-wizard-page__errors">
                <ul class="mb-0">
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
@endsection
