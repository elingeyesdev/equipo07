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

        <form action="{{ route('organicos.store') }}" method="post" enctype="multipart/form-data">
            @include('organicos._form', ['organico' => null])
        </form>
    </div>
@endsection
