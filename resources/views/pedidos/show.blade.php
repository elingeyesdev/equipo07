@extends('layouts.adminlte')

@section('title', 'Detalle del Pedido')
@section('page_title', 'Detalle del Pedido')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="card-title">
                        Pedido #{{ $pedido->id }}
                    </h3>
                    <p class="mb-0 text-muted">Fecha: {{ $pedido->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <span class="badge badge-info text-uppercase">{{ $pedido->estado }}</span>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <h5>Productos</h5>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Tipo</th>
                            <th>Cantidad</th>
                            <th>Precio Unit.</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pedido->detalles as $detalle)
                            <tr>
                                <td>{{ $detalle->nombre_producto }}</td>
                                <td>{{ ucfirst($detalle->product_type) }}</td>
                                <td>{{ $detalle->cantidad }}</td>
                                <td>Bs {{ number_format($detalle->precio_unitario, 2) }}</td>
                                <td>Bs {{ number_format($detalle->subtotal, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="text-right mt-3">
                    <h4>Total: Bs {{ number_format($pedido->total, 2) }}</h4>
                </div>

                <a href="{{ route('pedidos.index') }}" class="btn btn-secondary mt-3">
                    <i class="fas fa-arrow-left mr-1"></i> Volver a mis pedidos
                </a>
            </div>
        </div>
    </div>
@endsection
