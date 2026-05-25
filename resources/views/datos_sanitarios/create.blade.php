@extends('layouts.adminlte')

@section('title', 'Nuevo Registro Sanitario')
@section('hide_page_title', true)

@section('content')
    <div class="sanitary-wizard-page">
        @include('datos_sanitarios._form_wizard', [
            'datoSanitario' => null,
            'ganados' => $ganados,
            'formAction' => route('admin.datos-sanitarios.store'),
            'formMethod' => 'POST',
            'mode' => 'create',
        ])
    </div>
@endsection
