@extends('layouts.adminlte')

@section('title', 'Reclamos')
@section('page_title', 'Reclamos')

@section('content')
    <div class="container-fluid">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <div class="d-flex flex-wrap justify-content-between align-items-center">
                    <div>
                        <h3 class="card-title font-weight-bold"><i class="fas fa-flag text-warning mr-2"></i>Reclamos de productos</h3>
                        <div class="small text-muted mt-1">{{ $reclamos->total() }} casos registrados</div>
                    </div>
                    <form method="GET" class="form-inline mt-2 mt-md-0">
                        <select name="estado" class="form-control form-control-sm mr-2">
                            <option value="">Todos los estados</option>
                            @foreach(\App\Models\Reclamo::ESTADOS as $key => $label)
                                <option value="{{ $key }}" @selected(request('estado') === $key)>{{ $label }}</option>
                            @endforeach
                        </select>
                        <button class="btn btn-sm btn-success"><i class="fas fa-filter mr-1"></i>Filtrar</button>
                    </form>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>Caso</th>
                            <th>Producto</th>
                            <th>Reportado por</th>
                            <th>Problema</th>
                            <th>Estado</th>
                            <th class="text-right">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reclamos as $reclamo)
                            <tr>
                                <td>#{{ $reclamo->id }}<br><small class="text-muted">{{ $reclamo->created_at->format('d/m/Y H:i') }}</small></td>
                                <td>{{ $reclamo->detalle->nombre_producto }}</td>
                                <td>{{ $reclamo->creador?->name }}<br><small class="text-muted">{{ ucfirst($reclamo->creador_rol) }}</small></td>
                                <td>{{ $reclamo->tipo_label }}</td>
                                <td><span class="badge badge-info">{{ $reclamo->estado_label }}</span></td>
                                <td class="text-right">
                                    <a href="{{ route('reclamos.show', $reclamo) }}" class="btn btn-sm btn-outline-success">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center text-muted py-5">No hay reclamos registrados.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($reclamos->hasPages())
                <div class="card-footer bg-white">{{ $reclamos->links() }}</div>
            @endif
        </div>
    </div>
@endsection
