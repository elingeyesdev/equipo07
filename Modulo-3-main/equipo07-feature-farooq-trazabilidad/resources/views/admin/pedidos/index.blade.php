@extends('layouts.adminlte')

@section('title', 'Pedidos')
@section('page_title', 'Pedidos')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">
                    <i class="fas fa-receipt mr-2"></i>Pedidos
                </h3>

                <form class="form-inline" method="GET">
                    <input type="text" name="q" class="form-control form-control-sm mr-2" placeholder="Buscar cliente"
                        value="{{ request('q') }}">

                    <select name="estado" class="form-control form-control-sm mr-2">
                        <option value="">Todos los estados</option>
                        @foreach ($estados as $key => $label)
                            <option value="{{ $key }}" {{ request('estado') == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>

                    <input type="date" name="fecha_desde" class="form-control form-control-sm mr-2"
                        value="{{ request('fecha_desde') }}">
                    <input type="date" name="fecha_hasta" class="form-control form-control-sm mr-2"
                        value="{{ request('fecha_hasta') }}">

                    <button class="btn btn-success btn-sm" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Fecha</th>
                                <th>Cliente</th>
                                <th>Total</th>
                                <th>Estado</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pedidos as $pedido)
                                <tr>
                                    <td>{{ $pedido->id }}</td>
                                    <td>{{ $pedido->created_at->format('d/m/Y H:i') }}</td>
                                    <td>{{ $pedido->user->name }}<br><small
                                            class="text-muted">{{ $pedido->user->email }}</small></td>
                                    <td>Bs {{ number_format($pedido->total, 2) }}</td>
                                    <td>
                                        <span
                                            class="badge badge-pill 
                  @if ($pedido->estado == 'pendiente') badge-warning
                  @elseif($pedido->estado == 'en_proceso') badge-info
                  @elseif($pedido->estado == 'entregado') badge-success
                  @else badge-danger @endif">
                                            {{ ucfirst(str_replace('_', ' ', $pedido->estado)) }}
                                        </span>
                                    </td>
                                    <td class="text-right">
                                        <a href="{{ route('admin.pedidos.show', $pedido) }}"
                                            class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i> Ver
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted p-4">
                                        No hay pedidos registrados.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if ($pedidos->hasPages())
                <div class="card-footer">
                    {{ $pedidos->links() }}
                </div>
            @endif

        </div>
    </div>
@endsection
