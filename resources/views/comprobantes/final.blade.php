<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Comprobante final #{{ $detalle->pedido_id }}</title>
    <style>
        body { margin: 0; color: #172817; background: #eef4ec; font-family: Arial, sans-serif; }
        .sheet { width: min(960px, calc(100% - 28px)); margin: 20px auto; padding: 28px; border: 1px solid #d8e4d4; border-radius: 8px; background: #fff; }
        .header { display: flex; justify-content: space-between; gap: 18px; border-bottom: 2px solid #2f7d24; padding-bottom: 16px; }
        h1, h2 { margin: 0; }
        h1 { font-size: 1.45rem; }
        h2 { margin-top: 22px; font-size: 1.05rem; }
        .badge { display: inline-block; padding: 7px 10px; border-radius: 999px; background: #e8f5e5; color: #245c1c; font-weight: 800; }
        .grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 12px; margin-top: 18px; }
        .box { padding: 12px; border: 1px solid #e2ebde; border-radius: 6px; background: #fbfdf9; }
        .box small { display: block; color: #667363; font-weight: 800; text-transform: uppercase; }
        table { width: 100%; margin-top: 12px; border-collapse: collapse; }
        th, td { padding: 10px; border-bottom: 1px solid #e6eee3; text-align: left; }
        th { background: #eef8ea; }
        .timeline { display: grid; gap: 8px; margin-top: 12px; }
        .event { display: grid; grid-template-columns: 190px 1fr; gap: 12px; padding: 10px; border: 1px solid #e6eee3; border-radius: 6px; }
        .signatures { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 16px; margin-top: 12px; }
        .signature { min-height: 140px; padding: 12px; border: 1px solid #d8e4d4; border-radius: 6px; }
        .signature img { display: block; max-width: 100%; max-height: 92px; margin: 8px auto; }
        .actions { margin: 18px auto; width: min(960px, calc(100% - 28px)); text-align: right; }
        button { padding: 10px 14px; border: 0; border-radius: 6px; color: #fff; background: #2f7d24; font-weight: 800; cursor: pointer; }
        @media print { body { background: #fff; } .actions { display: none; } .sheet { width: auto; margin: 0; border: 0; } }
    </style>
</head>
<body>
    @php
        $pedido = $detalle->pedido;
        $eventos = $detalle->transporteEventos->sortBy('created_at');
        $eventoRecogido = $eventos->firstWhere('estado_nuevo', 'producto_recogido')
            ?? $eventos->firstWhere('estado_nuevo', 'maquinaria_recogida_retorno');
        $eventoLlegada = $eventos->firstWhere('estado_nuevo', 'llego_destino')
            ?? $eventos->firstWhere('estado_nuevo', 'llego_retorno');
        $eventoEntrega = $eventos->firstWhere('estado_nuevo', 'esperando_confirmacion')
            ?? $eventos->firstWhere('estado_nuevo', 'entregado');
        $hitos = [
            ['Reserva inicial', $pedido->created_at, 'Pedido registrado por el comprador.'],
            ['Aceptacion del vendedor', $detalle->respondido_at, 'El vendedor acepto vender/reservar el producto.'],
            ['Producto recogido', $eventoRecogido?->created_at, 'El transportista marco la recogida del producto.'],
            ['Llegada al destino', $eventoLlegada?->created_at, 'El transportista llego al punto de entrega.'],
            ['Entrega marcada por transportista', $detalle->firma_transportista_at ?? $eventoEntrega?->created_at, 'El transportista firmo y dejo la entrega pendiente de confirmacion.'],
            ['Recepcion del comprador', $detalle->recepcion_confirmada_at, 'El comprador firmo y confirmo que recibio el producto.'],
        ];
    @endphp
    <div class="actions"><button onclick="window.print()">Imprimir / Guardar PDF</button></div>
    <main class="sheet">
        <div class="header">
            <div>
                <h1>Comprobante final de entrega</h1>
                <p>Pedido #{{ $pedido->id }} · Envio {{ $detalle->grupo_envio }}</p>
            </div>
            <span class="badge">Recepcion confirmada</span>
        </div>

        <section class="grid">
            <div class="box"><small>Comprador</small>{{ $pedido->user->name }}<br>{{ $pedido->user->email }}</div>
            <div class="box"><small>Vendedor</small>{{ $detalle->vendedor->name ?? 'No disponible' }}<br>{{ $detalle->vendedor->email ?? '' }}</div>
            <div class="box"><small>Transportista</small>{{ $detalle->transportista->name ?? 'Transporte externo / codigo QR' }}</div>
            <div class="box"><small>Lugar de entrega</small>{{ $pedido->destino_entrega }}<br>{{ $pedido->destino_latitud }}, {{ $pedido->destino_longitud }}</div>
        </section>

        <h2>Productos entregados</h2>
        <table>
            <thead><tr><th>Producto</th><th>Tipo</th><th>Cantidad/Tiempo</th><th>Subtotal</th></tr></thead>
            <tbody>
                @foreach ($detallesEnvio as $item)
                    <tr>
                        <td>{{ $item->nombre_producto }}</td>
                        <td>{{ ucfirst($item->product_type) }}</td>
                        <td>{{ $item->cantidad_tiempo_texto }}</td>
                        <td>Bs {{ number_format((float) $item->subtotal, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <h2>Fechas y trazabilidad</h2>
        <div class="timeline">
            @foreach ($hitos as [$titulo, $fecha, $descripcion])
                <div class="event">
                    <strong>{{ $titulo }}</strong>
                    <span>{{ $fecha?->format('d/m/Y H:i:s') ?? 'No registrado' }} · {{ $descripcion }}</span>
                </div>
            @endforeach
        </div>

        <h2>Firmas</h2>
        <div class="signatures">
            <div class="signature">
                <strong>Firma transportista</strong>
                @if ($detalle->firma_transportista)
                    <img src="{{ $detalle->firma_transportista }}" alt="Firma transportista">
                    <small>{{ $detalle->firma_transportista_at?->format('d/m/Y H:i:s') }}</small>
                @else
                    <p>No registrada.</p>
                @endif
            </div>
            <div class="signature">
                <strong>Firma comprador</strong>
                @if ($detalle->firma_comprador)
                    <img src="{{ $detalle->firma_comprador }}" alt="Firma comprador">
                    <small>{{ $detalle->firma_comprador_at?->format('d/m/Y H:i:s') }}</small>
                @else
                    <p>No registrada.</p>
                @endif
            </div>
        </div>
    </main>
</body>
</html>
