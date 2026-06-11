@extends('layouts.adminlte')

@section('title', 'Editar Registro Sanitario')
@section('hide_page_title', true)

@section('content')
    <div class="sanitary-wizard-page">
        @include('datos_sanitarios._form_wizard', [
            'datoSanitario' => $datoSanitario,
            'ganados' => $ganados,
            'formAction' => route('admin.datos-sanitarios.update', $datoSanitario->id),
            'formMethod' => 'PUT',
            'mode' => 'edit',
        ])

    </div>
@endsection
