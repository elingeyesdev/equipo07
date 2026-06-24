<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Comprobante de reserva #{{ $pedido->id }}</title>
    <style>
        body { margin: 0; color: #172817; background: #eef4ec; font-family: Arial, sans-serif; }
        .sheet { width: min(900px, calc(100% - 28px)); margin: 20px auto; padding: 28px; border: 1px solid #d8e4d4; border-radius: 8px; background: #fff; }
        .header { display: flex; justify-content: space-between; gap: 18px; border-bottom: 2px solid #2f7d24; padding-bottom: 16px; }
        h1 { margin: 0; font-size: 1.45rem; }
        .badge { display: inline-block; padding: 7px 10px; border-radius: 999px; background: #e8f5e5; color: #245c1c; font-weight: 800; }
        .grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 12px; margin-top: 18px; }
        .box { padding: 12px; border: 1px solid #e2ebde; border-radius: 6px; background: #fbfdf9; }
        .box small { display: block; color: #667363; font-weight: 800; text-transform: uppercase; }
        table { width: 100%; margin-top: 18px; border-collapse: collapse; }
        th, td { padding: 10px; border-bottom: 1px solid #e6eee3; text-align: left; }
        th { background: #eef8ea; }
        .actions { margin: 18px auto; width: min(900px, calc(100% - 28px)); text-align: right; }
        button { padding: 10px 14px; border: 0; border-radius: 6px; color: #fff; background: #2f7d24; font-weight: 800; cursor: pointer; }
        @media print { body { background: #fff; } .actions { display: none; } .sheet { width: auto; margin: 0; border: 0; } }
    </style>
</head>
<body>
    @php
        $totalAceptado = $detallesAceptados->sum('subtotal');
    @endphp
    <div class="actions"><button onclick="window.print()">Imprimir / Guardar PDF</button></div>
    <main class="sheet">
        <div class="header">
            <div>
                <h1>Comprobante de reserva aceptada</h1>
                <p>Pedido #{{ $pedido->id }}</p>
            </div>
            <span class="badge">Reserva aceptada</span>
        </div>

        <section class="grid">
            <div class="box"><small>Comprador</small>{{ $pedido->user->name }}<br>{{ $pedido->user->email }}</div>
            <div class="box"><small>Fecha y hora de reserva</small>{{ $pedido->created_at->format('d/m/Y H:i:s') }}</div>
            <div class="box"><small>Destino de entrega</small>{{ $pedido->destino_entrega }}</div>
            <div class="box"><small>Contacto</small>{{ $pedido->telefono_contacto ?: 'No registrado' }}</div>
        </section>

        <table>
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Vendedor</th>
                    <th>Cantidad/Tiempo</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($detallesAceptados as $detalle)
                    <tr>
                        <td>{{ $detalle->nombre_producto }}</td>
                        <td>{{ $detalle->vendedor->name ?? 'No disponible' }}</td>
                        <td>{{ $detalle->cantidad_tiempo_texto }}</td>
                        <td>Bs {{ number_format((float) $detalle->subtotal, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3">Total aceptado</th>
                    <th>Bs {{ number_format((float) $totalAceptado, 2) }}</th>
                </tr>
            </tfoot>
        </table>
    </main>
</body>
</html>
