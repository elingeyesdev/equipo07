@php
    $resenas = $producto->resenas;
@endphp

<section class="card detail-card mb-4 product-detail-reviews">
    <div class="detail-card-header detail-card-header-success">
        <h5 class="mb-0"><i class="fas fa-star mr-2"></i>Calificaciones verificadas</h5>
    </div>
    <div class="card-body">
        @if($resenas->isNotEmpty())
            <div class="d-flex align-items-center mb-3">
                <strong class="h3 mb-0 mr-2">{{ number_format($resenas->avg('estrellas'), 1) }}</strong>
                <div>
                    <div class="text-warning">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="{{ $i <= round($resenas->avg('estrellas')) ? 'fas' : 'far' }} fa-star"></i>
                        @endfor
                    </div>
                    <small class="text-muted">{{ $resenas->count() }} compra(s) calificadas</small>
                </div>
            </div>
            <div class="row">
                @foreach($resenas->sortByDesc('created_at') as $resena)
                    <div class="col-md-6 mb-3">
                        <article class="border rounded p-3 h-100 bg-light">
                            <div class="text-warning mb-2">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="{{ $i <= $resena->estrellas ? 'fas' : 'far' }} fa-star"></i>
                                @endfor
                            </div>
                            <p class="mb-2">{{ $resena->comentario }}</p>
                            <small class="text-muted">
                                Compra verificada · {{ $resena->comprador?->name }} ·
                                {{ $resena->created_at->format('d/m/Y') }}
                            </small>
                        </article>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-muted">
                <i class="far fa-comment-dots mr-1"></i>
                Este producto todavía no tiene calificaciones de compras verificadas.
            </div>
        @endif
    </div>
</section>
