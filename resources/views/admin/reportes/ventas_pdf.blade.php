<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Ventas y Rentabilidad</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 11px; }
        h1 { font-size: 18px; margin-bottom: 4px; }
        .subtitulo { font-size: 11px; color: #555; margin-bottom: 12px; }
        .kpis { margin-bottom: 15px; }
        .kpis div { margin-bottom: 3px; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th, td { border: 1px solid #ccc; padding: 4px 6px; text-align: left; }
        th { background: #f0f0f0; font-weight: bold; }
        .right { text-align: right; }
        .small { font-size: 10px; color: #777; }
    </style>
</head>
<body>
    <h1>Reporte de Análisis de Ventas y Rentabilidad</h1>
    <div class="subtitulo">
        Periodo:
        <strong>{{ $desde->format('d/m/Y') }}</strong>
        al
        <strong>{{ $hasta->format('d/m/Y') }}</strong>
        @if($categoria)
            | Categoría: <strong>{{ ucfirst($categoria) }}</strong>
        @endif
        @if($estado)
            | Estado: <strong>{{ ucfirst($estado) }}</strong>
        @endif
    </div>

    <div class="kpis">
        <div><strong>Total de pedidos:</strong> {{ $totalPedidos }}</div>
        <div><strong>Ingresos totales:</strong> Bs. {{ number_format($totalIngresos, 2) }}</div>
        <div><strong>Ticket promedio:</strong> Bs. {{ number_format($ticketPromedio, 2) }}</div>
        <div><strong>Tasa de conversión:</strong> {{ number_format($tasaConversion, 2) }}%</div>
    </div>

    <h3>Ventas por categoría</h3>
    <table>
        <thead>
        <tr>
            <th>Categoría</th>
            <th class="right">Pedidos</th>
            <th class="right">Cantidad</th>
            <th class="right">Ingresos (Bs.)</th>
            <th class="right">Precio promedio (Bs.)</th>
        </tr>
        </thead>
        <tbody>
        @foreach($ventasPorCategoria as $fila)
            <tr>
                <td>{{ ucfirst($fila->categoria) }}</td>
                <td class="right">{{ $fila->total_pedidos }}</td>
                <td class="right">{{ $fila->total_cantidad }}</td>
                <td class="right">{{ number_format($fila->ingresos_totales, 2) }}</td>
                <td class="right">{{ number_format($fila->precio_promedio, 2) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <p class="small">
        Generado el {{ now()->format('d/m/Y H:i') }} - Mercado Agrícola
    </p>
</body>
</html>
