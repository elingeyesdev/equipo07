@extends('layouts.adminlte')

@section('title', 'Carrito de Compras')
@section('page_title', 'Carrito de Compras')

@section('content')
    <style>
        .cart-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .cart-header {
            background: var(--agro) !important;
            color: white;
            padding: 2rem;
            border-radius: 15px 15px 0 0;
            margin-bottom: 0;
        }

        .cart-item-card {
            border: 1px solid #e9ecef;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
            background: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            clear: both;
            overflow: hidden;
        }

        .cart-item-card:hover {
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .cart-item-card .row {
            margin: 0;
        }

        .cart-item-card .row>div {
            padding-left: 8px;
            padding-right: 8px;
        }

        .cart-item-card .row {
            margin-left: -8px;
            margin-right: -8px;
        }

        .quantity-controls {
            margin: 0;
        }

        .product-image {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 10px;
            border: 2px solid #e9ecef;
            transition: transform 0.3s ease;
        }

        .product-image:hover {
            transform: scale(1.05);
            cursor: pointer;
        }

        .product-info h5 {
            font-size: 1.2rem;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }

        .quantity-controls {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            width: auto;
            flex-wrap: nowrap;
        }

        .quantity-btn {
            width: 35px;
            height: 35px;
            min-width: 35px;
            border-radius: 8px;
            border: 1px solid #dee2e6;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
            flex-shrink: 0;
        }

        .quantity-btn:hover {
            background: #f8f9fa;
            border-color: #28a745;
            color: #28a745;
        }

        .quantity-input {
            width: 60px;
            min-width: 60px;
            text-align: center;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 0.5rem;
            font-weight: 600;
            flex-shrink: 0;
        }

        .quantity-input:focus {
            border-color: #28a745;
            outline: none;
            box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.1);
        }

        .price-display {
            font-size: 1.3rem;
            font-weight: 700;
            color: #28a745;
        }

        .subtotal-display {
            font-size: 1.1rem;
            font-weight: 600;
            color: #2c3e50;
        }

        .cart-summary {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 15px;
            padding: 2rem;
            position: sticky;
            top: 20px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 0;
            border-bottom: 1px solid #dee2e6;
        }

        .summary-row.total {
            border-bottom: none;
            border-top: 2px solid #28a745;
            margin-top: 1rem;
            padding-top: 1rem;
            font-size: 1.3rem;
            font-weight: 700;
            color: #28a745;
        }

        .empty-cart {
            text-align: center;
            padding: 4rem 2rem;
        }

        .empty-cart i {
            font-size: 5rem;
            color: #dee2e6;
            margin-bottom: 1.5rem;
        }

        .btn-modern {
            border-radius: 10px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .btn-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }

        .badge-modern {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 500;
        }

        .action-btn {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .action-btn:hover {
            transform: scale(1.1);
        }


        @media (max-width: 768px) {
            .product-image {
                width: 100px;
                height: 100px;
            }

            .cart-item-card {
                padding: 1rem;
            }

            .cart-summary {
                position: relative;
                top: 0;
                margin-top: 2rem;
            }

            .quantity-controls {
                max-width: 100%;
            }
        }
    </style>

    <div class="container-fluid">
        <div class="cart-container">
            <div class="card shadow-sm border-0" style="border-radius: 15px; overflow: hidden;">
                <div class="cart-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-1" style="font-weight: 700;">
                                <i class="fas fa-shopping-cart mr-2"></i>Mi Carrito
                            </h3>
                            @if ($cartItems->count() > 0)
                                <p class="mb-0 text-white-50">
                                    <i class="fas fa-box mr-1"></i>{{ $cartItems->sum('cantidad') }}
                                    {{ $cartItems->sum('cantidad') == 1 ? 'producto' : 'productos' }}
                                </p>
                            @endif
                        </div>
                        @if ($cartItems->count() > 0)
                            <form action="{{ route('cart.clear') }}" method="POST" class="d-inline" id="clearCartForm">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-light btn-sm" onclick="confirmClearCart()">
                                    <i class="fas fa-trash mr-1"></i>Vaciar Carrito
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                <div class="card-body p-4">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if ($cartItems->count() > 0)
                        <div class="row">
                            <div class="col-lg-8">
                                @foreach ($cartItems as $item)
                                    @php
                                        $product = $item->product;
                                        $imageUrl = null;
                                        if ($item->product_type == 'ganado' && $product) {
                                            // Intentar obtener imagen de la relación imagenes primero, luego del campo directo
                                            if ($product->imagenes && $product->imagenes->count() > 0) {
                                                $imageUrl = asset('storage/' . $product->imagenes->first()->ruta);
                                            } elseif ($product->imagen) {
                                                $imageUrl = asset('storage/' . $product->imagen);
                                            }
                                        } elseif (
                                            $item->product_type == 'maquinaria' &&
                                            $product &&
                                            $product->imagenes &&
                                            $product->imagenes->count() > 0
                                        ) {
                                            $imageUrl = asset('storage/' . $product->imagenes->first()->ruta);
                                        } elseif (
                                            $item->product_type == 'organico' &&
                                            $product &&
                                            $product->imagenes &&
                                            $product->imagenes->count() > 0
                                        ) {
                                            $imageUrl = asset('storage/' . $product->imagenes->first()->ruta);
                                        }
                                    @endphp

                                    <div class="cart-item-card">
                                        <div class="row">
                                            <div class="col-md-2 col-4 mb-3 mb-md-0">
                                                @if ($imageUrl)
                                                    <img src="{{ $imageUrl }}"
                                                        alt="{{ $product ? $product->nombre : 'Producto' }}"
                                                        class="product-image"
                                                        onclick="window.open('{{ $imageUrl }}', '_blank')"
                                                        style="cursor: pointer;">
                                                @else
                                                    <div
                                                        class="bg-light d-flex align-items-center justify-content-center product-image">
                                                        <i class="fas fa-image fa-2x text-muted"></i>
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="col-md-4 col-8">
                                                <div class="product-info">
                                                    <h5>{{ $product ? $product->nombre : 'Producto eliminado' }}</h5>
                                                    @if ($item->product_type == 'ganado')
                                                        <span class="badge badge-info badge-modern mb-2">
                                                            <i class="fas fa-cow mr-1"></i>Animal
                                                        </span>
                                                    @elseif($item->product_type == 'maquinaria')
                                                        <span class="badge badge-warning badge-modern mb-2">
                                                            <i class="fas fa-tractor mr-1"></i>Maquinaria
                                                        </span>
                                                    @elseif($item->product_type == 'organico')
                                                        <span class="badge badge-success badge-modern mb-2">
                                                            <i class="fas fa-leaf mr-1"></i>Orgánico
                                                        </span>
                                                    @endif
                                                    @if ($item->notas)
                                                        <p class="text-muted small mt-2 mb-2">
                                                            <i class="fas fa-sticky-note mr-1"></i>{{ $item->notas }}
                                                        </p>
                                                    @endif
                                                    @if ($product)
                                                        @php
                                                            $showRoute = '';
                                                            if ($item->product_type == 'ganado') {
                                                                $showRoute = route('ganados.show', $product->id);
                                                            } elseif ($item->product_type == 'maquinaria') {
                                                                $showRoute = route('maquinarias.show', $product->id);
                                                            } elseif ($item->product_type == 'organico') {
                                                                $showRoute = route('organicos.show', $product->id);
                                                            }
                                                        @endphp
                                                        @if ($showRoute)
                                                            <a href="{{ $showRoute }}"
                                                                class="btn btn-sm btn-outline-primary mt-2" target="_blank">
                                                                <i class="fas fa-eye mr-1"></i>Ver Anuncio
                                                            </a>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="d-flex flex-wrap align-items-end gap-3" style="gap: 1rem;">
                                                    <div style="min-width: 140px;">
                                                        <label class="text-muted small mb-1 d-block"
                                                            style="font-size: 0.75rem; margin-bottom: 0.3rem;">Cantidad</label>
                                                        <form action="{{ route('cart.update', $item) }}" method="POST"
                                                            id="quantityForm{{ $item->id }}" style="margin: 0;">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="quantity-controls">
                                                                <button type="button" class="quantity-btn"
                                                                    onclick="decreaseQuantity({{ $item->id }})">
                                                                    <i class="fas fa-minus"></i>
                                                                </button>
                                                                <input type="number" name="cantidad" class="quantity-input"
                                                                    value="{{ $item->cantidad }}" min="1"
                                                                    id="quantity{{ $item->id }}"
                                                                    onchange="updateQuantity({{ $item->id }})"
                                                                    readonly>
                                                                <button type="button" class="quantity-btn"
                                                                    onclick="increaseQuantity({{ $item->id }})">
                                                                    <i class="fas fa-plus"></i>
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>

                                                    <div style="min-width: 100px;">
                                                        <label class="text-muted small mb-1 d-block"
                                                            style="font-size: 0.75rem; margin-bottom: 0.3rem;">Precio
                                                            Unit.</label>
                                                        <div class="subtotal-display"
                                                            style="font-size: 0.95rem; line-height: 1.2;">Bs
                                                            {{ number_format($item->precio_unitario, 2) }}</div>
                                                    </div>

                                                    <div style="min-width: 100px; text-align: right;">
                                                        <label class="text-muted small mb-1 d-block"
                                                            style="font-size: 0.75rem; margin-bottom: 0.3rem;">Subtotal</label>
                                                        <div class="price-display"
                                                            style="font-size: 1.1rem; line-height: 1.2;">Bs
                                                            {{ number_format($item->subtotal, 2) }}</div>
                                                    </div>

                                                    <div class="ml-auto">
                                                        <form action="{{ route('cart.remove', $item) }}" method="POST"
                                                            class="d-inline" id="removeForm{{ $item->id }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button" class="btn btn-danger btn-sm"
                                                                onclick="confirmRemoveItem({{ $item->id }})"
                                                                title="Eliminar" style="margin-top: 1.5rem;">
                                                                <i class="fas fa-trash mr-1"></i>Eliminar
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="col-lg-4">
                                <div class="cart-summary">
                                    <h4 class="mb-4 font-weight-bold">
                                        <i class="fas fa-receipt mr-2"></i>Resumen del Pedido
                                    </h4>

                                    <div class="summary-row">
                                        <span class="text-muted">Subtotal:</span>
                                        <strong>Bs {{ number_format($total, 2) }}</strong>
                                    </div>

                                    <div class="summary-row">
                                        <span class="text-muted">Productos:</span>
                                        <strong>{{ $cartItems->sum('cantidad') }}</strong>
                                    </div>

                                    <div class="summary-row total">
                                        <span>Total:</span>
                                        <span>Bs {{ number_format($total, 2) }}</span>
                                    </div>

                                    <div class="mt-4">
                                        <a href="{{ route('ads.index') }}"
                                            class="btn btn-outline-secondary btn-block btn-modern mb-3">
                                            <i class="fas fa-arrow-left mr-2"></i>Continuar Comprando
                                        </a>


                                        <button class="btn btn-success btn-block btn-modern btn-lg"
                                            onclick="proceedToCheckout()">
                                            <i class="fas fa-check mr-2"></i>Procesar Pedido
                                        </button>

                                        <form id="checkoutForm" action="{{ route('pedidos.store') }}" method="POST"
                                            style="display: none;">
                                            @csrf
                                        </form>

                                    </div>

                                    <div class="mt-3 text-center">
                                        <small class="text-muted">
                                            <i class="fas fa-shield-alt mr-1"></i>Compra segura y protegida
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="empty-cart">
                            <i class="fas fa-shopping-cart"></i>
                            <h3 class="text-muted mb-3">Tu carrito está vacío</h3>
                            <p class="text-muted mb-4">Agrega productos para comenzar a comprar</p>
                            <a href="{{ route('home') }}" class="btn btn-success btn-lg btn-modern">
                                <i class="fas fa-shopping-bag mr-2"></i>Ir a Comprar
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function increaseQuantity(itemId) {
            const input = document.getElementById('quantity' + itemId);
            const currentValue = parseInt(input.value) || 1;
            input.value = currentValue + 1;
            updateQuantity(itemId);
        }

        function decreaseQuantity(itemId) {
            const input = document.getElementById('quantity' + itemId);
            const currentValue = parseInt(input.value) || 1;
            if (currentValue > 1) {
                input.value = currentValue - 1;
                updateQuantity(itemId);
            }
        }

        function updateQuantity(itemId) {
            const form = document.getElementById('quantityForm' + itemId);
            const input = document.getElementById('quantity' + itemId);
            const value = parseInt(input.value) || 1;

            if (value < 1) {
                input.value = 1;
                return;
            }

            // Mostrar loading
            const btn = form.querySelector('button[type="submit"]') || input;
            const originalValue = input.value;

            // Enviar formulario
            form.submit();
        }

        function confirmRemoveItem(itemId) {
            if (confirm('¿Estás seguro de eliminar este producto del carrito?')) {
                document.getElementById('removeForm' + itemId).submit();
            }
        }

        function confirmClearCart() {
            if (confirm('¿Estás seguro de vaciar todo el carrito? Esta acción no se puede deshacer.')) {
                document.getElementById('clearCartForm').submit();
            }
        }

        function proceedToCheckout() {
            Swal.fire({
                title: '¿Procesar pedido?',
                text: 'Tu pedido será generado y enviado para su procesamiento.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí, procesar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('checkoutForm').submit();
                }
            });
        }
    </script>
@endsection
