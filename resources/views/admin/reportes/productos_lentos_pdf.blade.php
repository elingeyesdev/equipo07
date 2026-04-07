<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Reporte de Productos con Bajo Movimiento</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #555; padding: 4px 6px; }
        th { background: #e0e0e0; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <h1>Reporte de Productos con Bajo Movimiento</h1>
    <p>
        Periodo:
        <strong>{{ $desde->format('d/m/Y') }}</strong>
        al
        <strong>{{ $hasta->format('d/m/Y') }}</strong><br>
        Máx. ventas para considerar lento: <strong>{{ $minVentas }}</strong>
    </p>

    <p>
        Total productos: <strong>{{ $totalProductos }}</strong><br>
        Sin ventas: <strong>{{ $sinVentas }}</strong><br>
        Con pocas ventas: <strong>{{ $conPocasVentas }}</strong>
    </p>

    <table>
        <thead>
        <tr>
            <th>Categoría</th>
            <th>Producto</th>
            <th>Vendedor</th>
            <th class="text-right">Precio (Bs.)</th>
            <th class="text-right">Total vendido</th>
            <th class="text-right">Ingresos (Bs.)</th>
            <th>Última venta</th>
        </tr>
        </thead>
        <tbody>
        @foreach($productos as $p)
            <tr>
                <td>{{ $p->categoria }}</td>
                <td>{{ $p->producto }}</td>
                <td>{{ $p->vendedor }}</td>
                <td class="text-right">{{ number_format($p->precio, 2) }}</td>
                <td class="text-right">{{ $p->total_vendido }}</td>
                <td class="text-right">{{ number_format($p->ingresos, 2) }}</td>
                <td>
                    @if($p->ultima_venta)
                        {{ \Carbon\Carbon::parse($p->ultima_venta)->format('d/m/Y') }}
                    @else
                        Sin ventas
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <p class="small">
        Generado el {{ now()->format('d/m/Y H:i') }} - Mercado Agrícola
    </p>
</body>
</html>
