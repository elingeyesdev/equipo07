@extends('layouts.adminlte')

@section('title', 'Reclamo #' . $reclamo->id)
@section('page_title', 'Reclamo #' . $reclamo->id)

@section('content')
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success"><i class="fas fa-check-circle mr-1"></i>{{ session('success') }}</div>
        @endif
        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-white d-flex justify-content-between">
                        <strong>{{ $reclamo->tipo_label }}</strong>
                        <span class="badge badge-info">{{ $reclamo->estado_label }}</span>
                    </div>
                    <div class="card-body">
                        <dl class="row mb-0">
                            <dt class="col-sm-4">Producto</dt><dd class="col-sm-8">{{ $reclamo->detalle->nombre_producto }}</dd>
                            <dt class="col-sm-4">Tipo</dt><dd class="col-sm-8">{{ ucfirst($reclamo->detalle->product_type) }}</dd>
                            <dt class="col-sm-4">Pedido</dt><dd class="col-sm-8">#{{ $reclamo->detalle->pedido_id }}</dd>
                            <dt class="col-sm-4">Reportado por</dt><dd class="col-sm-8">{{ $reclamo->creador?->name }} ({{ $reclamo->creador_rol }})</dd>
                            <dt class="col-sm-4">Comprador</dt><dd class="col-sm-8">{{ $reclamo->detalle->pedido->user?->name }}</dd>
                            <dt class="col-sm-4">Vendedor</dt><dd class="col-sm-8">{{ $reclamo->detalle->vendedor?->name }}</dd>
                        </dl>
                        <hr>
                        <h6 class="font-weight-bold">Descripción</h6>
                        <p class="mb-0" style="white-space:pre-wrap">{{ $reclamo->descripcion }}</p>
                        @if($reclamo->detalle->cancelacion_motivo)
                            <div class="alert alert-warning mt-3 mb-0">
                                <strong>Motivo registrado por transporte:</strong>
                                {{ $reclamo->detalle->cancelacion_motivo }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-white font-weight-bold">Seguimiento del caso</div>
                    <div class="card-body">
                        @if(auth()->user()->isAdmin())
                            <form method="POST" action="{{ route('admin.reclamos.update', $reclamo) }}">
                                @csrf
                                @method('PATCH')
                                <div class="form-group">
                                    <label>Estado</label>
                                    <select name="estado" class="form-control">
                                        @foreach(\App\Models\Reclamo::ESTADOS as $key => $label)
                                            <option value="{{ $key }}" @selected($reclamo->estado === $key)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Observación del administrador</label>
                                    <textarea name="respuesta_admin" class="form-control" rows="5" maxlength="2000">{{ $reclamo->respuesta_admin }}</textarea>
                                </div>
                                <button class="btn btn-success btn-block"><i class="fas fa-save mr-1"></i>Guardar seguimiento</button>
                            </form>
                        @elseif($reclamo->respuesta_admin)
                            <p class="mb-0">{{ $reclamo->respuesta_admin }}</p>
                        @else
                            <p class="text-muted mb-0">El caso está pendiente de revisión administrativa.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <a href="{{ route('reclamos.index') }}" class="btn btn-light"><i class="fas fa-arrow-left mr-1"></i>Volver</a>
    </div>
@endsection
