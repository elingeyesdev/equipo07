@extends('layouts.adminlte')

@section('title', 'Pedido #' . $pedido->id)
@section('page_title', 'Pedido #' . $pedido->id)

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="card-title mb-0">
                        Pedido #{{ $pedido->id }}
                    </h3>
                    <small class="text-muted">
                        Fecha: {{ $pedido->created_at->format('d/m/Y H:i') }} |
                        Cliente: {{ $pedido->user->name }} ({{ $pedido->user->email }})
                    </small>
                </div>

                <form action="{{ route('admin.pedidos.updateEstado', $pedido) }}" method="POST" class="form-inline">
                    @csrf
                    @method('PUT')
                    <label class="mr-2 mb-0">Estado:</label>
                    <select name="estado" class="form-control form-control-sm mr-2">
                        @foreach ($estados as $key => $label)
                            <option value="{{ $key }}" {{ $pedido->estado == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    <button class="btn btn-sm btn-success" type="submit">
                        <i class="fas fa-save mr-1"></i>Actualizar
                    </button>
                </form>
            </div>

            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle mr-1"></i>{{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                    </div>
                @endif

                <h5>Productos del pedido</h5>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Tipo</th>
                                <th>Cantidad</th>
                                <th>Precio unit.</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pedido->detalles as $detalle)
                                <tr>
                                    <td>
                                        {{ $detalle->nombre_producto }}
                                        @if ($detalle->notas)
                                            <br><small class="text-muted">{{ $detalle->notas }}</small>
                                        @endif
                                    </td>
                                    <td>{{ ucfirst($detalle->product_type) }}</td>
                                    <td>{{ $detalle->cantidad }}</td>
                                    <td>Bs {{ number_format($detalle->precio_unitario, 2) }}</td>
                                    <td>Bs {{ number_format($detalle->subtotal, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="text-right mt-3">
                    <h4>Total: Bs {{ number_format($pedido->total, 2) }}</h4>
                </div>
            </div>

            <div class="card-footer">
                <a href="{{ route('admin.pedidos.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-1"></i> Volver a la lista
                </a>
            </div>
        </div>
    </div>
@endsection
