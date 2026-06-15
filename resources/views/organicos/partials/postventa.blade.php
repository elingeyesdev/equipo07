@php
    $modo = $modo ?? 'comprador';
    $esComprador = $modo === 'comprador';
    $esAdmin = $modo === 'admin';
    $finalizado = $detalle->estado_solicitud === 'aceptada'
        && (
            $detalle->recepcion_confirmada_at !== null
            || (
                $detalle->estado_transporte_actual === 'entregado'
                && in_array($detalle->pedido->estado, ['entregado', 'finalizado'], true)
            )
            || $detalle->pedido->estado === 'finalizado'
        );
    $reclamable = $detalle->estado_solicitud === 'aceptada'
        && (
            $detalle->recepcion_confirmada_at !== null
            || in_array($detalle->estado_transporte_actual, ['entregado', 'cancelado'], true)
            || $detalle->pedido->estado === 'finalizado'
        );
    $esMaquinaria = $detalle->product_type === 'maquinaria';
    $reclamoPropio = $detalle->reclamos->firstWhere('creador_id', auth()->id());
    $reclamosVisibles = $esComprador
        ? $detalle->reclamos->where('creador_id', auth()->id())
        : $detalle->reclamos;
@endphp

<div class="post-sale-box mt-3 border rounded bg-light p-3">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-2">
        <strong><i class="fas fa-comment-dots text-success mr-1"></i>Postventa {{ $esMaquinaria ? 'de la maquinaria' : 'del producto' }}</strong>
        <span class="badge badge-{{ $detalle->estado_transporte_actual === 'cancelado' ? 'danger' : 'success' }}">
            {{ $detalle->estado_transporte_label }}
        </span>
    </div>

    @if($errors->any())
        <div class="alert alert-danger py-2">
            <i class="fas fa-exclamation-circle mr-1"></i>{{ $errors->first() }}
        </div>
    @endif

    @if($detalle->estado_transporte_actual === 'cancelado')
        <div class="alert alert-warning mb-3">
            <strong><i class="fas fa-exclamation-triangle mr-1"></i>Motivo de cancelacion</strong>
            <div class="mt-1">{{ $detalle->cancelacion_motivo ?: 'No se registro una explicacion.' }}</div>
            @if($detalle->cancelado_at)
                <small class="d-block mt-1">Registrado el {{ $detalle->cancelado_at->format('d/m/Y H:i') }}</small>
            @endif
        </div>
    @endif

    @if($detalle->resenaProducto)
        <div class="bg-white border rounded p-3 mb-3">
            <div class="text-warning mb-1" aria-label="{{ $detalle->resenaProducto->estrellas }} estrellas">
                @for($i = 1; $i <= 5; $i++)
                    <i class="{{ $i <= $detalle->resenaProducto->estrellas ? 'fas' : 'far' }} fa-star"></i>
                @endfor
            </div>
            <div>{{ $detalle->resenaProducto->comentario }}</div>
            <small class="text-muted">
                {{ $detalle->resenaProducto->comprador?->name }} ·
                {{ $detalle->resenaProducto->created_at->format('d/m/Y') }}
            </small>
        </div>
    @elseif($esComprador && $finalizado)
        <form method="POST" action="{{ route('resenas.store', $detalle) }}" class="post-sale-review bg-white border rounded p-3 mb-3">
            @csrf
            <label class="font-weight-bold d-block">Califica tu {{ $esMaquinaria ? 'alquiler' : 'compra' }}</label>
            <div class="form-row">
                <div class="col-md-4 mb-2">
                    <fieldset class="star-rating">
                        <legend class="sr-only">Selecciona una calificacion de una a cinco estrellas</legend>
                        @for($i = 5; $i >= 1; $i--)
                            <input type="radio" name="estrellas" value="{{ $i }}"
                                id="rating-{{ $detalle->id }}-{{ $i }}"
                                {{ (int) old('estrellas') === $i ? 'checked' : '' }} required>
                            <label for="rating-{{ $detalle->id }}-{{ $i }}"
                                title="{{ $i }} {{ $i === 1 ? 'estrella' : 'estrellas' }}">
                                <i class="fas fa-star" aria-hidden="true"></i>
                                <span class="sr-only">{{ $i }} {{ $i === 1 ? 'estrella' : 'estrellas' }}</span>
                            </label>
                        @endfor
                    </fieldset>
                    <small class="star-rating-help">Toca una estrella para calificar</small>
                </div>
                <div class="col-md-8 mb-2">
                    <textarea name="comentario" class="form-control" rows="2" minlength="10" maxlength="1200"
                        placeholder="{{ $esMaquinaria ? 'Cuenta cómo fue la maquinaria, el alquiler y la atención' : 'Cuenta cómo fue el producto y la atención del vendedor' }}" required></textarea>
                </div>
            </div>
            <button class="btn btn-sm btn-success" type="submit">
                <i class="fas fa-paper-plane mr-1"></i>Publicar reseña
            </button>
        </form>
    @endif

    @if($reclamable && !$reclamoPropio && !$esAdmin)
        <details class="bg-white border rounded p-3">
            <summary class="font-weight-bold" style="cursor:pointer">
                <i class="fas fa-flag text-warning mr-1"></i>Abrir un reclamo
            </summary>
            <form method="POST" action="{{ route('reclamos.store', $detalle) }}" class="mt-3">
                @csrf
                <div class="form-group">
                    <label>Tipo de problema</label>
                    <select name="tipo" class="form-control" required>
                        <option value="">Selecciona una opcion</option>
                        @foreach(\App\Models\Reclamo::TIPOS as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Describe lo sucedido</label>
                    <textarea name="descripcion" class="form-control" rows="3" minlength="15" maxlength="2000"
                        placeholder="Incluye los detalles necesarios para revisar el caso" required></textarea>
                </div>
                <button class="btn btn-sm btn-warning" type="submit">
                    <i class="fas fa-paper-plane mr-1"></i>Enviar reclamo
                </button>
            </form>
        </details>
    @endif

    @foreach($reclamosVisibles as $reclamo)
        <a href="{{ route('reclamos.show', $reclamo) }}"
            class="d-flex justify-content-between align-items-center bg-white border rounded p-2 mt-2 text-dark">
            <span>
                <i class="fas fa-flag text-warning mr-1"></i>
                {{ $reclamo->tipo_label }}
                @if(!$esComprador)
                    <small class="text-muted">por {{ $reclamo->creador?->name }}</small>
                @endif
            </span>
            <span class="badge badge-info">{{ $reclamo->estado_label }}</span>
        </a>
    @endforeach
</div>
