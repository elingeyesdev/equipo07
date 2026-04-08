@extends('layouts.adminlte')

@section('content')
    <div class="x_panel">
        <div class="x_title">
            <h2>Nuevo Orgánico</h2>
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

            <form action="{{ route('organicos.store') }}" method="post" enctype="multipart/form-data">
                @include('organicos._form', ['organico' => null])
            </form>
        </div>
    </div>
@endsection
