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

        .cart-header h1,
        .cart-header h2,
        .cart-header h3,
        .cart-header h4,
        .cart-header h5,
        .cart-header h6,
        .cart-header p,
        .cart-header i {
            color: #fff !important;
        }

        .cart-header .text-white-50 {
            color: rgba(255, 255, 255, .82) !important;
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

        .checkout-map {
            height: 520px;
            width: 100%;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            overflow: hidden;
        }

        .checkout-map-suggestions {
            max-height: 220px;
            overflow-y: auto;
        }

        .checkout-destination-status {
            min-height: 38px;
        }

        @media (max-width: 768px) {
            .checkout-map {
                height: 420px;
            }
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

        .cart-product-modal .modal-content {
            border: 0;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 24px 60px rgba(0, 0, 0, 0.22);
        }

        .cart-product-modal .modal-header {
            background: #f7fbf4;
            border-bottom: 1px solid rgba(63, 126, 42, 0.14);
        }

        .cart-product-modal .modal-title {
            color: #172114;
            font-weight: 800;
        }

        .cart-product-preview {
            display: grid;
            grid-template-columns: minmax(240px, .8fr) minmax(0, 1.2fr);
            gap: 1.25rem;
        }

        .cart-product-preview__media {
            min-height: 300px;
            border: 1px solid #e4eadf;
            border-radius: 12px;
            overflow: hidden;
            background: #eef4ea;
        }

        .cart-product-preview__media img,
        .cart-product-preview__empty {
            width: 100%;
            height: 100%;
            min-height: 300px;
        }

        .cart-product-preview__media img {
            object-fit: cover;
        }

        .cart-product-preview__empty {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: .75rem;
            color: #6b776b;
            font-weight: 700;
        }

        .cart-product-preview__empty i {
            color: var(--agro);
            font-size: 2.4rem;
        }

        .cart-product-preview__badges {
            display: flex;
            flex-wrap: wrap;
            gap: .45rem;
            margin: .7rem 0 1rem;
        }

        .cart-product-preview__price {
            display: flex;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 1rem;
            padding: .9rem;
            background: #fff7d7;
            border-left: 4px solid #ffb300;
            border-radius: 12px;
        }

        .cart-product-preview__price small {
            color: #6b776b;
            font-weight: 800;
        }

        .cart-product-preview__price strong {
            color: #8a6500;
            font-size: 1.25rem;
            font-weight: 900;
        }

        .cart-product-preview__details {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: .65rem;
            margin: 0 0 1rem;
        }

        .cart-product-preview__details div {
            padding: .75rem;
            border: 1px solid #e4eadf;
            border-radius: 10px;
            background: #fff;
        }

        .cart-product-preview__details dt {
            color: #667466;
            font-size: .72rem;
            font-weight: 900;
            letter-spacing: .04em;
            text-transform: uppercase;
        }

        .cart-product-preview__details dd {
            margin-bottom: 0;
            color: #25321f;
            font-weight: 700;
        }

        .cart-product-preview__description {
            padding-top: 1rem;
            border-top: 1px solid #e4eadf;
        }

        .cart-product-preview__description span {
            color: #667466;
            font-size: .72rem;
            font-weight: 900;
            letter-spacing: .04em;
            text-transform: uppercase;
        }

        .cart-product-preview__description p {
            margin: .35rem 0 0;
            color: #4f5d4b;
            line-height: 1.55;
        }

        @media (max-width: 768px) {
            .cart-product-preview,
            .cart-product-preview__details {
                grid-template-columns: 1fr;
            }
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
                                        $alquilerUnidad = null;
                                        $quantityLabel = 'Cantidad';
                                        $unitPriceLabel = 'Precio Unit.';
                                        if ($item->product_type === 'maquinaria') {
                                            $alquilerUnidad = $item->alquiler_unidad
                                                ?: (str_contains(strtolower($item->notas ?? ''), 'día') ? 'dia' : 'hora');
                                            $quantityLabel = $alquilerUnidad === 'dia' ? 'Días' : 'Horas';
                                            $unitPriceLabel = $alquilerUnidad === 'dia' ? 'Precio por día' : 'Precio por hora';
                                        }
                                        $productTypeLabel = match ($item->product_type) {
                                            'ganado' => 'Animal',
                                            'maquinaria' => 'Maquinaria',
                                            'organico' => 'Orgánico',
                                            default => 'Producto',
                                        };
                                        $productTypeIcon = match ($item->product_type) {
                                            'ganado' => 'fas fa-cow',
                                            'maquinaria' => 'fas fa-tractor',
                                            'organico' => 'fas fa-leaf',
                                            default => 'fas fa-box',
                                        };
                                        $productBadgeClass = match ($item->product_type) {
                                            'ganado' => 'badge-info',
                                            'maquinaria' => 'badge-warning',
                                            'organico' => 'badge-success',
                                            default => 'badge-secondary',
                                        };
                                        $productDescription = $product->descripcion ?? 'Sin descripción registrada.';
                                        $modalDetails = [
                                            $quantityLabel => $item->cantidad,
                                            $unitPriceLabel => 'Bs ' . number_format($item->precio_unitario, 2),
                                            'Subtotal' => 'Bs ' . number_format($item->subtotal, 2),
                                        ];

                                        if ($item->product_type === 'maquinaria' && $product) {
                                            $modalDetails = array_merge([
                                                'Tipo' => optional($product->tipoMaquinaria)->nombre,
                                                'Marca' => optional($product->marcaMaquinaria)->nombre,
                                                'Estado' => optional($product->estadoMaquinaria)->nombre,
                                                'Teléfono' => $product->telefono,
                                                'Ubicación' => $product->ubicacion ?: ($product->ciudad ?: $product->municipio),
                                            ], $modalDetails);
                                        } elseif ($item->product_type === 'ganado' && $product) {
                                            $modalDetails = array_merge([
                                                'Tipo' => optional($product->tipoAnimal)->nombre,
                                                'Raza' => optional($product->raza)->nombre,
                                                'Categoría' => optional($product->categoria)->nombre,
                                                'Ubicación' => $product->ubicacion,
                                            ], $modalDetails);
                                        } elseif ($item->product_type === 'organico' && $product) {
                                            $modalDetails = array_merge([
                                                'Categoría' => optional($product->categoria)->nombre,
                                                'Unidad' => optional($product->unidad)->nombre,
                                                'Stock' => $product->stock,
                                                'Ubicación' => $product->ubicacion,
                                            ], $modalDetails);
                                        }
                                        if ($item->product_type == 'ganado' && $product) {
                                            if ($product->imagenes && $product->imagenes->count() > 0) {
                                                $imageUrl = asset('storage/' . $product->imagenes->first()->ruta);
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
                                                        <button type="button" class="btn btn-sm btn-outline-primary mt-2"
                                                            data-toggle="modal"
                                                            data-target="#cartProductModal{{ $item->id }}">
                                                            <i class="fas fa-eye mr-1"></i>Ver Anuncio
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="d-flex flex-wrap align-items-end gap-3" style="gap: 1rem;">
                                                    <div style="min-width: 140px;">
                                                        <label class="text-muted small mb-1 d-block"
                                                            style="font-size: 0.75rem; margin-bottom: 0.3rem;">{{ $quantityLabel }}</label>
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
                                                            style="font-size: 0.75rem; margin-bottom: 0.3rem;">{{ $unitPriceLabel }}</label>
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

                                    @if ($product)
                                        <div class="modal fade cart-product-modal"
                                            id="cartProductModal{{ $item->id }}" tabindex="-1" role="dialog"
                                            aria-labelledby="cartProductModalLabel{{ $item->id }}"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <div>
                                                            <h5 class="modal-title"
                                                                id="cartProductModalLabel{{ $item->id }}">
                                                                <i class="{{ $productTypeIcon }} mr-2"></i>{{ $product->nombre }}
                                                            </h5>
                                                            <small class="text-muted">Resumen del anuncio agregado al carrito</small>
                                                        </div>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Cerrar">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="cart-product-preview">
                                                            <div class="cart-product-preview__media">
                                                                @if ($imageUrl)
                                                                    <img src="{{ $imageUrl }}"
                                                                        alt="{{ $product->nombre }}">
                                                                @else
                                                                    <div class="cart-product-preview__empty">
                                                                        <i class="fas fa-image"></i>
                                                                        <span>Sin imagen disponible</span>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div>
                                                                <h4 class="font-weight-bold mb-0">{{ $product->nombre }}</h4>
                                                                <div class="cart-product-preview__badges">
                                                                    <span class="badge {{ $productBadgeClass }} badge-modern">
                                                                        <i class="{{ $productTypeIcon }} mr-1"></i>{{ $productTypeLabel }}
                                                                    </span>
                                                                </div>
                                                                <div class="cart-product-preview__price">
                                                                    <small>{{ $unitPriceLabel }}</small>
                                                                    <strong>Bs {{ number_format($item->precio_unitario, 2) }}</strong>
                                                                </div>
                                                                <dl class="cart-product-preview__details">
                                                                    @foreach ($modalDetails as $label => $value)
                                                                        <div>
                                                                            <dt>{{ $label }}</dt>
                                                                            <dd>{{ is_scalar($value) && $value !== '' ? $value : '-' }}</dd>
                                                                        </div>
                                                                    @endforeach
                                                                </dl>
                                                                <div class="cart-product-preview__description">
                                                                    <span>Descripción</span>
                                                                    <p>{{ $productDescription }}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-outline-secondary"
                                                            data-dismiss="modal">Cerrar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
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

                                        <form id="checkoutForm" action="{{ route('pedidos.store') }}" method="POST">
                                            @csrf
                                            <div class="form-group">
                                                <label for="destino_entrega" class="font-weight-bold">
                                                    <i class="fas fa-map-marker-alt mr-1"></i>Destino y detalles
                                                </label>
                                                <div id="destino-status"
                                                    class="alert alert-light border checkout-destination-status py-2 px-3 mb-2">
                                                    <span class="text-muted">Aun no seleccionaste un punto en el mapa.</span>
                                                </div>
                                                <button type="button" class="btn btn-outline-success btn-block mb-2"
                                                    data-toggle="modal" data-target="#destinoMapModal">
                                                    <i class="fas fa-map-marked-alt mr-1"></i>Seleccionar en mapa
                                                </button>
                                                <textarea name="destino_entrega" id="destino_entrega"
                                                    class="form-control @error('destino_entrega') is-invalid @enderror" rows="3"
                                                    placeholder="Escribe detalles extra para el vendedor: referencia, horario, contacto o instrucciones"
                                                    required>{{ old('destino_entrega') }}</textarea>
                                                <input type="hidden" name="destino_latitud" id="destino_latitud"
                                                    value="{{ old('destino_latitud') }}" required>
                                                <input type="hidden" name="destino_longitud" id="destino_longitud"
                                                    value="{{ old('destino_longitud') }}" required>
                                                @error('destino_entrega')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                @if ($errors->has('destino_latitud') || $errors->has('destino_longitud'))
                                                    <div class="text-danger small mt-1">Debes marcar el destino exacto en el mapa.</div>
                                                @endif
                                                <small class="form-text text-muted">
                                                    El punto exacto se marca en el mapa. Este campo es solo para detalles extra escritos por ti.
                                                </small>
                                            </div>

                                            <div class="form-group">
                                                <label for="telefono_contacto" class="font-weight-bold">
                                                    <i class="fas fa-phone-alt mr-1"></i>Telefono de contacto
                                                </label>
                                                <input type="tel" name="telefono_contacto" id="telefono_contacto"
                                                    class="form-control @error('telefono_contacto') is-invalid @enderror"
                                                    value="{{ old('telefono_contacto') }}"
                                                    placeholder="Ej: 70000000" required>
                                                @error('telefono_contacto')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                <small class="form-text text-muted">
                                                    Este numero se mostrara al vendedor para coordinar la entrega o alquiler.
                                                </small>
                                            </div>

                                            <button type="button" class="btn btn-success btn-block btn-modern btn-lg"
                                                onclick="proceedToCheckout()">
                                                <i class="fas fa-check mr-2"></i>Procesar Pedido
                                            </button>
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

    @if ($cartItems->count() > 0)
        <div class="modal fade" id="destinoMapModal" tabindex="-1" role="dialog" aria-labelledby="destinoMapModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="destinoMapModalLabel">
                            <i class="fas fa-map-marker-alt mr-1"></i>Seleccionar destino exacto
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="input-group mb-2">
                            <input type="search" id="buscar-destino" class="form-control"
                                placeholder="Busca una zona, comunidad o direccion">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-outline-secondary" id="btn-buscar-destino"
                                    title="Buscar en el mapa">
                                    <i class="fas fa-search"></i>
                                </button>
                                <button type="button" class="btn btn-outline-secondary" id="btn-destino-actual"
                                    title="Usar mi ubicacion actual">
                                    <i class="fas fa-location-arrow"></i>
                                </button>
                            </div>
                        </div>
                        <div id="sugerencias-destino" class="list-group checkout-map-suggestions mb-2"
                            style="display: none;"></div>
                        <div id="checkout-map" class="checkout-map"></div>
                        <small class="form-text text-muted">
                            Busca una direccion, usa tu ubicacion actual o haz clic en el mapa para marcar el destino.
                        </small>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-success" id="btn-confirmar-destino">
                            <i class="fas fa-check mr-1"></i>Usar este destino
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($cartItems->count() > 0)
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    @endif

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

        document.addEventListener('DOMContentLoaded', function() {
            var mapElement = document.getElementById('checkout-map');

            if (!mapElement || typeof L === 'undefined') {
                return;
            }

            var latInput = document.getElementById('destino_latitud');
            var lngInput = document.getElementById('destino_longitud');
            var destinoInput = document.getElementById('destino_entrega');
            var destinoStatus = document.getElementById('destino-status');
            var searchInput = document.getElementById('buscar-destino');
            var suggestions = document.getElementById('sugerencias-destino');
            var storageKeys = {
                lat: 'checkout_destino_latitud',
                lng: 'checkout_destino_longitud',
                label: 'checkout_destino_label',
                detail: 'checkout_destino_detalle'
            };

            if (!latInput.value && sessionStorage.getItem(storageKeys.lat)) {
                latInput.value = sessionStorage.getItem(storageKeys.lat);
            }

            if (!lngInput.value && sessionStorage.getItem(storageKeys.lng)) {
                lngInput.value = sessionStorage.getItem(storageKeys.lng);
            }

            if (!destinoInput.value && sessionStorage.getItem(storageKeys.detail)) {
                destinoInput.value = sessionStorage.getItem(storageKeys.detail);
            }

            var initialLat = latInput.value ? parseFloat(latInput.value) : -17.7833;
            var initialLng = lngInput.value ? parseFloat(lngInput.value) : -63.1821;
            var initialZoom = latInput.value && lngInput.value ? 14 : 6;
            var checkoutMap = L.map('checkout-map').setView([initialLat, initialLng], initialZoom);
            var marker = null;

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(checkoutMap);

            function setDestination(lat, lng, label, zoom) {
                latInput.value = lat;
                lngInput.value = lng;
                sessionStorage.setItem(storageKeys.lat, lat);
                sessionStorage.setItem(storageKeys.lng, lng);
                sessionStorage.setItem(storageKeys.label, label || 'Punto seleccionado en el mapa');

                if (marker) {
                    marker.setLatLng([lat, lng]);
                } else {
                    marker = L.marker([lat, lng]).addTo(checkoutMap);
                }

                marker.bindPopup(label || 'Punto seleccionado en el mapa').openPopup();
                checkoutMap.setView([lat, lng], zoom || 15);

                if (destinoStatus) {
                    destinoStatus.innerHTML =
                        '<strong><i class="fas fa-check-circle text-success mr-1"></i>Destino seleccionado:</strong>' +
                        '<div class="small mt-1"></div>';
                    destinoStatus.querySelector('.small').textContent = label || 'Punto seleccionado en el mapa';
                }
            }

            if (latInput.value && lngInput.value) {
                setDestination(
                    latInput.value,
                    lngInput.value,
                    sessionStorage.getItem(storageKeys.label) || 'Punto seleccionado en el mapa',
                    initialZoom
                );
            }

            checkoutMap.on('click', function(e) {
                var lat = e.latlng.lat.toFixed(8);
                var lng = e.latlng.lng.toFixed(8);
                setDestination(lat, lng, 'Punto seleccionado en el mapa', 15);
            });

            destinoInput.addEventListener('input', function() {
                sessionStorage.setItem(storageKeys.detail, destinoInput.value);
            });

            if (destinoInput.value) {
                sessionStorage.setItem(storageKeys.detail, destinoInput.value);
            }

            function renderSuggestions(results) {
                suggestions.innerHTML = '';

                if (!results.length) {
                    suggestions.style.display = 'none';
                    return;
                }

                results.forEach(function(result) {
                    var button = document.createElement('button');
                    button.type = 'button';
                    button.className = 'list-group-item list-group-item-action';
                    button.textContent = result.display_name;
                    button.addEventListener('click', function() {
                        searchInput.value = result.display_name;
                        suggestions.style.display = 'none';
                        setDestination(parseFloat(result.lat).toFixed(8), parseFloat(result.lon).toFixed(8), result.display_name, 15);
                    });
                    suggestions.appendChild(button);
                });

                suggestions.style.display = 'block';
            }

            function searchDestination() {
                var query = searchInput.value.trim();

                if (query.length < 3) {
                    suggestions.style.display = 'none';
                    return;
                }

                fetch('/api/geocodificacion/buscar?q=' + encodeURIComponent(query), {
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                    .then(function(response) {
                        return response.json();
                    })
                    .then(function(data) {
                        renderSuggestions(data.success ? data.data : []);
                    })
                    .catch(function() {
                        suggestions.style.display = 'none';
                    });
            }

            document.getElementById('btn-buscar-destino').addEventListener('click', searchDestination);
            searchInput.addEventListener('keydown', function(event) {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    searchDestination();
                }
            });

            document.getElementById('btn-destino-actual').addEventListener('click', function() {
                if (!navigator.geolocation) {
                    alert('Tu navegador no permite obtener la ubicacion actual.');
                    return;
                }

                navigator.geolocation.getCurrentPosition(function(position) {
                    var lat = position.coords.latitude.toFixed(8);
                    var lng = position.coords.longitude.toFixed(8);
                    setDestination(lat, lng, 'Mi ubicacion actual', 16);
                }, function() {
                    alert('No se pudo obtener tu ubicacion actual.');
                });
            });

            setTimeout(function() {
                checkoutMap.invalidateSize();
            }, 250);

            $('#destinoMapModal').on('shown.bs.modal', function() {
                setTimeout(function() {
                    checkoutMap.invalidateSize();

                    if (latInput.value && lngInput.value) {
                        checkoutMap.setView([latInput.value, lngInput.value], 15);
                    }
                }, 150);
            });

            document.getElementById('btn-confirmar-destino').addEventListener('click', function() {
                if (!latInput.value || !lngInput.value) {
                    alert('Primero marca un punto en el mapa.');
                    return;
                }

                $('#destinoMapModal').modal('hide');
            });
        });

        function proceedToCheckout() {
            const form = document.getElementById('checkoutForm');
            const latInput = document.getElementById('destino_latitud');
            const lngInput = document.getElementById('destino_longitud');

            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            if (!latInput.value || !lngInput.value) {
                alert('Debes marcar el destino exacto en el mapa.');
                return;
            }

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
                    form.submit();
                }
            });
        }
    </script>
@endsection
