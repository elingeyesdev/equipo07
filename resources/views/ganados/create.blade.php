@extends('layouts.adminlte')
@section('hide_page_title', true)

@section('content')
    <div class="agro-wizard-page">
        @if ($errors->any())
            <div class="alert alert-danger agro-wizard-page__errors">
                <ul class="mb-0">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form action="{{ route('ganados.store') }}" method="POST" enctype="multipart/form-data">
            @include('ganados._form', ['ganado' => null])
        </form>
    </div>
@endsection